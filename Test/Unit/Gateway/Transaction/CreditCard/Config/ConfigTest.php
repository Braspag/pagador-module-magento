<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Config;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\Config;

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
    	    ->with('payment/braspag_pagador_billet/merchant_id')
    	    ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

    	$this->scopeConfig->expects($this->at(1))
    	    ->method('getValue')
    	    ->with('payment/braspag_pagador_billet/merchant_key')
    	    ->will($this->returnValue('0123456789012345678901234567890123456789'));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
    }
}
