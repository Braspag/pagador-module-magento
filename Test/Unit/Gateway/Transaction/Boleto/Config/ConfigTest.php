<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Boleto\Config;


use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\Config;
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
            ->with('payment/braspag_pagador_boleto/demonstrative')
            ->will($this->returnValue('demonstrative'));

        $this->scopeConfigMock->expects($this->at(3))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/instructions')
            ->will($this->returnValue('instructions'));

        $this->scopeConfigMock->expects($this->at(4))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/identification')
            ->will($this->returnValue('12345678912'));

        $this->scopeConfigMock->expects($this->at(5))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/assignor')
            ->will($this->returnValue('assignor'));

        $this->scopeConfigMock->expects($this->at(6))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/assignor_address')
            ->will($this->returnValue('assignor_address'));

        $this->scopeConfigMock->expects($this->at(7))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/expiration_days')
            ->will($this->returnValue('expiration date'));

        $this->scopeConfigMock->expects($this->at(8))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/types')
            ->will($this->returnValue('payment provider'));

        $this->dateTimeMock->expects($this->once())
            ->method('gmDate')
            ->will($this->returnValue('2016-10-114'));

        $this->contextMock->expects($this->once())
            ->method('getDateTime')
            ->will($this->returnValue($this->dateTimeMock));

        $this->scopeConfigMock->expects($this->at(9))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/bank')
            ->will($this->returnValue('payment bank'));

        $this->scopeConfigMock->expects($this->at(10))
            ->method('getValue')
            ->with('payment/braspag_pagador_customer_address/street_attribute')
            ->will($this->returnValue('street_attribute'));

        $this->scopeConfigMock->expects($this->at(11))
            ->method('getValue')
            ->with('payment/braspag_pagador_customer_address/number_attribute')
            ->will($this->returnValue('number_attribute'));

        $this->scopeConfigMock->expects($this->at(12))
            ->method('getValue')
            ->with('payment/braspag_pagador_customer_address/complement_attribute')
            ->will($this->returnValue('complement_attribute'));

        $this->scopeConfigMock->expects($this->at(13))
            ->method('getValue')
            ->with('payment/braspag_pagador_customer_address/district_attribute')
            ->will($this->returnValue('district_attribute'));

        $this->scopeConfigMock->expects($this->at(14))
            ->method('getValue')
            ->with('payment/braspag_pagador_creditcard/customer_identity_attribute_code')
            ->will($this->returnValue('customer_identity_attribute_code'));

        $this->scopeConfigMock->expects($this->at(15))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/paymentsplit')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(16))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/paymentsplit_type')
            ->will($this->returnValue('paymentsplit_type'));

        $this->scopeConfigMock->expects($this->at(17))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/paymentsplit_transactional_post_send_request_automatically')
            ->will($this->returnValue(true));

        $this->scopeConfigMock->expects($this->at(18))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/paymentsplit_transactional_post_send_request_automatically_after_x_hours')
            ->will($this->returnValue('paymentsplit_transactional_post_send_request_automatically_after_x_hours'));

        $this->scopeConfigMock->expects($this->at(19))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/paymentsplit_mdr')
            ->will($this->returnValue('paymentsplit_mdr'));

        $this->scopeConfigMock->expects($this->at(20))
            ->method('getValue')
            ->with('payment/braspag_pagador_boleto/paymentsplit_fee')
            ->will($this->returnValue('paymentsplit_fee'));

        $this->scopeConfigMock->expects($this->at(21))
            ->method('getValue')
            ->with('webjump_braspag_antifraud/general/active')
            ->will($this->returnValue(true));

        $this->contextMock->expects($this->exactly(22))
            ->method('getConfig')
            ->will($this->returnValue($this->scopeConfigMock));

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->config->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->config->getMerchantKey());
        static::assertEquals('demonstrative', $this->config->getPaymentDemonstrative());
        static::assertEquals('instructions', $this->config->getPaymentInstructions());
        static::assertEquals('12345678912', $this->config->getPaymentIdentification());
        static::assertEquals('assignor', $this->config->getPaymentAssignor());
        static::assertEquals('assignor_address', $this->config->getPaymentAssignorAddress());
        static::assertEquals('2016-10-114', $this->config->getExpirationDate());
        static::assertEquals('payment provider', $this->config->getPaymentProvider());
        static::assertEquals('payment bank', $this->config->getPaymentBank());
        static::assertEquals('street_attribute', $this->config->getCustomerStreetAttribute());
        static::assertEquals('number_attribute', $this->config->getCustomerNumberAttribute());
        static::assertEquals('complement_attribute', $this->config->getCustomerComplementAttribute());
        static::assertEquals('district_attribute', $this->config->getCustomerDistrictAttribute());
        static::assertEquals('customer_identity_attribute_code', $this->config->getIdentityAttributeCode());
        static::assertTrue($this->config->isPaymentSplitActive());
        static::assertEquals('paymentsplit_type', $this->config->getPaymentSplitType());
        static::assertTrue($this->config->getPaymentSplitTransactionalPostSendRequestAutomatically());
        static::assertEquals('paymentsplit_transactional_post_send_request_automatically_after_x_hours', $this->config->getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours());
        static::assertEquals('paymentsplit_mdr', $this->config->getPaymentSplitDefaultMrd());
        static::assertEquals('paymentsplit_fee', $this->config->getPaymentSplitDefaultFee());
        static::assertTrue($this->config->hasAntiFraud());
    }
}
