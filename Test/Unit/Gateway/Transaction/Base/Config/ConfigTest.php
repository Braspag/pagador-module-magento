<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Config;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
	private $config;
    private $contextMock;
    private $scopeConfigMock;

    public function setUp()
    {
    	$this->scopeConfigMock = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');
    	$this->contextMock = $this->getMock(ContextInterface::class);

        $this->config = new Config(
            $this->contextMock
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
