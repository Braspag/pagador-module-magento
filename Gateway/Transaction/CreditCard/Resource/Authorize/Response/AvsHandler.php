<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
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
class AvsHandler extends AbstractHandler implements HandlerInterface
{
    protected function _handle($payment, $response)
    {
        $avsResponse = $response->getAvs();

        if ($avsResponse instanceof AvsResponseInterface) {
            $payment->setAdditionalInformation('braspag_pagador_avs_status', $avsResponse->getStatus());
            $payment->setAdditionalInformation('braspag_pagador_avs_return_code', $avsResponse->getReturnCode());
        }

        return $this;
    }
}
