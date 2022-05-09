<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Config;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface;
use Magento\Framework\App\State;

class ConfigTest extends \PHPUnit\Framework\TestCase
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
            ->with(ConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_ID)
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));


        $this->scopeConfigMock->expects($this->at(1))
            ->method('getValue')
            ->with(ConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_KEY)
            ->will($this->returnValue('0123456789012345678901234567890123456789'));


        $this->contextMock->expects($this->exactly(2))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
    }
}
