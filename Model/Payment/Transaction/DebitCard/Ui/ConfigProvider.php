<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface;

/**
 * Braspag Transaction DebitCard Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_debitcard';

    protected $debitConfig;

    public function __construct(
        ConfigInterface $debitConfig
    ) {
        $this->setDebitConfig($debitConfig);
    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'dcform' => [
                    'superdebito' => [
                        'active' => [self::CODE => $this->getDebitConfig()->isSuperDebitoActive()]
                    ],
                ],
                'redirect_after_place_order' => $this->getDebitConfig()->getRedirectAfterPlaceOrder()
            ]
        ];

        return $config;
    }

    protected function getDebitConfig()
    {
        return $this->debitConfig;
    }

    protected function setDebitConfig($debitConfig)
    {
        $this->debitConfig = $debitConfig;

        return $this;
    }
}
