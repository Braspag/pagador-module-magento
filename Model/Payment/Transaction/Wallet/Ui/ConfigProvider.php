<?php

namespace Braspag\BraspagPagador\Model\Payment\Transaction\Wallet\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Wallet\Config\ConfigInterface;

/**
 * Braspag Transaction Wallet Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_wallet';

    protected $walletConfig;

    public function __construct(
        ConfigInterface $walletConfig
    ) {
        $this->setWalletConfig($walletConfig);
    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'wcform' => [
                    'card_view' => [
                        'active' => $this->getWalletConfig()->isCardViewActive()
                    ]
                ],
                'redirect_after_place_order' => $this->getWalletConfig()->getRedirectAfterPlaceOrder()
            ]
        ];

        return $config;
    }

    protected function getWalletConfig()
    {
        return $this->walletConfig;
    }

    protected function setWalletConfig($walletConfig)
    {
        $this->walletConfig = $walletConfig;

        return $this;
    }
}