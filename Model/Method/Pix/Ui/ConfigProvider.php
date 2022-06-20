<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */
namespace Braspag\BraspagPagador\Model\Method\Pix\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Braspag\BraspagPagador\Model\Method\Pix\Config\Config;

final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_pix';

    protected $pixConfig;
    public function __construct(Config $billetConfig)
    {
        $this->setPixConfig($billetConfig);
    }
    public function getConfig()
    {
        $config = [
            'braspagpagador' => [
                self::CODE => [
                    'info' => [
                        'demonstrative' => $this->getPixConfig()->getPixPaymentDemonstrative(),
                    ]
                ]
            ]
        ];

        return $config;
    }
    /**
     * @return Config
     */
    protected function getPixConfig()
    {
        return $this->pixConfig;
    }
    protected function setPixConfig(Config $pixConfig)
    {
        $this->pixConfig = $pixConfig;
        return $this;
    }
}