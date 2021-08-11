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

        $this->scopeConfigMock->expects($this->at(13))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/paymentsplit')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(14))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/paymentsplit_type')
            ->will($this->returnValue('paymentsplit_type'));

        $this->scopeConfigMock->expects($this->at(15))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/paymentsplit_transactional_post_send_request_automatically')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(16))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/paymentsplit_transactional_post_send_request_automatically_after_x_hours')
            ->will($this->returnValue('paymentsplit_transactional_post_send_request_automatically_after_x_hours'));

        $this->scopeConfigMock->expects($this->at(17))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/paymentsplit_mdr')
            ->will($this->returnValue('paymentsplit_mdr'));

        $this->scopeConfigMock->expects($this->at(18))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/paymentsplit_fee')
            ->will($this->returnValue('paymentsplit_fee'));

        $this->scopeConfigMock->expects($this->at(19))
            ->method('getValue')
            ->with('webjump_braspag_antifraud/general/active')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(20))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcardtoken/active')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(21))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/create_invoice_on_notification_captured')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(22))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/decimal_grand_total')
            ->will($this->returnValue(2));

        $this->scopeConfigMock->expects($this->at(23))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20_mastercard_notify_only')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(24))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20_authorize_on_error')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(25))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20_authorize_on_failure')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(26))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20_authorize_on_unenrolled')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(27))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20_authorize_on_unsupported_brand')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(28))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20_mdd1')
            ->will($this->returnValue('mdd1'));

        $this->scopeConfigMock->expects($this->at(29))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20_mdd2')
            ->will($this->returnValue('mdd2'));

        $this->scopeConfigMock->expects($this->at(30))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20_mdd3')
            ->will($this->returnValue('mdd3'));

        $this->scopeConfigMock->expects($this->at(31))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20_mdd4')
            ->will($this->returnValue('mdd4'));

        $this->scopeConfigMock->expects($this->at(32))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/authentication_3ds_20_mdd5')
            ->will($this->returnValue('mdd5'));

        $this->scopeConfigMock->expects($this->at(33))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/card_view')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(34))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/cctypes')
            ->will($this->returnValue([]));

        $this->contextMock->expects($this->exactly(35))
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
        static::assertTrue($this->config->isPaymentSplitActive());
        static::assertEquals('paymentsplit_type', $this->config->getPaymentSplitType());
        static::assertTrue($this->config->getPaymentSplitTransactionalPostSendRequestAutomatically());
        static::assertEquals('paymentsplit_transactional_post_send_request_automatically_after_x_hours', $this->config->getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours());
        static::assertEquals('paymentsplit_mdr', $this->config->getPaymentSplitDefaultMrd());
        static::assertEquals('paymentsplit_fee', $this->config->getPaymentSplitDefaultFee());
        static::assertTrue($this->config->hasAntiFraud());
        static::assertTrue($this->config->isSaveCardActive());
        static::assertTrue($this->config->isCreateInvoiceOnNotificationCaptured());
        static::assertEquals(2, $this->config->getDecimalGrandTotal());
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
        static::assertTrue($this->config->isCardViewActive());
        static::assertEquals([], $this->config->getCcTypes());
    }

    public function testGetDecimalGrandTotalShouldReturnDefaultDecimalWhenConfigIsEmpty()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/decimal_grand_total')
            ->will($this->returnValue(null));

        $this->contextMock->expects($this->once())
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        static::assertEquals(2, $this->config->getDecimalGrandTotal());
    }
}
