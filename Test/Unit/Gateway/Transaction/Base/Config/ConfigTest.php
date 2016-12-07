<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
	protected $config;

	protected $scopeConfigMock;

    public function setUp()
    {
    	$this->scopeConfigMock = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');

    	$this->config = new Config(
    		$this->scopeConfigMock
    	);
    }

    public function tearDown()
    {

    }

    public function testgetData()
    {
        $this->scopeConfigMock->expects($this->at(0))
            ->method('getValue')
            ->with('payment/braspag_pagador_global/merchant_id')
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $this->scopeConfigMock->expects($this->at(1))
            ->method('getValue')
            ->with('payment/braspag_pagador_global/merchant_key')
            ->will($this->returnValue('0123456789012345678901234567890123456789'));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
    }
}
