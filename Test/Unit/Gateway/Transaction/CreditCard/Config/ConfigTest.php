<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Config;

use Magento\Store\Model\StoreManagerInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\Config;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $config;
    private $contextMock;
    private $scopeConfigMock;
    private $storeManagerMock;
    private $storeMock;

    public function setUp()
    {
        $this->scopeConfigMock = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->storeManagerMock = $this->getMock(StoreManagerInterface::class);
        $this->storeMock = $this->getMockBuilder(\Magento\Store\Model\StoreManager::class)
            ->setMethods(['getUrl'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock = $this->getMock(ContextInterface::class);

        $this->config = new Config(
            $this->contextMock
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
            ->with('payment/braspag_pagador_creditcard/payment_action')
            ->will($this->returnValue('authorize_capture'));

        $this->scopeConfigMock->expects($this->at(3))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/soft_config')
            ->will($this->returnValue('Texto que será impresso na fatura do portador'));

        $this->scopeConfigMock->expects($this->at(4))
            ->method('getValue')
            ->with('webjump_braspag_antifraud/general/active')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(5))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/avs_active')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(6))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authenticate_3ds_vbv')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(7))
            ->method('getValue')
            ->with('payment/braspag_pagador_config/return_url')
            ->will($this->returnValue('checkout/onepage/success'));

        $this->scopeConfigMock->expects($this->at(8))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/customer_identity_attribute_code')
            ->will($this->returnValue('customer_taxvat'));

        $this->storeMock->expects($this->once())
            ->method('getUrl')
            ->with('checkout/onepage/success')
            ->will($this->returnValue('http://www.braspagreturnurl.com.br/index.php/checkout/onepage/success/'));

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->will($this->returnValue($this->storeMock));

        $this->contextMock->expects($this->exactly(9))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        $this->contextMock->expects($this->once())
            ->method('getStoreManager')
            ->will($this->returnValue($this->storeManagerMock));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
        static::assertTrue($this->config->isAuthorizeAndCapture());
        static::assertEquals('Texto que será impresso na fatura do portador', $this->config->getSoftDescriptor());
        static::assertTrue($this->config->hasAntiFraud());
        static::assertTrue($this->config->hasAvs());
        static::assertTrue($this->config->isAuthenticate3DsVbv());
        static::assertEquals('http://www.braspagreturnurl.com.br/checkout/onepage/success', $this->config->getReturnUrl());
        static::assertEquals('customer_taxvat', $this->config->getIdentityAttributeCode());
    }
}
