<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Config;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
	protected $config;

	protected $scopeConfig;

    public function setUp()
    {
    	$this->scopeConfig = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');

    	$this->config = new Config(
    		$this->scopeConfig
    	);
    }

    public function tearDown()
    {

    }

    public function testgetData()
    {
    	$this->scopeConfig->expects($this->at(0))
    	    ->method('getValue')
    	    ->with('payment/braspag_pagador_global/merchant_id')
    	    ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

    	$this->scopeConfig->expects($this->at(1))
    	    ->method('getValue')
    	    ->with('payment/braspag_pagador_global/merchant_key')
    	    ->will($this->returnValue('0123456789012345678901234567890123456789'));

        $this->scopeConfig->expects($this->at(2))
            ->method('getValue')
            ->with('payment/braspag_pagador_debit/return_url')
            ->will($this->returnValue('http://test.com'));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
        static::assertEquals('http://test.com', $this->config->getPaymentReturnUrl());
    }
}
