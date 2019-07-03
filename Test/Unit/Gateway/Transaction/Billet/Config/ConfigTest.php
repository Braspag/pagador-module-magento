<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Billet\Config;


use Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\Config;
use Magento\Framework\Stdlib\DateTime;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;
use Magento\Framework\App\State;


class ConfigTest extends \PHPUnit\Framework\TestCase
{
    private $dateTimeMock;
    private $config;
    private $contextMock;
    private $scopeConfigMock;
    private $stateMock;

    public function setUp()
    {
        $this->scopeConfigMock = $this->createMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->contextMock = $this->createMock(ContextInterface::class);
        $this->stateMock = $this->createMock(State::class);

        $this->dateTimeMock =  $this->getMockBuilder(DateTime::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->config = new Config(
            $this->contextMock,
            $this->contextMock,
            $this->scopeConfigMock,
            $this->stateMock
        );
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
            ->with('payment/braspag_pagador_billet/types')
            ->will($this->returnValue('payment provider'));

        $this->dateTimeMock->expects($this->once())
            ->method('gmDate')
            ->will($this->returnValue('2016-10-114'));

        $this->contextMock->expects($this->exactly(7))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        $this->contextMock->expects($this->once())
            ->method('getDateTime')
            ->will($this->returnValue($this->dateTimeMock));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
        static::assertEquals('demonstrative', $this->config->getPaymentDemonstrative());
        static::assertEquals('instructions', $this->config->getPaymentInstructions());
        static::assertEquals('assignor', $this->config->getPaymentAssignor());
        static::assertEquals('2016-10-114', $this->config->getExpirationDate());
        static::assertEquals('payment provider', $this->config->getPaymentProvider());
    }
}
