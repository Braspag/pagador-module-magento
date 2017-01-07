<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
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
class AntiFraudHandler extends AbstractHandler implements HandlerInterface
{
    protected function _handle($payment, $response)
    {
        $antiFraudResponse = $response->getPaymentFraudAnalysis();

        if ($antiFraudResponse instanceof AntiFraudResponseInterface) {
            $payment->setAdditionalInformation('braspag_antifraud_id', $antiFraudResponse->getId());
            $payment->setAdditionalInformation('braspag_antifraud_status', $antiFraudResponse->getStatus());
            $payment->setAdditionalInformation('braspag_antifraud_capture_on_low_risk', $antiFraudResponse->getCaptureOnLowRisk());
            $payment->setAdditionalInformation('braspag_antifraud_void_on_high_risk', $antiFraudResponse->getVoidOnHighRisk());
            $payment->setAdditionalInformation('braspag_antifraud_fraud_analysis_reasonCode', $antiFraudResponse->getFraudAnalysisReasonCode());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_address_info_code', $antiFraudResponse->getReplyDataAddressInfoCode());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_factor_code', $antiFraudResponse->getReplyDataFactorCode());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_score', $antiFraudResponse->getReplyDataScore());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_bin_country', $antiFraudResponse->getReplyDataBinCountry());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_card_issuer', $antiFraudResponse->getReplyDataCardIssuer());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_card_scheme', $antiFraudResponse->getReplyDataCardScheme());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_host_severity', $antiFraudResponse->getReplyDataHostSeverity());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_internet_info_code', $antiFraudResponse->getReplyDataInternetInfoCode());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_ip_routing_method', $antiFraudResponse->getReplyDataIpRoutingMethod());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_score_model_used', $antiFraudResponse->getReplyDataScoreModelUsed());
            $payment->setAdditionalInformation('braspag_antifraud_reply_data_case_priority', $antiFraudResponse->getReplyDataCasePriority());
        }

        return $this;
    }
}
