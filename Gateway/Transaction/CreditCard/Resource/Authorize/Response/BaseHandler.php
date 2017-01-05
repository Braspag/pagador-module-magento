<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface;
use Webjump\BraspagPagador\Api\CardTokenRepositoryInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\ResponseInterface as AntiFraudResponseInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Velocity\ResponseInterface as VelocityResponseInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Velocity\Reasons\ResponseInterface as VelocityReasonsResponseInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Avs\ResponseInterface as AvsResponseInterface;
/**

 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class BaseHandler extends AbstractHandler implements HandlerInterface
{
    protected function _handle($payment, $response)
    {
        $payment->setTransactionId($response->getPaymentPaymentId());
        $payment->setIsTransactionClosed(false);

        // if ($response->getPaymentFraudAnalysis() instanceof AntiFraudResponseInterface) {
        //     /** @var AntiFraudResponseInterface $antiFraudResponse */
        //     $antiFraudResponse = $response->getPaymentFraudAnalysis();

        //     $payment->setAdditionalInformation('braspag_antifraud_id', $antiFraudResponse->getId());
        //     $payment->setAdditionalInformation('braspag_antifraud_status', $antiFraudResponse->getStatus());
        //     $payment->setAdditionalInformation('braspag_antifraud_capture_on_low_risk', $antiFraudResponse->getCaptureOnLowRisk());
        //     $payment->setAdditionalInformation('braspag_antifraud_void_on_high_risk', $antiFraudResponse->getVoidOnHighRisk());
        //     $payment->setAdditionalInformation('braspag_antifraud_fraud_analysis_reasonCode', $antiFraudResponse->getFraudAnalysisReasonCode());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_address_info_code', $antiFraudResponse->getReplyDataAddressInfoCode());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_factor_code', $antiFraudResponse->getReplyDataFactorCode());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_score', $antiFraudResponse->getReplyDataScore());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_bin_country', $antiFraudResponse->getReplyDataBinCountry());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_card_issuer', $antiFraudResponse->getReplyDataCardIssuer());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_card_scheme', $antiFraudResponse->getReplyDataCardScheme());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_host_severity', $antiFraudResponse->getReplyDataHostSeverity());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_internet_info_code', $antiFraudResponse->getReplyDataInternetInfoCode());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_score', $antiFraudResponse->getReplyDataScore());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_binCountry', $antiFraudResponse->getReplyDataBinCountry());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_card_issuer', $antiFraudResponse->getReplyDataCardIssuer());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_card_scheme', $antiFraudResponse->getReplyDataCardScheme());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_host_severity', $antiFraudResponse->getReplyDataHostSeverity());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_internet_info_code', $antiFraudResponse->getReplyDataInternetInfoCode());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_ip_routing_method', $antiFraudResponse->getReplyDataIpRoutingMethod());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_score_model_used', $antiFraudResponse->getReplyDataScoreModelUsed());
        //     $payment->setAdditionalInformation('braspag_antifraud_reply_data_case_priority', $antiFraudResponse->getReplyDataCasePriority());
        // }

        // if ($response->getVelocityAnalysis() instanceof VelocityResponseInterface) {
        //     /** @var VelocityResponseInterface $velocityResponse */
        //     $velocityResponse = $response->getVelocityAnalysis();

        //     $payment->setAdditionalInformation('braspag_pagador_velocity_id', $velocityResponse->getId());
        //     $payment->setAdditionalInformation('braspag_pagador_velocity_result_message', $velocityResponse->getResultMessage());
        //     $payment->setAdditionalInformation('braspag_pagador_velocity_score', $velocityResponse->getScore());

        //     if ($velocityResponse->getRejectReasons() && is_array($velocityResponse)) {
        //         $reasons = [];
        //         /** @var VelocityReasonsResponseInterface $reason */
        //         foreach ($velocityResponse->getRejectReasons() as $reason) {
        //             $reasons[] = [
        //                 'rule_id' => $reason->getRuleId(),
        //                 'message' => $reason->getMessage(),
        //                 'hits_quantity' => $reason->getHitsQuantity(),
        //                 'expiration_block_time_in_seconds' => $reason->getExpirationBlockTimeInSeconds()
        //             ];
        //         }
        //         $payment->setAdditionalInformation('braspag_pagador_velocity_reject_reasons', serialize($reasons));
        //     }
        // }

        // if ($response->getAvs() instanceof AvsResponseInterface) {
        //     /** @var AvsResponseInterface $avsResponse */
        //     $avsResponse = $response->getAvs();

        //     $payment->setAdditionalInformation('braspag_pagador_avs_status', $avsResponse->getStatus());
        //     $payment->setAdditionalInformation('braspag_pagador_avs_return_code', $avsResponse->getReturnCode());
        // }

        // if ($response->getAuthenticationUrl()) {
        //     $payment->setAdditionalInformation('redirect_url', $response->getAuthenticationUrl());
        // }

        return $this;
    }
}
