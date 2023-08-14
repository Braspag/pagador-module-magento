<?php

namespace Braspag\BraspagPagador\Model\Payment\Transaction\Voucher\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Voucher\Config\ConfigInterface;

/**
 * Braspag Transaction DebitCard Authorize Command
 *
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 *  @author Esmerio Neto <esmerio.neto@signativa.com.br>
 *
 * SPDX-License-Identifier: Apache-2.0
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_voucher';

    protected $voucherConfig;

    public function __construct(
        ConfigInterface $voucherConfig
    ) {
        $this->setVoucherConfig($voucherConfig);
    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'vcform' => [
                    'bpmpi_authentication' => [
                        'active' => $this->getVoucherConfig()->isAuth3Ds20Active(),
                        'mastercard_notify_only' => $this->getVoucherConfig()->isAuth3Ds20MCOnlyNotifyActive(),
                        'authorize_on_error' => $this->getVoucherConfig()->isAuth3Ds20AuthorizedOnError(),
                        'authorize_on_failure' => $this->getVoucherConfig()->isAuth3Ds20AuthorizedOnFailure(),
                        'authorize_on_unenrolled' => $this->getVoucherConfig()->isAuth3Ds20AuthorizeOnUnenrolled(),
                        'mdd1' => $this->getVoucherConfig()->getAuth3Ds20Mdd1(),
                        'mdd2' => $this->getVoucherConfig()->getAuth3Ds20Mdd2(),
                        'mdd3' => $this->getVoucherConfig()->getAuth3Ds20Mdd3(),
                        'mdd4' => $this->getVoucherConfig()->getAuth3Ds20Mdd4(),
                        'mdd5' => $this->getVoucherConfig()->getAuth3Ds20Mdd5()
                    ],
                    'card_view' => [
                        'active' => $this->getVoucherConfig()->isCardViewActive()
                    ]
                ],
                'redirect_after_place_order' => $this->getVoucherConfig()->getRedirectAfterPlaceOrder()
            ]
        ];

        return $config;
    }

    protected function getVoucherConfig()
    {
        return $this->voucherConfig;
    }

    protected function setVoucherConfig($voucherConfig)
    {
        $this->voucherConfig = $voucherConfig;

        return $this;
    }
}
