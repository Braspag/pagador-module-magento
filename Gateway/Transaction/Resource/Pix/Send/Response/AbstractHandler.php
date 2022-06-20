<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Gateway\Transaction\Resource\Pix\Send\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Payment\Model\InfoInterface;
use Braspag\BraspagPagador\Model\Qrcode;

abstract class AbstractHandler implements HandlerInterface
{
    const ADDITIONAL_INFORMATION_PAYMENT_ID = 'payment_id';

    const ADDITIONAL_INFORMATION_EXPIRATION_DATE = 'expiration_date';

    const ADDITIONAL_INFORMATION_PIX_LINK = 'pix_link';

    const ADDITIONAL_INFORMATION_PIX_QRCODEBASE64IMAGE = 'QrcodeBase64Image';

    const ADDITIONAL_INFORMATION_PIX_QRCODESTRING = 'QrCodeString';

    const ADDITIONAL_INFORMATION_PIX_TRANSACTIONID = 'pix_transaction_id';

    protected $qrcodeHandler;

    public function __construct(
        Qrcode $qrcodeHandler
    ) {
        $this->qrcodeHandler = $qrcodeHandler;
    }

    public function handle(array $handlingSubject, array $response)
    {
        /**
         * @var \Magento\Payment\Gateway\Data\PaymentDataObject $paymentDO
         */
        $paymentDO = $handlingSubject['payment'];

        $payment = $paymentDO->getPayment();
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PAYMENT_ID, $response['payment_method']['id']);
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_EXPIRATION_DATE, $response['payment_method']['expiration_date']);
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PIX_LINK, $response['payment_method']['pix_link']);
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PIX_QRCODEBASE64IMAGE, $response['payment_method']['QrcodeBase64Image']['emv']);
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PIX_TRANSACTIONID, $response['payment_method']['pix_transaction_id']);
        $payment->setAdditionalInformation(self::ADDITIONAL_INFORMATION_PIX_QRCODESTRING, $this->qrcodeHandler->saveToFile($response['payment_method']['QrcodeBase64Image']['emv'], $response['payment_method']['id']));

        $this->_handle($payment, $response);

        return $this;
    }

    /**
     * @param InfoInterface $payment
     * @param $response
     * @return mixed
     */
    abstract protected function _handle($payment, $response);
}