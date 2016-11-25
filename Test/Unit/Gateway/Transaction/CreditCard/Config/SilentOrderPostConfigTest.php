<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Config;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\SilentOrderPostConfig;

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
        $this->scopeConfig = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');

        $this->config = new SilentOrderPostConfig(
            'braspag_pagador_creditcard',
            $this->scopeConfig            
        );
    }

    public function tearDown()
    {

    }

    public function testIsActive() 
    {
        $this->scopeConfig->expects($this->at(0))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/silentorderpost_active')
            ->will($this->returnValue(1));

        $this->scopeConfig->expects($this->at(1))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/silentorderpost_is_sandbox_mode')
            ->will($this->returnValue(1));

        $this->scopeConfig->expects($this->at(2))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/silentorderpost_url_homolog')
            ->will($this->returnValue('http://teste.com/'));

        static::assertTrue($this->config->isActive());
        static::assertEquals('http://teste.com/', $this->config->getUrl());
    }

    public function testIsActiveWithSandboxDisabled() 
    {
        $this->scopeConfig->expects($this->at(0))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/silentorderpost_active')
            ->will($this->returnValue(1));

        $this->scopeConfig->expects($this->at(1))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/silentorderpost_is_sandbox_mode')
            ->will($this->returnValue(0));

        $this->scopeConfig->expects($this->at(2))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/silentorderpost_url_production')
            ->will($this->returnValue('http://teste.com/'));

        static::assertTrue($this->config->isActive());
        static::assertEquals('http://teste.com/', $this->config->getUrl());
    }
}