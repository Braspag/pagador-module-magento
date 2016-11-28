<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Config;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\Config;
use Magento\Framework\Session\SessionManagerInterface;
class ConfigTest extends \PHPUnit_Framework_TestCase
{
	protected $config;

	protected $scopeConfig;
    protected $session;

    public function setUp()
    {
    	$this->scopeConfig = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->session = $this->getMockBuilder(SessionManagerInterface::class)->getMockForAbstractClass();
    	$this->config = new Config(
    		$this->scopeConfig,
            $this->session
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
            ->with('payment/braspag_pagador_creditcard/payment_action')
            ->will($this->returnValue('authorize_capture'));

        $this->scopeConfig->expects($this->at(3))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/soft_config')
            ->will($this->returnValue('Texto que será impresso na fatura do portador'));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
        static::assertTrue($this->config->isAuthorizeAndCapture());
        static::assertEquals('Texto que será impresso na fatura do portador', $this->config->getSoftDescriptor());
    }
}
