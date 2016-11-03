<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Config;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\InstallmentsConfig;

class InstallmentsConfigTest extends \PHPUnit_Framework_TestCase
{
	protected $config;

	protected $scopeConfig;

    public function setUp()
    {
    	$this->scopeConfig = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');

    	$this->config = new InstallmentsConfig(
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
    	    ->with('payment/braspag_pagador_creditcard/installments_number')
    	    ->will($this->returnValue(10));

    	$this->scopeConfig->expects($this->at(1))
    	    ->method('getValue')
    	    ->with('payment/braspag_pagador_creditcard/installments_is_with_interest')
    	    ->will($this->returnValue(1));

    	$this->scopeConfig->expects($this->at(2))
    	    ->method('getValue')
    	    ->with('payment/braspag_pagador_creditcard/installment_min_amount')
    	    ->will($this->returnValue(30.00));

    	$this->scopeConfig->expects($this->at(3))
    	    ->method('getValue')
    	    ->with('payment/braspag_pagador_creditcard/installments_interest_rate')
    	    ->will($this->returnValue(20));

    	$this->scopeConfig->expects($this->at(4))
    	    ->method('getValue')
    	    ->with('payment/braspag_pagador_creditcard/installments_interest_by_issuer')
    	    ->will($this->returnValue(1));

    	$this->scopeConfig->expects($this->at(5))
    	    ->method('getValue')
    	    ->with('payment/braspag_pagador_creditcard/installments_max_without_interest')
    	    ->will($this->returnValue(5));

		static::assertEquals(10, $this->config->getInstallmentsNumber());
		static::assertTrue($this->config->isWithInterest());
		static::assertEquals(30.00, $this->config->getInstallmentMinAmount());
		static::assertEquals(0.2, $this->config->getInterestRate());
    	static::assertTrue($this->config->isInterestByIssuer());
		static::assertEquals(5, $this->config->getinstallmentsMaxWithoutInterest());
    }
}
