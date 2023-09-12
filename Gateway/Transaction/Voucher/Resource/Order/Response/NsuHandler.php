<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Voucher\Resource\Order\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Braspag\Braspag\Pagador\Transaction\Resource\Voucher\Send\Response;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Response\AbstractHandler;

/**

 * Braspag Transaction Voucher Order Response Handler
 *
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 *  @author Esmerio Neto <esmerio.neto@signativa.com.br>
 *
 * SPDX-License-Identifier: Apache-2.0
 */
class NsuHandler extends AbstractHandler implements HandlerInterface
{
    public function __construct(
        Response $response
    ) {
        $this->setResponse($response);
    }

    protected function _handle($payment, $response)
    {
        $payment->setAdditionalInformation('proof_of_sale', $response->getPaymentProofOfSale());
        $payment->setAdditionalInformation('payment_token', $response->getPaymentPaymentId());
        list($paymentProvider, $paymentBrand) = array_pad(explode('-', $payment->getCcType(), 2), 2, null);
        list($responseProvider, $responseBrand) = array_pad(explode('-', $response->getPaymentCardProvider(), 2), 2, null);
        $payment->setAdditionalInformation('send_provider', $paymentProvider);
        $payment->setAdditionalInformation('receive_provider', $responseProvider);

        return $response;
    }
}