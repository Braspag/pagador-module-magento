<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\SilentOrderPostConfig;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;
use Magento\Framework\App\State;

/**
 * 
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class SilentOrderPostConfigTest extends \PHPUnit\Framework\TestCase
{
    private $config;
    private $contextMock;
    private $scopeConfigMock;
    private $stateMock;

    public function setUp()
    {
        $this->scopeConfigMock = $this->createMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->contextMock = $this->createMock(ContextInterface::class);
        $this->stateMock = $this->createMock(State::class);

        $this->config = new SilentOrderPostConfig(
            $this->contextMock,
            $this->contextMock,
            $this->scopeConfigMock,
            $this->stateMock,
            [
                'code' => 'payment_method_custom'
            ]
        );
    }

    public function testIsActive() 
    {
        $this->scopeConfigMock->expects($this->at(0))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_active')
            ->will($this->returnValue(1));

        $this->scopeConfigMock->expects($this->at(1))
            ->method('getValue')
            ->with('webjump_braspag/pagador/test_mode')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(2))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_paymenttoken_url_homolog')
            ->will($this->returnValue('http://teste.com/'));

        $this->scopeConfigMock->expects($this->at(3))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_accesstoken_url_production')
            ->will($this->returnValue('http://production.com='));

        $this->scopeConfigMock->expects($this->at(4))
            ->method('getValue')
            ->with('webjump_braspag/pagador/test_mode')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(5))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_accesstoken_url_homolog')
            ->will($this->returnValue('http://homolog.com='));

        $this->scopeConfigMock->expects($this->at(6))
            ->method('getValue')
            ->with('webjump_braspag/pagador/merchant_id')
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $this->contextMock->expects($this->exactly(7))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        static::assertTrue($this->config->isActive());
        static::assertEquals('http://teste.com/', $this->config->getPaymentTokenUrl());
        static::assertEquals('http://homolog.com=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getAccessTokenUrl());
    }

    public function testIsActiveWithSandboxDisabled() 
    {
        $this->scopeConfigMock->expects($this->at(0))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_active')
            ->will($this->returnValue(1));

        $this->scopeConfigMock->expects($this->at(1))
            ->method('getValue')
            ->with('webjump_braspag/pagador/test_mode')
            ->will($this->returnValue(false));

        $this->scopeConfigMock->expects($this->at(2))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_paymenttoken_url_production')
            ->will($this->returnValue('http://teste.com/'));

        $this->scopeConfigMock->expects($this->at(3))
            ->method('getValue')
            ->with('payment/payment_method_custom/silentorderpost_accesstoken_url_production')
            ->will($this->returnValue('http://production.com='));

        $this->scopeConfigMock->expects($this->at(4))
            ->method('getValue')
            ->with('webjump_braspag/pagador/test_mode')
            ->will($this->returnValue(false));

        $this->scopeConfigMock->expects($this->at(5))
            ->method('getValue')
            ->with('webjump_braspag/pagador/merchant_id')
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $this->contextMock->expects($this->exactly(6))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        static::assertTrue($this->config->isActive());
        static::assertEquals('http://teste.com/', $this->config->getPaymentTokenUrl());
        static::assertEquals('http://production.com=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getAccessTokenUrl());
    }
}
