<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\BuilderInterface  as InstallmentsBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\InstallmentInterface;
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
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_creditcard';

    protected $installments = [];

    protected $installmentsBuilder;

    protected $silentorderPostBuilder;

    public function __construct(
        InstallmentsBuilder $installmentsBuilder,
        SilentOrderPOstBuilder $silentorderPostBuilder
    ) {
        $this->setInstallmentsBuilder($installmentsBuilder);
        $this->setSilentorderPostBuilder($silentorderPostBuilder);
    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'ccform' => [
                    'installments' => [
                        'active' => [self::CODE => true],
                        'list' => $this->getInstallments(),
                    ],
                ]
            ]
        ];

        if ($silentorderPostAccessToken = $this->getSilentorderPostBuilder()->build()) {
            $config['payment']['ccform']['silentorderpost']['accesstoken'][self::CODE] = $silentorderPostAccessToken;
        }

        return $config;
    }

    public function getSilentOrderPostAccessToken()
    {
        
    }

    protected function getInstallments()
    {
        foreach ($this->getInstallmentsBuilder()->build() as $installment) {
            $this->installments[self::CODE][$installment->getId()] = $installment->getLabel();
        }

    	return $this->installments;
    }

    protected function getInstallmentsBuilder()
    {
        return $this->installmentsBuilder;
    }

    protected function setInstallmentsBuilder($installmentsBuilder)
    {
        $this->installmentsBuilder = $installmentsBuilder;

        return $this;
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
