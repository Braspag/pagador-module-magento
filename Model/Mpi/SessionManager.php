<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi;

use Braspag\BraspagPagador\Api\Data\MpiV3SessionInterface;
use Braspag\BraspagPagador\Api\MpiV3SessionManagerInterface;
use Braspag\BraspagPagador\Gateway\Mpi\Command\InitCommand;
use Braspag\BraspagPagador\Gateway\Mpi\Config\ConfigInterface as MpiV3Config;
use Braspag\BraspagPagador\Gateway\Mpi\Resource\Init\BuilderInterface;
use Braspag\BraspagPagador\Model\Mpi\Data\MpiV3SessionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Psr\Log\LoggerInterface;

/**
 * Webapi-facing service that starts an MPI V3 session for a given cart.
 *
 * Resolves the quote from the masked cart id (guest checkout) or plain id
 * (logged-in checkout), reserves the order id if not yet reserved (so the
 * INIT `orderNumber` matches what /v2/sales/ will later see), and delegates
 * to InitCommand.
 */
class SessionManager implements MpiV3SessionManagerInterface
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @var BuilderInterface
     */
    private $requestBuilder;

    /**
     * @var InitCommand
     */
    private $initCommand;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MpiV3Config
     */
    private $mpiConfig;

    /**
     * @var MpiV3SessionFactory
     */
    private $sessionFactory;

    /**
     * @param CartRepositoryInterface $cartRepository
     * @param QuoteIdMaskFactory      $quoteIdMaskFactory
     * @param BuilderInterface        $requestBuilder
     * @param InitCommand             $initCommand
     * @param LoggerInterface         $logger
     * @param MpiV3Config             $mpiConfig
     * @param MpiV3SessionFactory     $sessionFactory
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        BuilderInterface $requestBuilder,
        InitCommand $initCommand,
        LoggerInterface $logger,
        MpiV3Config $mpiConfig,
        MpiV3SessionFactory $sessionFactory
    ) {
        $this->cartRepository = $cartRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->requestBuilder = $requestBuilder;
        $this->initCommand = $initCommand;
        $this->logger = $logger;
        $this->mpiConfig = $mpiConfig;
        $this->sessionFactory = $sessionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function startSession($cartId)
    {
        try {
            $quote = $this->resolveQuote($cartId);
            if (!$quote->getReservedOrderId()) {
                $quote->reserveOrderId();
                $this->cartRepository->save($quote);
            }

            $request = $this->requestBuilder->build($quote);
            $initResult = $this->initCommand->execute($request);

            // Expose the reservedOrderId so the browser can pass the SAME
            // order number into MPIHelpers.getOrderBuilder().withOrderNumber()
            // during the challenge step. Cielo rejects mismatches between the
            // ENROLL OrderNumber and the challenge order carrier.
            $reservedOrderId = (string) $quote->getReservedOrderId();

            if ($this->mpiConfig->isDebugActive()) {
                $this->logger->debug(sprintf(
                    '[SessionManager] quoteId=%s reservedOrderId=%s referenceId=%s token.len=%d',
                    $quote->getId(),
                    $reservedOrderId,
                    $initResult['referenceId'] ?? '?',
                    strlen((string) ($initResult['token'] ?? ''))
                ));
            }

            /** @var MpiV3SessionInterface $session */
            $session = $this->sessionFactory->create();
            $session->setReferenceId((string) ($initResult['referenceId'] ?? ''));
            $session->setToken((string) ($initResult['token'] ?? ''));
            $session->setReservedOrderId($reservedOrderId);
            return $session;
        } catch (LocalizedException $e) {
            $this->logger->error('[Braspag MPI V3] SessionManager: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            $this->logger->error('[Braspag MPI V3] SessionManager unexpected: ' . $e->getMessage());
            throw new LocalizedException(
                new Phrase('MPI V3 session error: %1', [$e->getMessage()]),
                $e
            );
        }
    }

    /**
     * Resolves a quote from either a masked cart id (guest) or numeric id
     * (logged). Masked ids are 32-char strings; numeric ids are integers.
     *
     * @param string $cartId
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    private function resolveQuote($cartId)
    {
        if (is_numeric($cartId)) {
            return $this->cartRepository->getActive((int) $cartId);
        }

        $mask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->cartRepository->getActive((int) $mask->getQuoteId());
    }
}
