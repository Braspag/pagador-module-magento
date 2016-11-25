<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface;
use Webjump\BraspagPagador\Api\CardTokenRepositoryInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\ResponseInterface as AntiFraudResponseInterface;
/**

 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class ResponseHandler implements HandlerInterface
{
    protected $cardTokenRepository;

    public function __construct(
        CardTokenRepositoryInterface $cardTokenRepository
    ) {
        $this->setCardTokenRepository($cardTokenRepository);
    }

    public function handle(array $handlingSubject, array $response)
    {
        if (!isset($handlingSubject['payment']) || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        if (!isset($response['response']) || !$response['response'] instanceof ResponseInterface) {
            throw new \InvalidArgumentException('Braspag CreditCard Send Response Lib object should be provided');
        }

        /** @var ResponseInterface $response */
        $response = $response['response'];
        $paymentDO = $handlingSubject['payment'];
        $payment = $paymentDO->getPayment();

        $payment->setTransactionId($response->getPaymentPaymentId());
        $payment->setIsTransactionClosed(false);

        if ($response->getPaymentCardToken()) {
            $this->saveCardToken($payment, $response);
        }

        if (! ($response->getPaymentFraudAnalysis() instanceof AntiFraudResponseInterface)) {
            return $this;
        }

        /** @var AntiFraudResponseInterface $antiFraudResponse */
        $antiFraudResponse = $response->getPaymentFraudAnalysis();

        $payment->setAdditionalInformation('antifraud_id', $antiFraudResponse->getId());
        $payment->setAdditionalInformation('antifraud_status', $antiFraudResponse->getStatus());
        $payment->setAdditionalInformation('antifraud_capture_on_low_risk', $antiFraudResponse->getCaptureOnLowRisk());
        $payment->setAdditionalInformation('antifraud_void_on_high_risk', $antiFraudResponse->getVoidOnHighRisk());
        $payment->setAdditionalInformation('antifraud_fraud_analysis_reasonCode', $antiFraudResponse->getFraudAnalysisReasonCode());
        $payment->setAdditionalInformation('antifraud_reply_data_address_info_code', $antiFraudResponse->getReplyDataAddressInfoCode());
        $payment->setAdditionalInformation('antifraud_reply_data_factor_code', $antiFraudResponse->getReplyDataFactorCode());
        $payment->setAdditionalInformation('antifraud_reply_data_score', $antiFraudResponse->getReplyDataScore());
        $payment->setAdditionalInformation('antifraud_reply_data_bin_country', $antiFraudResponse->getReplyDataBinCountry());
        $payment->setAdditionalInformation('antifraud_reply_data_card_issuer', $antiFraudResponse->getReplyDataCardIssuer());
        $payment->setAdditionalInformation('antifraud_reply_data_card_scheme', $antiFraudResponse->getReplyDataCardScheme());
        $payment->setAdditionalInformation('antifraud_reply_data_host_severity', $antiFraudResponse->getReplyDataHostSeverity());
        $payment->setAdditionalInformation('antifraud_reply_data_internet_info_code', $antiFraudResponse->getReplyDataInternetInfoCode());
        $payment->setAdditionalInformation('antifraud_reply_data_score', $antiFraudResponse->getReplyDataScore());
        $payment->setAdditionalInformation('antifraud_reply_data_binCountry', $antiFraudResponse->getReplyDataBinCountry());
        $payment->setAdditionalInformation('antifraud_reply_data_card_issuer', $antiFraudResponse->getReplyDataCardIssuer());
        $payment->setAdditionalInformation('antifraud_reply_data_card_scheme', $antiFraudResponse->getReplyDataCardScheme());
        $payment->setAdditionalInformation('antifraud_reply_data_host_severity', $antiFraudResponse->getReplyDataHostSeverity());
        $payment->setAdditionalInformation('antifraud_reply_data_internet_info_code', $antiFraudResponse->getReplyDataInternetInfoCode());
        $payment->setAdditionalInformation('antifraud_reply_data_ip_routing_method', $antiFraudResponse->getReplyDataIpRoutingMethod());
        $payment->setAdditionalInformation('antifraud_reply_data_score_model_used', $antiFraudResponse->getReplyDataScoreModelUsed());
        $payment->setAdditionalInformation('antifraud_reply_data_case_priority', $antiFraudResponse->getReplyDataCasePriority());

        return $this;
    }

    protected function saveCardToken($payment, $response)
    {
        if ($cardToken = $this->getCardTokenRepository()->get($response->getPaymentCardToken())) {
            return $cardToken;
        }

        $cardToken = $this->getCardTokenRepository()->create(
            $response->getPaymentCardNumberEncrypted(),
            $response->getPaymentCardToken(),
            $response->getPaymentCardProvider(),
            $response->getPaymentCardBrand()
        );

        $this->getCardTokenRepository()->save($cardToken);

        return $cardToken;
    }

    protected function getCardTokenRepository()
    {
        return $this->CardTokenRepository;
    }

    protected function setCardTokenRepository(CardTokenRepositoryInterface $cardTokenRepository)
    {
        $this->CardTokenRepository = $cardTokenRepository;

        return $this;
    }
}
