<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Billet\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\Config;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
	private $config;

	private $scopeConfigMock;
    
    private $dateTimeMock;

    public function setUp()
    {
    	$this->scopeConfigMock = $this->getMock(ScopeConfigInterface::class);
        $this->dateTimeMock = $this->getMockBuilder(DateTime::class)
            ->getMock();

    	$this->config = new Config(
    		$this->scopeConfigMock,
            $this->dateTimeMock
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

        $this->scopeConfigMock->expects($this->at(2))
            ->method('getValue')
            ->with('payment/braspag_pagador_billet/demonstrative')
            ->will($this->returnValue('demonstrative'));

        $this->scopeConfigMock->expects($this->at(3))
            ->method('getValue')
            ->with('payment/braspag_pagador_billet/instructions')
            ->will($this->returnValue('instructions'));

        $this->scopeConfigMock->expects($this->at(4))
            ->method('getValue')
            ->with('payment/braspag_pagador_billet/assignor')
            ->will($this->returnValue('assignor'));

        $this->scopeConfigMock->expects($this->at(5))
            ->method('getValue')
            ->with('payment/braspag_pagador_billet/expiration_days')
            ->will($this->returnValue('expiration date'));

        $this->scopeConfigMock->expects($this->at(6))
            ->method('getValue')
            ->with('payment/braspag_pagador_billet/provider')
            ->will($this->returnValue('payment provider'));

        $this->dateTimeMock->expects($this->once())
            ->method('gmDate')
            ->will($this->returnValue('2016-10-114'));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
        static::assertEquals('demonstrative', $this->config->getPaymentDemonstrative());
        static::assertEquals('instructions', $this->config->getPaymentInstructions());
        static::assertEquals('assignor', $this->config->getPaymentAssignor());
        static::assertEquals('2016-10-114', $this->config->getExpirationDate());
        static::assertEquals('payment provider', $this->config->getPaymentProvider());
    }
}
