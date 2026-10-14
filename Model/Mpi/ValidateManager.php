<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi;

use Braspag\BraspagPagador\Api\Data\MpiV3ValidateResultInterface;
use Braspag\BraspagPagador\Api\MpiV3AuthTokenManagerInterface;
use Braspag\BraspagPagador\Api\MpiV3ValidateManagerInterface;
use Braspag\BraspagPagador\Gateway\Mpi\Command\ValidateCommand;
use Braspag\BraspagPagador\Gateway\Mpi\Config\ConfigInterface as MpiV3Config;
use Braspag\BraspagPagador\Gateway\Mpi\Resource\Validate\BuilderInterface;
use Braspag\BraspagPagador\Model\Mpi\Data\MpiV3AuthenticationFactory;
use Braspag\BraspagPagador\Model\Mpi\Data\MpiV3ValidateResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Psr\Log\LoggerInterface;

class ValidateManager implements MpiV3ValidateManagerInterface
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
     * @var ValidateCommand
     */
    private $validateCommand;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MpiV3Config
     */
    private $mpiConfig;

    /**
     * @var MpiV3ValidateResultFactory
     */
    private $validateResultFactory;

    /**
     * @var MpiV3AuthenticationFactory
     */
    private $authenticationFactory;

    /**
     * @var MpiV3AuthTokenManagerInterface
     */
    private $authTokenManager;

    /**
     * @param CartRepositoryInterface       $cartRepository
     * @param QuoteIdMaskFactory            $quoteIdMaskFactory
     * @param BuilderInterface              $requestBuilder
     * @param ValidateCommand               $validateCommand
     * @param LoggerInterface               $logger
     * @param MpiV3Config                   $mpiConfig
     * @param MpiV3ValidateResultFactory    $validateResultFactory
     * @param MpiV3AuthenticationFactory    $authenticationFactory
     * @param MpiV3AuthTokenManagerInterface $authTokenManager
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        BuilderInterface $requestBuilder,
        ValidateCommand $validateCommand,
        LoggerInterface $logger,
        MpiV3Config $mpiConfig,
        MpiV3ValidateResultFactory $validateResultFactory,
        MpiV3AuthenticationFactory $authenticationFactory,
        MpiV3AuthTokenManagerInterface $authTokenManager
    ) {
        $this->cartRepository = $cartRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->requestBuilder = $requestBuilder;
        $this->validateCommand = $validateCommand;
        $this->logger = $logger;
        $this->mpiConfig = $mpiConfig;
        $this->validateResultFactory = $validateResultFactory;
        $this->authenticationFactory = $authenticationFactory;
        $this->authTokenManager = $authTokenManager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($cartId, $transactionId, array $card)
    {
        try {
            $quote = $this->resolveQuote($cartId);
            $request = $this->requestBuilder->build($quote, $transactionId, $card);
            $result = $this->validateCommand->execute($request);

            if ($this->mpiConfig->isDebugActive()) {
                $auth = isset($result['authentication']) && is_array($result['authentication'])
                    ? $result['authentication']
                    : [];
                $this->logger->debug(sprintf(
                    '[ValidateManager] quoteId=%s orderNumber=%s status=%s reason=%s/%s eci=%s version=%s',
                    $quote->getId(),
                    $quote->getReservedOrderId(),
                    $result['status'] ?? '?',
                    $result['reasonCode'] ?? '',
                    $result['reasonMessage'] ?? '',
                    $auth['eci'] ?? '',
                    $auth['version'] ?? ''
                ));
            }

            // Session Cielo já foi consumida pelo par ENROLL(status=2) +
            // VALIDATE. Invalidamos aqui para que o próximo checkout pegue
            // token/session novos via AUTH.
            $this->authTokenManager->invalidate();

            return $this->hydrateResult($result);
        } catch (LocalizedException $e) {
            $this->authTokenManager->invalidate();
            $this->logger->error('[Braspag MPI V3] ValidateManager: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            $this->authTokenManager->invalidate();
            $this->logger->error('[Braspag MPI V3] ValidateManager unexpected: ' . $e->getMessage());
            throw new LocalizedException(
                new Phrase('MPI V3 validation error: %1', [$e->getMessage()]),
                $e
            );
        }
    }

    /**
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

    /**
     * Turns the flat array from ValidateCommand into typed DTOs so the REST
     * response comes as a proper JSON object (same reason as EnrollManager).
     *
     * @param array $result
     * @return MpiV3ValidateResultInterface
     */
    private function hydrateResult(array $result)
    {
        /** @var MpiV3ValidateResultInterface $dto */
        $dto = $this->validateResultFactory->create();
        $dto->setStatus((int) ($result['status'] ?? 0));
        $dto->setReasonCode((string) ($result['reasonCode'] ?? ''));
        $dto->setReasonMessage((string) ($result['reasonMessage'] ?? ''));

        if (isset($result['authentication']) && is_array($result['authentication'])) {
            $auth = $this->authenticationFactory->create();
            $auth->setCavv((string) ($result['authentication']['cavv'] ?? ''));
            $auth->setXid((string) ($result['authentication']['xid'] ?? ''));
            $auth->setEci((string) ($result['authentication']['eci'] ?? ''));
            $auth->setVersion((string) ($result['authentication']['version'] ?? ''));
            $auth->setDirectoryServerTransactionId(
                (string) ($result['authentication']['directoryServerTransactionId'] ?? '')
            );
            $dto->setAuthentication($auth);
        }

        return $dto;
    }
}
