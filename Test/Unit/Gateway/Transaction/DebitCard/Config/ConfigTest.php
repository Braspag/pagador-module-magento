<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Config;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\Config;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    private $config;
    private $contextMock;
    private $scopeConfigMock;

    public function setUp()
    {
        $this->scopeConfigMock = $this->createMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->contextMock = $this->createMock(ContextInterface::class);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->config = $objectManager->getObject(Config::class, [
            'context' => $this->contextMock
        ]);
    }

    public function testGetData()
    {
    	$this->scopeConfigMock->expects($this->at(0))
    	    ->method('getValue')
    	    ->with('webjump_braspag/pagador/merchant_id')
    	    ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

    	$this->scopeConfigMock->expects($this->at(1))
    	    ->method('getValue')
    	    ->with('webjump_braspag/pagador/merchant_key')
    	    ->will($this->returnValue('0123456789012345678901234567890123456789'));

        $this->scopeConfigMock->expects($this->at(2))
            ->method('getValue')
            ->with('webjump_braspag/pagador/return_url')
            ->will($this->returnValue('http://test.com'));

        $this->scopeConfigMock->expects($this->at(3))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/superdebit_active')
            ->will($this->returnValue(true));

        $this->contextMock->expects($this->exactly(4))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
        static::assertEquals('http://test.com', $this->config->getPaymentReturnUrl());
        static::assertTrue($this->config->isSuperDebitoActive());
    }
}

