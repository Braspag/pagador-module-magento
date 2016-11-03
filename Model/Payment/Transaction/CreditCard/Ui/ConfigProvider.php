<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Installments\BuilderInterface;
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

    protected $builder;

    public function __construct(
        BuilderInterface $builder
    ) {
        $this->setBuilder($builder);
    }

    public function getConfig()
    {
        return [
            'payment' => [
                'ccform' => [
                    'installments' => $this->getInstallments(),
                ]
            ]
        ];
    }

    protected function getInstallments()
    {
        foreach ($this->getBuilder()->build() as $installment) {
            $this->addInstallment($installment);
        }

    	return $this->installments;
    }

    protected function addInstallment(InstallmentInterface $installment)
    {
        $this->installments[self::CODE][$installment->getId()] = $installment->getLabel();

        return $this;
    }

    protected function getBuilder()
    {
        return $this->builder;
    }

    protected function setBuilder(BuilderInterface $builder)
    {
        $this->builder = $builder;

        return $this;
    }
}
