<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Velocity\ResponseInterface as VelocityResponseInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Velocity\Reasons\ResponseInterface as VelocityReasonsResponseInterface;
use Webjump\Braspag\Pagador\Transaction\Resource\CreditCard\Send\Response;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Response\AbstractHandler;

/**

 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class VelocityAnalysisHandler extends AbstractHandler implements HandlerInterface
{
    public function __construct(
        Response $response
    ) {
        $this->setResponse($response);
    }

    protected function _handle($payment, $response)
    {
        $velocityResponse = $response->getVelocityAnalysis();

        if (!$velocityResponse instanceof VelocityResponseInterface) {
            return $this;
        }

        $payment->setAdditionalInformation('braspag_pagador_velocity_id', $velocityResponse->getId());
        $payment->setAdditionalInformation('braspag_pagador_velocity_result_message', $velocityResponse->getResultMessage());
        $payment->setAdditionalInformation('braspag_pagador_velocity_score', $velocityResponse->getScore());

        $rejectReasons = $velocityResponse->getRejectReasons();

        if (!empty($rejectReasons)) {
            $payment->setAdditionalInformation('braspag_pagador_velocity_reject_reasons', $this->getRejectReasons($rejectReasons));
        }

        return $response;
    }

    protected function getRejectReasons($rejectReasons)
    {
        $reasons = [];

        /** @var VelocityReasonsResponseInterface $reason */
        foreach ($rejectReasons as $reason) {
            $reasons[] = [
                'rule_id' => $reason->getRuleId(),
                'message' => $reason->getMessage(),
                'hits_quantity' => $reason->getHitsQuantity(),
                'expiration_block_time_in_seconds' => $reason->getExpirationBlockTimeInSeconds()
            ];
        }

        return serialize($reasons);
    }
}
