<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\BuilderInterface  as InstallmentsBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\InstallmentInterface;

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
        InstallmentsBuilder $installmentsBuilder
    ) {
        $this->setInstallmentsBuilder($installmentsBuilder);
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
                    'authenticate' => [
                        'active' => [self::CODE => true]
                    ],
                ]
            ]
        ];

        return $config;
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
}
