<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi;

use Braspag\BraspagPagador\Api\MpiV3AuthTokenManagerInterface;
use Braspag\BraspagPagador\Gateway\Mpi\Command\AuthCommand;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Lock\LockManagerInterface;
use Magento\Framework\Phrase;
use Psr\Log\LoggerInterface;

/**
 * Orchestrates fetching and caching the MPI V3 access_token.
 *
 * Concurrency: many INIT calls may fire in parallel across processes, each
 * potentially finding the cache empty at the same instant. A named lock via
 * LockManagerInterface serializes AUTH issuance so we don't burn our rate
 * limit on redundant token requests.
 */
class AuthTokenManager implements MpiV3AuthTokenManagerInterface
{
    /**
     * Lock name — kept short so backends with tight identifier limits (Redis
     * defaults are generous, but zend semaphores are not) accept it.
     */
    const LOCK_NAME = 'braspag_mpi_v3_auth';

    /**
     * Seconds to wait for the AUTH lock before giving up. Should exceed a
     * typical AUTH round-trip; if it doesn't, we prefer to fail fast rather
     * than pile up requests.
     */
    const LOCK_TIMEOUT_SECONDS = 10;

    /**
     * @var AccessTokenCache
     */
    private $cache;

    /**
     * @var AuthCommand
     */
    private $authCommand;

    /**
     * @var LockManagerInterface
     */
    private $lockManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param AccessTokenCache     $cache
     * @param AuthCommand          $authCommand
     * @param LockManagerInterface $lockManager
     * @param LoggerInterface      $logger
     */
    public function __construct(
        AccessTokenCache $cache,
        AuthCommand $authCommand,
        LockManagerInterface $lockManager,
        LoggerInterface $logger
    ) {
        $this->cache = $cache;
        $this->authCommand = $authCommand;
        $this->lockManager = $lockManager;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        $cached = $this->cache->get();
        if ($cached !== null) {
            return $cached;
        }

        // Acquire the lock: another process may already be minting a fresh
        // token; wait for them and re-check the cache. Initialize before the
        // try/finally so a lock() throw does not surface `undefined variable`
        // in the finally.
        $lockAcquired = false;
        try {
            $lockAcquired = $this->lockManager->lock(self::LOCK_NAME, self::LOCK_TIMEOUT_SECONDS);
        } catch (\Exception $e) {
            // Failing to acquire the lock (e.g. backend down) must not break
            // AUTH — fall through and issue the token without lock protection.
            $this->logger->warning('[Braspag MPI V3] AUTH lock unavailable: ' . $e->getMessage());
        }

        try {
            if ($lockAcquired) {
                $cached = $this->cache->get();
                if ($cached !== null) {
                    return $cached;
                }
            }

            $response = $this->authCommand->execute();
            $this->cache->set($response['access_token'], $response['expires_in']);
            return $response['access_token'];
        } catch (LocalizedException $e) {
            $this->logger->error(
                '[Braspag MPI V3] Failed to issue AUTH: ' . $e->getMessage()
            );
            throw $e;
        } catch (\Exception $e) {
            $this->logger->error(
                '[Braspag MPI V3] Unexpected error issuing AUTH: ' . $e->getMessage()
            );
            throw new LocalizedException(
                new Phrase('MPI V3 authentication error: %1', [$e->getMessage()]),
                $e
            );
        } finally {
            if ($lockAcquired) {
                $this->lockManager->unlock(self::LOCK_NAME);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function invalidate()
    {
        $this->cache->remove();
    }
}
