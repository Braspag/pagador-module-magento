<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Config;

use Magento\Store\Model\StoreManagerInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\Config;
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
            ->with('webjump_braspag/pagador/return_url')
            ->will($this->returnValue('http://return.url'));

        $this->scopeConfigMock->expects($this->at(3))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/superdebit_active')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(4))
            ->method('getValue')
            ->with('webjump_braspag/pagador/test_mode')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(5))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/cctypes')
            ->will($this->returnValue([]));

        $this->scopeConfigMock->expects($this->at(6))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/redirect_after_place_order')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(7))
            ->method('getValue')
            ->with('webjump_braspag_antifraud/general/active')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(8))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(9))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20_mastercard_notify_only')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(10))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20_authorize_on_error')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(11))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20_authorize_on_failure')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(12))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20_authorize_on_unenrolled')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(13))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20_authorize_on_unsupported_brand')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(14))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20_mdd1')
            ->will($this->returnValue('mdd1'));

        $this->scopeConfigMock->expects($this->at(15))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20_mdd2')
            ->will($this->returnValue('mdd2'));

        $this->scopeConfigMock->expects($this->at(16))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20_mdd3')
            ->will($this->returnValue('mdd3'));

        $this->scopeConfigMock->expects($this->at(17))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20_mdd4')
            ->will($this->returnValue('mdd4'));

        $this->scopeConfigMock->expects($this->at(18))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/authentication_3ds_20_mdd5')
            ->will($this->returnValue('mdd5'));

        $this->scopeConfigMock->expects($this->at(19))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/paymentsplit')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(20))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/paymentsplit_type')
            ->will($this->returnValue('paymentsplit_type'));

        $this->scopeConfigMock->expects($this->at(21))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/paymentsplit_transactional_post_send_request_automatically')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(22))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/paymentsplit_transactional_post_send_request_automatically_after_x_hours')
            ->will($this->returnValue('paymentsplit_transactional_post_send_request_automatically_after_x_hours'));

        $this->scopeConfigMock->expects($this->at(23))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/paymentsplit_mdr')
            ->will($this->returnValue('paymentsplit_mdr'));

        $this->scopeConfigMock->expects($this->at(24))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/paymentsplit_fee')
            ->will($this->returnValue('paymentsplit_fee'));

        $this->scopeConfigMock->expects($this->at(25))
            ->method('getValue')
            ->with('payment/braspag_pagador_debitcard/card_view')
            ->will($this->returnValue(true));

        $this->contextMock->expects($this->exactly(26))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
        static::assertEquals('http://return.url', $this->config-> getPaymentReturnUrl());
        static::assertTrue($this->config-> isSuperDebitoActive());
        static::assertTrue($this->config->getIsTestEnvironment());
        static::assertEquals([], $this->config->getDcTypes());
        static::assertTrue($this->config->getRedirectAfterPlaceOrder());
        static::assertTrue($this->config->hasAntiFraud());
        static::assertTrue($this->config->isAuth3Ds20Active());
        static::assertTrue($this->config->isAuth3Ds20MCOnlyNotifyActive());
        static::assertTrue($this->config->isAuth3Ds20AuthorizedOnError());
        static::assertTrue($this->config->isAuth3Ds20AuthorizedOnFailure());
        static::assertTrue($this->config->isAuth3Ds20AuthorizeOnUnenrolled());
        static::assertTrue($this->config->isAuth3Ds20AuthorizeOnUnsupportedBrand());
        static::assertEquals('mdd1', $this->config->getAuth3Ds20Mdd1());
        static::assertEquals('mdd2', $this->config->getAuth3Ds20Mdd2());
        static::assertEquals('mdd3', $this->config->getAuth3Ds20Mdd3());
        static::assertEquals('mdd4', $this->config->getAuth3Ds20Mdd4());
        static::assertEquals('mdd5', $this->config->getAuth3Ds20Mdd5());
        static::assertTrue($this->config->isPaymentSplitActive());
        static::assertEquals('paymentsplit_type', $this->config->getPaymentSplitType());
        static::assertTrue($this->config->getPaymentSplitTransactionalPostSendRequestAutomatically());
        static::assertEquals('paymentsplit_transactional_post_send_request_automatically_after_x_hours', $this->config->getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours());
        static::assertEquals('paymentsplit_mdr', $this->config->getPaymentSplitDefaultMrd());
        static::assertEquals('paymentsplit_fee', $this->config->getPaymentSplitDefaultFee());
        static::assertTrue($this->config->isCardViewActive());
    }
}
