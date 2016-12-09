<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Config;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\Config;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $config;
    private $contextMock;
    private $scopeConfigMock;

    public function setUp()
    {
        $this->scopeConfigMock = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->contextMock = $this->getMock(ContextInterface::class);

        $this->config = new Config(
            $this->contextMock
        );
    }

    public function testGetData()
    {
    	$this->scopeConfigMock->expects($this->at(0))
    	    ->method('getValue')
    	    ->with('payment/braspag_pagador_global/merchant_id')
    	    ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

    	$this->scopeConfigMock->expects($this->at(1))
    	    ->method('getValue')
    	    ->with('payment/braspag_pagador_global/merchant_key')
    	    ->will($this->returnValue('0123456789012345678901234567890123456789'));

        $this->scopeConfigMock->expects($this->at(2))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/return_url')
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

