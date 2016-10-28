<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\Billet\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface as BilletConfig;

/**
 * Braspag Transaction Billet Send Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_billet';

    protected $billetConfig;

    public function __construct(
    	BilletConfig $billetConfig
    ) {
    	$this->setBilletConfig($billetConfig);
    }

    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'info' => [
                        'demonstrative' => $this->getBilletConfig()->getPaymentDemonstrative(),
                    ]
                ]
            ]
        ];
    }

    
    protected function getBilletConfig()
    {
        return $this->billetConfig;
    }

    protected function setBilletConfig(BilletConfig $billetConfig)
    {
        $this->billetConfig = $billetConfig;

        return $this;
    }
}
