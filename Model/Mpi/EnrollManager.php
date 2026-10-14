<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Model\Mpi;

use Braspag\BraspagPagador\Api\Data\MpiV3EnrollResultInterface;
use Braspag\BraspagPagador\Api\MpiV3AuthTokenManagerInterface;
use Braspag\BraspagPagador\Api\MpiV3EnrollManagerInterface;
use Braspag\BraspagPagador\Gateway\Mpi\Command\EnrollCommand;
use Braspag\BraspagPagador\Gateway\Mpi\Config\ConfigInterface as MpiV3Config;
use Braspag\BraspagPagador\Model\Mpi\Data\MpiV3AuthenticationFactory;
use Braspag\BraspagPagador\Model\Mpi\Data\MpiV3ChallengeFactory;
use Braspag\BraspagPagador\Model\Mpi\Data\MpiV3EnrollResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Psr\Log\LoggerInterface;

/**
 * Webapi-facing ENROLL orchestrator. Resolves the quote, builds the payload,
 * calls Cielo and reshapes the response into a frontend-friendly envelope:
 *   { status, reasonCode, reasonMessage, challenge?, authentication? }
 */
class EnrollManager implements MpiV3EnrollManagerInterface
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
     * @var PayloadBuilder
     */
    private $payloadBuilder;

    /**
     * @var EnrollCommand
     */
    private $enrollCommand;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MpiV3Config
     */
    private $mpiConfig;

    /**
     * @var MpiV3EnrollResultFactory
     */
    private $enrollResultFactory;

    /**
     * @var MpiV3ChallengeFactory
     */
    private $challengeFactory;

    /**
     * @var MpiV3AuthenticationFactory
     */
    private $authenticationFactory;

    /**
     * @var MpiV3AuthTokenManagerInterface
     */
    private $authTokenManager;

    /**
     * @param CartRepositoryInterface        $cartRepository
     * @param QuoteIdMaskFactory             $quoteIdMaskFactory
     * @param PayloadBuilder                 $payloadBuilder
     * @param EnrollCommand                  $enrollCommand
     * @param LoggerInterface                $logger
     * @param MpiV3Config                    $mpiConfig
     * @param MpiV3EnrollResultFactory       $enrollResultFactory
     * @param MpiV3ChallengeFactory          $challengeFactory
     * @param MpiV3AuthenticationFactory     $authenticationFactory
     * @param MpiV3AuthTokenManagerInterface $authTokenManager
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        PayloadBuilder $payloadBuilder,
        EnrollCommand $enrollCommand,
        LoggerInterface $logger,
        MpiV3Config $mpiConfig,
        MpiV3EnrollResultFactory $enrollResultFactory,
        MpiV3ChallengeFactory $challengeFactory,
        MpiV3AuthenticationFactory $authenticationFactory,
        MpiV3AuthTokenManagerInterface $authTokenManager
    ) {
        $this->cartRepository = $cartRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->payloadBuilder = $payloadBuilder;
        $this->enrollCommand = $enrollCommand;
        $this->logger = $logger;
        $this->mpiConfig = $mpiConfig;
        $this->enrollResultFactory = $enrollResultFactory;
        $this->challengeFactory = $challengeFactory;
        $this->authenticationFactory = $authenticationFactory;
        $this->authTokenManager = $authTokenManager;
    }

    /**
     * {@inheritdoc}
     */
    public function enroll($cartId, array $browserInfo, array $card, $installments, $paymentMethod)
    {
        try {
            $quote = $this->resolveQuote($cartId);
            if (!$quote->getReservedOrderId()) {
                $quote->reserveOrderId();
                $this->cartRepository->save($quote);
            }

            $request = $this->payloadBuilder->build(
                $quote,
                $browserInfo,
                $card,
                (int) $installments,
                (string) $paymentMethod
            );

            $result = $this->enrollCommand->execute($request);

            if ($this->mpiConfig->isDebugActive()) {
                $this->logger->debug(sprintf(
                    '[EnrollManager] quoteId=%s orderNumber=%s status=%s reason=%s/%s path=%s',
                    $quote->getId(),
                    $quote->getReservedOrderId(),
                    $result['status'] ?? '?',
                    $result['reasonCode'] ?? '',
                    $result['reasonMessage'] ?? '',
                    ($result['status'] ?? 0) === 1 ? 'frictionless'
                        : (($result['status'] ?? 0) === 2 ? 'challenge' : 'failed')
                ));
            }

            // O access_token da Cielo é amarrado a uma session Cielo (o JWT
            // carrega o ReferenceId como claim). Se o ENROLL retornou Status=2
            // (challenge), a MESMA session precisa ser usada no VALIDATE que
            // virá em seguida — invalidar aqui força novo AUTH e o VALIDATE
            // devolve HTTP 409 ("session mismatch"). Nesse caso, o
            // ValidateManager é quem invalida o token depois do VALIDATE.
            //
            // Para Status=1 (frictionless — não precisa VALIDATE) ou Status
            // diferente (falha, sem challenge), invalidamos aqui para o
            // próximo checkout pegar token novo.
            $status = (int) ($result['status'] ?? 0);
            if ($status !== 2) {
                $this->authTokenManager->invalidate();
            }

            return $this->hydrateResult($result);
        } catch (LocalizedException $e) {
            // Também invalida em caso de erro — não sabemos em que estado a
            // session Cielo ficou após o erro, mais seguro pedir token novo
            // no próximo checkout.
            $this->authTokenManager->invalidate();
            $this->logger->error('[Braspag MPI V3] EnrollManager: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            $this->authTokenManager->invalidate();
            $this->logger->error('[Braspag MPI V3] EnrollManager unexpected: ' . $e->getMessage());
            throw new LocalizedException(
                new Phrase('MPI V3 enrollment error: %1', [$e->getMessage()]),
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
     * Transforms the flat array coming from EnrollCommand into typed DTOs so
     * the Magento REST TypeProcessor serializes the response as a proper JSON
     * object (with named keys) instead of a positional array.
     *
     * @param array $result
     * @return MpiV3EnrollResultInterface
     */
    private function hydrateResult(array $result)
    {
        /** @var MpiV3EnrollResultInterface $dto */
        $dto = $this->enrollResultFactory->create();
        $dto->setStatus((int) ($result['status'] ?? 0));
        $dto->setReasonCode((string) ($result['reasonCode'] ?? ''));
        $dto->setReasonMessage((string) ($result['reasonMessage'] ?? ''));

        if (isset($result['challenge']) && is_array($result['challenge'])) {
            $challenge = $this->challengeFactory->create();
            $challenge->setAcsUrl((string) ($result['challenge']['acsUrl'] ?? ''));
            $challenge->setPareq((string) ($result['challenge']['pareq'] ?? ''));
            $challenge->setTransactionId((string) ($result['challenge']['transactionId'] ?? ''));
            $dto->setChallenge($challenge);
        }

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
