<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Config;

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

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
    }
}
