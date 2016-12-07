<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\SilentOrderPostConfig;

/**
 * 
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class SilentOrderPostConfigTest extends \PHPUnit_Framework_TestCase
{
    private $config;

    private $scopeConfig;

    public function setUp()
    {
        $this->scopeConfigMock = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');

        $this->code = 'payment_method_custom';

        $this->config = new SilentOrderPostConfig(
            $this->scopeConfigMock,
            $this->code
        );
    }

    public function tearDown()
    {

    }

    public function testIsActive() 
    {
        $this->scopeConfigMock->expects($this->at(0))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_active')
            ->will($this->returnValue(1));

        $this->scopeConfigMock->expects($this->at(1))
            ->method('getValue')
            ->with('payment/braspag_pagador_global/test_mode')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(2))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_url_homolog')
            ->will($this->returnValue('http://teste.com/'));

        static::assertTrue($this->config->isActive());
        static::assertEquals('http://teste.com/', $this->config->getUrl());
    }

    public function testIsActiveWithSandboxDisabled() 
    {
        $this->scopeConfigMock->expects($this->at(0))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_active')
            ->will($this->returnValue(1));

        $this->scopeConfigMock->expects($this->at(1))
            ->method('getValue')
            ->with('payment/braspag_pagador_global/test_mode')
            ->will($this->returnValue(false));

        $this->scopeConfigMock->expects($this->at(2))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_url_production')
            ->will($this->returnValue('http://teste.com/'));

        static::assertTrue($this->config->isActive());
        static::assertEquals('http://teste.com/', $this->config->getUrl());
    }
}