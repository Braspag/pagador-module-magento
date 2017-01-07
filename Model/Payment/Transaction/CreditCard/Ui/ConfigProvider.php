<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as CreditCardConfig;

/**
 * Braspag Transaction CreditCard Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_creditcard';

    protected $creditCardConfig;

    public function __construct(
        CreditCardConfig $creditCardConfig
    ){
        $this->setCreditCardConfig($creditCardConfig);
    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'ccform' => [
                    'savecard' => [
                        'active' => [self::CODE => $this->getCreditCardConfig()->isSaveCardActive()]
                    ],
                    'authenticate' => [
                        'active' => [self::CODE => $this->getCreditCardConfig()->isAuthenticate3DsVbv()]
                    ],
                ]
            ]
        ];

        return $config;
    }

    public function getCreditCardConfig()
    {
        return $this->creditCardConfig;
    }

    protected function setCreditCardConfig($creditCardConfig)
    {
        $this->creditCardConfig = $creditCardConfig;

        return $this;
    }
}
