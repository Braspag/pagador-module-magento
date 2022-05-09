<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Avs\ResponseInterface as AvsResponseInterface;
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
class AvsHandler extends AbstractHandler implements HandlerInterface
{
    public function __construct(
        Response $response
    ) {
        $this->setResponse($response);
    }

    protected function _handle($payment, $response)
    {
        $avsResponse = $response->getAvs();

        if ($avsResponse instanceof AvsResponseInterface) {
            $payment->setAdditionalInformation('braspag_pagador_avs_status', $avsResponse->getStatus());
            $payment->setAdditionalInformation('braspag_pagador_avs_return_code', $avsResponse->getReturnCode());
        }

        return $response;
    }
}
