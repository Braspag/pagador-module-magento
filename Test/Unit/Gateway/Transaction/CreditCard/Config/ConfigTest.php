<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Config;

use Magento\Store\Model\StoreManagerInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\Config;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ContextInterface;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    private $config;
    private $contextMock;
    private $scopeConfigMock;
    private $storeManagerMock;
    private $storeMock;

    public function setUp()
    {
        $this->scopeConfigMock = $this->createMock('Magento\Framework\App\Config\ScopeConfigInterface');
        $this->storeManagerMock = $this->createMock(StoreManagerInterface::class);
        $this->storeMock = $this->getMockBuilder(\Magento\Store\Model\StoreManager::class)
            ->setMethods(['getUrl'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock = $this->createMock(ContextInterface::class);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->config = $objectManager->getObject(Config::class, [
            'context' => $this->contextMock
        ]);
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
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(7))
            ->method('getValue')
            ->with('webjump_braspag/pagador/return_url')
            ->will($this->returnValue('checkout/onepage/success'));

        $this->scopeConfigMock->expects($this->at(8))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/customer_identity_attribute_code')
            ->will($this->returnValue('customer_taxvat'));

        $this->scopeConfigMock->expects($this->at(9))
            ->method('getValue')
            ->with('payment/braspag_pagador_customer_address/street_attribute')
            ->will($this->returnValue('street_1'));

        $this->scopeConfigMock->expects($this->at(10))
            ->method('getValue')
            ->with('payment/braspag_pagador_customer_address/number_attribute')
            ->will($this->returnValue('street_2'));

        $this->scopeConfigMock->expects($this->at(11))
            ->method('getValue')
            ->with('payment/braspag_pagador_customer_address/complement_attribute')
            ->will($this->returnValue('street_3'));

        $this->scopeConfigMock->expects($this->at(12))
            ->method('getValue')
            ->with('payment/braspag_pagador_customer_address/district_attribute')
            ->will($this->returnValue('street_4'));

        $this->contextMock->expects($this->exactly(13))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
        static::assertTrue($this->config->isAuthorizeAndCapture());
        static::assertEquals('Texto que será impresso na fatura do portador', $this->config->getSoftDescriptor());
        static::assertTrue($this->config->hasAntiFraud());
        static::assertTrue($this->config->hasAvs());
        static::assertTrue($this->config->isAuth3Ds20Active());
        static::assertEquals('checkout/onepage/success', $this->config->getReturnUrl());
        static::assertEquals('customer_taxvat', $this->config->getIdentityAttributeCode());
        static::assertEquals('street_1', $this->config->getCustomerStreetAttribute());
        static::assertEquals('street_2', $this->config->getCustomerNumberAttribute());
        static::assertEquals('street_3', $this->config->getCustomerComplementAttribute());
        static::assertEquals('street_4', $this->config->getCustomerDistrictAttribute());
    }
}
