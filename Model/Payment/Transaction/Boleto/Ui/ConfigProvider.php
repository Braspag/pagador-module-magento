<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\Boleto\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as BoletoConfig;

/**
 * Braspag Transaction Boleto Send Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_boleto';

    protected $boletoConfig;

    public function __construct(
    	BoletoConfig $boletoConfig
    ) {
    	$this->setBoletoConfig($boletoConfig);
    }

    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'info' => [
                        'demonstrative' => $this->getBoletoConfig()->getPaymentDemonstrative(),
                    ]
                ]
            ]
        ];
    }

    
    protected function getBoletoConfig()
    {
        return $this->boletoConfig;
    }

    protected function setBoletoConfig(BoletoConfig $boletoConfig)
    {
        $this->boletoConfig = $boletoConfig;

        return $this;
    }
}
