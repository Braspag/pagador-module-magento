<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\SilentOrderPost\BuilderInterface as SilentOrderPOstBuilder;
/**
 * Braspag Transaction CreditCard Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class SilentOrderPostConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_creditcard';

    protected $silentorderPostBuilder;

    public function __construct(
        SilentOrderPOstBuilder $silentorderPostBuilder
    ) {
        $this->setSilentorderPostBuilder($silentorderPostBuilder);
    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'ccform' => [
                    'silentorderpost' => [
                        'accesstoken' => [self::CODE => $this->getSilentorderPostBuilder()->build()],
                        'requesttimeout' => [self::CODE => 5000],
                        'environment' => [self::CODE => 'sandbox'],
                        'language' => [self::CODE => 'PT'],
                    ],
                ]
            ]
        ];

        return $config;
    }

    protected function getSilentorderPostBuilder()
    {
        return $this->silentorderPostBuilder;
    }

    protected function setSilentorderPostBuilder($silentorderPostBuilder)
    {
        $this->silentorderPostBuilder = $silentorderPostBuilder;

        return $this;
    }
}
