<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Voucher\Resource\Order\Response;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Braspag\Braspag\Pagador\Transaction\Api\Voucher\Send\ResponseInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Voucher\Config\ConfigInterface as VoucherConfigInterface;

/**
 * Validator
 *
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 *  @author Esmerio Neto <esmerio.neto@signativa.com.br>
 *
 * SPDX-License-Identifier: Apache-2.0
 */
class Validator implements ValidatorInterface
{
    const NOTFINISHED = 0;
    const AUTHORIZED = 1;
    const PAYMENTCONFIRMED = 2;
    const DENIED = 3;
    const VOIDED = 10;
    const REFUNDED = 11;
    const PENDING = 12;
    const ABORTED = 13;
    const SCHEDULED = 20;

    protected $statusDenied;
    protected $voucherConfigInterface;

    public function __construct(
        VoucherConfigInterface $voucherConfigInterface
    ) {
        $this->voucherConfigInterface = $voucherConfigInterface;
    }

    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['response']) || !$validationSubject['response'] instanceof ResponseInterface) {
            throw new \InvalidArgumentException('Braspag Voucher Authorize Response object should be provided');
        }

        $response = $validationSubject['response'];
        $status = true;
        $message = [];

        if (in_array($response->getPaymentStatus(), $this->getStatusDenied($response))) {
            $status = false;
            $message = $response->getPaymentProviderReturnMessage();
            if (empty($message)) {
                $message = "Voucher Card Payment Failure. #BP{$response->getPaymentStatus()}";
            }
        }

        return new Result($status, [$message]);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    protected function getStatusDenied(ResponseInterface $response)
    {
        if (! $this->statusDenied) {
            $this->statusDenied = [self::DENIED, self::VOIDED, self::ABORTED];
        }

        return $this->statusDenied;
    }
}