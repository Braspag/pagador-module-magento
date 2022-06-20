<?php

namespace Braspag\BraspagPagador\Model\Payment\Transaction\Pix\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Pix\Config\ConfigInterface as PixConfig;

/**
 * Braspag Transaction Pix Send Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_pix';

    protected $pixConfig;

    public function __construct(
        PixConfig $pixConfig
    ) {
        $this->setPixConfig($pixConfig);
    }

    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'info' => [
                        'demonstrative' => $this->getPixConfig()->getPaymentDemonstrative(),
                    ]
                ]
            ]
        ];
    }


    protected function getPixConfig()
    {
        return $this->pixConfig;
    }

    protected function setPixConfig(PixConfig $pixConfig)
    {
        $this->pixConfig = $pixConfig;

        return $this;
    }

        /**
     * Get logo url from config
     *
     * @param string $code
     *
     * @return string
     */
    protected function getLogo($code)
    {
        return nl2br($this->escaper->escapeHtml($this->method->getLogo()));
    }
}