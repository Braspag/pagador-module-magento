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
class BaseHandler extends AbstractHandler implements HandlerInterface
{
    public function __construct(
        Response $response
    ) {
        $this->setResponse($response);
    }

    /**
     * @param $payment
     * @param $response
     * @return $this
     */
    protected function _handle($payment, $response)
    {
        list($paymentProvider, $paymentBrand) = array_pad(explode('-', $payment->getCcType(), 2), 2, null);
        list($responseProvider, $responseBrand) = array_pad(explode('-', $response->getPaymentCardProvider(), 2), 2, null);
        $payment->setAdditionalInformation('send_provider', $paymentProvider);
        $payment->setAdditionalInformation('receive_provider', $responseProvider);

        $payment->setTransactionId($response->getPaymentPaymentId());
        $payment->setAdditionalInformation('redirect_url', $response->getPaymentAuthenticationUrl());
        $payment->setAdditionalInformation('braspag_payment_status', $response->getPaymentStatus());

        $payment->setIsTransactionClosed(false);

        return $response;
    }
}