<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Voucher\Resource\Order\Request;

use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Validator\Result;
use Braspag\Braspag\Pagador\Transaction\Api\Voucher\Send\RequestInterface;
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
    protected $voucherConfigInterface;

    public function __construct(
        VoucherConfigInterface $voucherConfigInterface
    ) {
        $this->voucherConfigInterface = $voucherConfigInterface;
    }

    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['request']) || !$validationSubject['request'] instanceof RequestInterface) {
            throw new \InvalidArgumentException('Braspag Voucher Order Request object should be provided');
        }

        $request = $validationSubject['request'];
        $status = true;
        $message = [];

        if ($this->voucherConfigInterface->isAuth3Ds20Active()) {
            $failureType = $request->getPaymentExternalAuthenticationFailureType();

            if (
                $failureType == VoucherConfigInterface::BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR
                && !$this->voucherConfigInterface->isAuth3Ds20AuthorizedOnError()
            ) {
                return new Result(false, ["Voucher Card Payment Failure. #MPI{$failureType}"]);
            }

            if (
                $failureType == VoucherConfigInterface::BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE
                 && !$this->voucherConfigInterface->isAuth3Ds20AuthorizedOnFailure()
            ) {
                return new Result(false, ["Voucher Card Payment Failure. #MPI{$failureType}"]);
            }

            if (
                $failureType == VoucherConfigInterface::BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED
                && !$this->voucherConfigInterface->isAuth3Ds20AuthorizeOnUnenrolled()
            ) {
                return new Result(false, ["Voucher Card Payment Failure. #MPI{$failureType}"]);
            }

            if (
                $failureType == VoucherConfigInterface::BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_UNSUPPORTED_BRAND
                && !$this->voucherConfigInterface->isAuth3Ds20AuthorizeOnUnsupportedBrand()
            ) {
                return new Result(false, ["Voucher Card Payment Failure. #MPI{$failureType}"]);
            }

            if (
                !$this->voucherConfigInterface->getIsTestEnvironment()
                && !preg_match("#cielo#is", $request->getPaymentProvider())
                && $failureType != VoucherConfigInterface::BRASPAG_PAGADOR_VOUCHER_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED
            ) {
                return new Result(false, ["Voucher Card Payment Failure. #MPI{$failureType}"]);
            }
        }

        return new Result($status, [$message]);
    }
}