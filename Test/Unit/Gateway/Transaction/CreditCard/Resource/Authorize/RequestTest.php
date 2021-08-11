<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize;

use Magento\Checkout\Model\Session;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    private $request;
    private $creaditCardConfig;
    private $objectManagerHelper;
    private $configMock;
    protected $sessionMock;
    private $installmentsconfigMock;
    private $validatorMock;
    private $grandTotalPricingHelper;
    private $helperData;
    protected $quoteMock;
    protected $billingAddressMock;

    public function setUp()
    {
        $this->objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuote'])
            ->getMock();

        $this->configMock = $this->getMockBuilder('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface')
            ->disableOriginalConstructor()
            ->setMethods([
                'getSession',
                'getMerchantId',
                'getMerchantKey',
                'getMerchantName',
                'getMCC',
                'getEstablishmentCode',
                'getIsTestEnvironment',
                'isAuthorizeAndCapture',
                'getSoftDescriptor',
                'getIdentityAttributeCode',
                'hasAntiFraud',
                'hasAvs',
                'getReturnUrl',
                'isSaveCardActive',
                'isCreateInvoiceOnNotificationCaptured',
                'getCustomerStreetAttribute',
                'getCustomerNumberAttribute',
                'getCustomerComplementAttribute',
                'getCustomerDistrictAttribute',
                'getDecimalGrandTotal',
                'isAuth3Ds20Active',
                'isAuth3Ds20MCOnlyNotifyActive',
                'isAuth3Ds20AuthorizedOnError',
                'isAuth3Ds20AuthorizedOnFailure',
                'isAuth3Ds20AuthorizeOnUnenrolled',
                'isAuth3Ds20AuthorizeOnUnsupportedBrand',
                'getAuth3Ds20Mdd1',
                'getAuth3Ds20Mdd2',
                'getAuth3Ds20Mdd3',
                'getAuth3Ds20Mdd4',
                'getAuth3Ds20Mdd5',
                'isPaymentSplitActive',
                'getPaymentSplitType',
                'getPaymentSplitTransactionalPostSendRequestAutomatically',
                'getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours',
                'getPaymentSplitDefaultMrd',
                'getPaymentSplitDefaultFee',
                'isCardViewActive'
            ])
            ->getMock();


        $this->installmentsconfigMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface');

        $this->validatorMock = $this->createMock('Webjump\BraspagPagador\Helper\Validator');

        $this->quoteMock = $this->createMock(\Magento\Quote\Model\Quote::class);
        $this->customerMock = $this->createMock(\Magento\Customer\Model\Customer::class);

        $this->grandTotalPricingHelper = $this->getMockBuilder('\Webjump\BraspagPagador\Helper\GrandTotal\Pricing')
            ->disableOriginalConstructor()
            ->setMethods(['currency'])
            ->getMock();

        $this->helperData = $this->getMockBuilder('\Webjump\BraspagPagador\Helper\Data')
            ->disableOriginalConstructor()
            ->setMethods(['removeSpecialCharacters', 'removeSpecialCharactersFromTaxvat'])
            ->getMock();

        $this->billingAddressMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\AddressAdapterInterface')
            ->setMethods([
                'getData',
                'getRegionCode',
                'getCountryId',
                'getStreetLine1',
                'getStreetLine2',
                'getTelephone',
                'getPostcode',
                'getCity',
                'getFirstname',
                'getLastname',
                'getMiddlename',
                'getCustomerId',
                'getEmail',
                'getPrefix',
                'getSuffix',
                'getCompany',
                'getStreetLine'
            ])
            ->getMock();

        $this->shippingAddressMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\AddressAdapterInterface')
            ->setMethods([
                'getData',
                'getRegionCode',
                'getCountryId',
                'getStreetLine1',
                'getStreetLine2',
                'getTelephone',
                'getPostcode',
                'getCity',
                'getFirstname',
                'getLastname',
                'getMiddlename',
                'getCustomerId',
                'getEmail',
                'getPrefix',
                'getSuffix',
                'getCompany',
                'getStreetLine'
            ])
            ->getMock();

        $this->request = $this->objectManagerHelper->getObject(
            Request::class,
            [
                'config' => $this->configMock,
                'installmentsConfig' => $this->installmentsconfigMock,
                'validator' => $this->validatorMock,
                'grandTotalPricingHelper' => $this->grandTotalPricingHelper,
                'helperData' => $this->helperData
            ]
        );
    }

    public function tearDown()
    {

    }

    public function testGetData()
    {
        $expectedCustomerName = "John Doe";

        $this->configMock->expects($this->once())
            ->method('getMerchantId')
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $this->configMock->expects($this->once())
            ->method('getMerchantKey')
            ->will($this->returnValue('0123456789012345678901234567890123456789'));

        $this->configMock->expects($this->once())
            ->method('isAuthorizeAndCapture')
            ->will($this->returnValue(true));

        $this->configMock->expects($this->once())
            ->method('getSoftDescriptor')
            ->will($this->returnValue('Texto que será impresso na fatura do portador'));

        $this->configMock->expects($this->exactly(2))
            ->method('getIdentityAttributeCode')
            ->will($this->returnValue('taxvat'));

        $this->configMock->expects($this->atLeastOnce())
            ->method('getCustomerStreetAttribute')
            ->will($this->returnValue('street_1'));

        $this->configMock->expects($this->atLeastOnce())
            ->method('getCustomerNumberAttribute')
            ->will($this->returnValue('street_2'));

        $this->configMock->expects($this->atLeastOnce())
            ->method('getCustomerComplementAttribute')
            ->will($this->returnValue('street_3'));

        $this->configMock->expects($this->atLeastOnce())
            ->method('getCustomerDistrictAttribute')
            ->will($this->returnValue('street_4'));

        $this->configMock->expects($this->atLeastOnce())
            ->method('isAuth3Ds20Active')
            ->will($this->returnValue(true));

        $this->configMock->expects($this->atLeastOnce())
            ->method('isAuthorizeAndCapture')
            ->will($this->returnValue(true));

        $this->configMock->expects($this->atLeastOnce())
            ->method('getIsTestEnvironment')
            ->will($this->returnValue(true));

        $this->configMock->expects($this->atLeastOnce())
            ->method('getSoftDescriptor')
            ->will($this->returnValue('soft_descriptor'));

        $this->configMock->expects($this->atLeastOnce())
            ->method('isPaymentSplitActive')
            ->will($this->returnValue(true));

        $this->validatorMock->expects($this->atLeastOnce())
            ->method('sanitizeDistrict')
            ->with('Centro')
            ->willReturn('Centro');

        $this->billingAddressMock->expects($this->once())
            ->method('getFirstname')
            ->will($this->returnValue('John'));

        $this->billingAddressMock->expects($this->once())
            ->method('getLastname')
            ->will($this->returnValue('Doe'));

        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('getStreetLine')
            ->withConsecutive(['1'],['2'],['3'],['4'])
            ->willReturnOnConsecutiveCalls('rua x', '123', 'casa 123', 'Centro');

        $this->billingAddressMock->expects($this->once())
            ->method('getCity')
            ->will($this->returnValue('São Paulo'));

        $this->billingAddressMock->expects($this->once())
            ->method('getRegionCode')
            ->will($this->returnValue('SP'));

        $this->billingAddressMock->expects($this->once())
            ->method('getPostcode')
            ->will($this->returnValue('12345678'));

        $this->billingAddressMock->expects($this->once())
            ->method('getEmail')
            ->will($this->returnValue('johndoe@webjump.com.br'));

        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('getData')
            ->with('taxvat')
            ->will($this->returnValue('12345678912'));

        $this->billingAddressMock->expects($this->atLeastOnce())
            ->method('getTelephone')
            ->will($this->returnValue('11123456789'));

        $this->shippingAddressMock->expects($this->atLeastOnce())
            ->method('getStreetLine')
            ->withConsecutive(['1'],['2'],['3'],['4'])
            ->willReturnOnConsecutiveCalls('rua x', '123', 'casa 123', 'Centro');

        $this->shippingAddressMock->expects($this->atLeastOnce())
            ->method('getCity')
            ->will($this->returnValue('São Paulo'));

        $this->shippingAddressMock->expects($this->once())
            ->method('getRegionCode')
            ->will($this->returnValue('SP'));

        $this->shippingAddressMock->expects($this->atLeastOnce())
            ->method('getPostcode')
            ->will($this->returnValue('01311300'));

        $this->shippingAddressMock->expects($this->once())
            ->method('getTelephone')
            ->will($this->returnValue('11123456789'));

        $orderAdapterMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapterMock->expects($this->exactly(1))
            ->method('getBillingAddress')
            ->will($this->returnValue($this->billingAddressMock));

        $this->helperData->expects($this->once())
            ->method('removeSpecialCharacters')
            ->willReturn($expectedCustomerName);

        $this->helperData->expects($this->exactly(2))
            ->method('removeSpecialCharactersFromTaxvat')
            ->willReturn('12345678912');

        $this->quoteMock->expects($this->atLeastOnce())
            ->method('getBillingAddress')
            ->willReturn($this->billingAddressMock);

        $this->quoteMock->expects($this->atLeastOnce())
            ->method('getShippingAddress')
            ->willReturn($this->shippingAddressMock);

        $this->quoteMock->expects($this->atLeastOnce())
            ->method('getCustomer')
            ->willReturn($this->customerMock);

        $this->configMock->expects($this->once())
            ->method('getReturnUrl')
            ->willReturn('http://return.url');

        $orderAdapterMock->expects($this->exactly(1))
            ->method('getShippingAddress')
            ->will($this->returnValue($this->shippingAddressMock));

        $this->grandTotalPricingHelper->expects($this->once())
            ->method('currency')
            ->with(157.00)
            ->will($this->returnValue(15700));

        $orderAdapterMock->expects($this->once())
            ->method('getGrandTotalAmount')
            ->will($this->returnValue(157.00));

        $orderAdapterMock->expects($this->once())
            ->method('getGrandTotalAmount')
            ->will($this->returnValue(157.00));

        $orderAdapterMock->expects($this->exactly(1))
            ->method('getOrderIncrementId')
            ->will($this->returnValue('2016000001'));

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\Info')
            ->disableOriginalConstructor()
            ->setMethods([
                'getCcType',
                'getAdditionalInformation',
                'getCcNumber',
                'getCcOwner',
                'getCcExpMonth',
                'getCcExpYear',
                'getCcCid',
                'getCcSavecard',
            ])
            ->getMock();

        $infoMock->expects($this->once())
            ->method('getCcNumber')
            ->will($this->returnValue('1234123412341231'));

        $infoMock->expects($this->once())
            ->method('getCcOwner')
            ->will($this->returnValue('John Due'));

        $infoMock->expects($this->exactly(2))
            ->method('getCcType')
            ->will($this->returnValue('Cielo-Visa'));

        $infoMock->expects($this->once())
            ->method('getCcExpMonth')
            ->will($this->returnValue('05'));

        $infoMock->expects($this->once())
            ->method('getCcExpYear')
            ->will($this->returnValue('2019'));

        $infoMock->expects($this->once())
            ->method('getCcCid')
            ->will($this->returnValue('123'));

        $infoMock->expects($this->any())
            ->method('getAdditionalInformation')
            ->will($this->returnValueMap(array(
                array('cc_savecard', true),
                array('cc_installments', 3),
                array('authentication_failure_type', 3),
                array('authentication_cavv', 44),
                array('authentication_xid', 55),
                array('authentication_eci', 66),
                array('authentication_version', 77),
                array('authentication_reference_id', 88),
                array('cc_token', '123456789'),
                array('cc_soptpaymenttoken', '123456789'),
            )));

        $this->request->setOrderAdapter($orderAdapterMock);
        $this->request->setQuote($this->quoteMock);
        $this->request->setPaymentData($infoMock);

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
        static::assertEquals('2016000001', $this->request->getMerchantOrderId());
        static::assertEquals($expectedCustomerName, $this->request->getCustomerName());
        static::assertEquals('12345678912',$this->request->getCustomerIdentity());
        static::assertEquals('CPF', $this->request->getCustomerIdentityType());
        static::assertEquals('johndoe@webjump.com.br', $this->request->getCustomerEmail());
        static::assertNull($this->request->getCustomerBirthDate());

        static::assertEquals('rua x', $this->request->getCustomerAddressStreet());
        static::assertEquals('123', $this->request->getCustomerAddressNumber());
        static::assertEquals('casa 123', $this->request->getCustomerAddressComplement());
        static::assertEquals('12345678', $this->request->getCustomerAddressZipCode());
        static::assertEquals('Centro', $this->request->getCustomerAddressDistrict());
        static::assertEquals('São Paulo', $this->request->getCustomerAddressCity());
        static::assertEquals('SP', $this->request->getCustomerAddressState());
        static::assertEquals('BRA', $this->request->getCustomerAddressCountry());

        static::assertEquals('rua x', $this->request->getCustomerDeliveryAddressStreet());
        static::assertEquals('123', $this->request->getCustomerDeliveryAddressNumber());
        static::assertEquals('casa 123', $this->request->getCustomerDeliveryAddressComplement());
        static::assertEquals('01311300', $this->request->getCustomerDeliveryAddressZipCode());
        static::assertEquals('Centro', $this->request->getCustomerDeliveryAddressDistrict());
        static::assertEquals('São Paulo', $this->request->getCustomerDeliveryAddressCity());
        static::assertEquals('SP', $this->request->getCustomerDeliveryAddressState());
        static::assertEquals('BRA', $this->request->getCustomerDeliveryAddressCountry());
        static::assertEquals('15700', $this->request->getPaymentAmount());
        static::assertEquals('BRL', $this->request->getPaymentCurrency());
        static::assertEquals('BRA', $this->request->getPaymentCountry());
        static::assertEquals('Cielo', $this->request->getPaymentProvider());
        static::assertEquals(0, $this->request->getPaymentServiceTaxAmount());
        static::assertEquals(3, $this->request->getPaymentInstallments());
        static::assertEquals('ByMerchant', $this->request->getPaymentInterest());
        static::assertTrue($this->request->getPaymentCapture());
        static::assertFalse($this->request->getPaymentAuthenticate());
        static::assertEquals('Texto que será impresso na fatura do portador', $this->request->getPaymentSoftDescriptor());
        static::assertEquals('1234123412341231', $this->request->getPaymentCreditCardCardNumber());
        static::assertEquals('John Due', $this->request->getPaymentCreditCardHolder());
        static::assertEquals('05/2019', $this->request->getPaymentCreditCardExpirationDate());
        static::assertEquals('123', $this->request->getPaymentCreditCardSecurityCode());
        static::assertTrue($this->request->getPaymentCreditCardSaveCard());
        static::assertEquals('Visa', $this->request->getPaymentCreditCardBrand());
        static::assertNull($this->request->getPaymentExtraDataCollection());
        static::assertNull($this->request->getAntiFraudRequest());
        static::assertTrue($this->request->isTestEnvironment());
        static::assertEquals('11123456789', $this->request->getCustomerAddressPhone());
        static::assertEquals('11123456789', $this->request->getCustomerDeliveryAddressPhone());
        static::assertFalse($this->request->getPaymentType());
        static::assertTrue($this->request->getPaymentDoSplit());
        static::assertEquals('http://return.url', $this->request->getReturnUrl());
        static::assertEquals(44, $this->request->getPaymentExternalAuthenticationCavv());
        static::assertEquals(55, $this->request->getPaymentExternalAuthenticationXid());
        static::assertEquals(66, $this->request->getPaymentExternalAuthenticationEci());
        static::assertEquals(77, $this->request->getPaymentCardExternalAuthenticationVersion());
        static::assertEquals(88, $this->request->getPaymentExternalAuthenticationReferenceId());
        static::assertEquals('123456789', $this->request->getPaymentCreditCardCardToken());
        static::assertEquals('123456789', $this->request->getPaymentCreditSoptpaymenttoken());
        static::assertEquals($this->customerMock, $this->request->getCustomerCreditCard());
    }

    public function testgetCustomerIdentityShouldReturnCustomerEntityFromQuoteWhenEmptyInBillingAddress()
    {
        $expectedCustomerName = "John Doe";

        $this->configMock->expects($this->exactly(1))
            ->method('getIdentityAttributeCode')
            ->will($this->returnValue('taxvat'));

        $orderAdapterMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $this->helperData->expects($this->atLeastOnce())
            ->method('removeSpecialCharactersFromTaxvat')
            ->willReturnOnConsecutiveCalls( false, '12345678912');

        $this->quoteMock->expects($this->atLeastOnce())
            ->method('getBillingAddress')
            ->willReturn($this->billingAddressMock);

        $this->quoteMock->expects($this->atLeastOnce())
            ->method('getData')
            ->willReturn('12345678912');

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\Info')
            ->disableOriginalConstructor()
            ->setMethods([
                'getCcType',
                'getAdditionalInformation',
                'getCcNumber',
                'getCcOwner',
                'getCcExpMonth',
                'getCcExpYear',
                'getCcCid',
                'getCcSavecard',
            ])
            ->getMock();

        $infoMock->expects($this->any())
            ->method('getAdditionalInformation')
            ->will($this->returnValueMap(array(
                array('cc_savecard', true),
                array('cc_installments', 3),
                array('authentication_failure_type', 3),
                array('authentication_cavv', 44),
                array('authentication_xid', 55),
                array('authentication_eci', 66),
                array('authentication_version', 77),
                array('authentication_reference_id', 88),
                array('cc_token', '123456789'),
                array('cc_soptpaymenttoken', '123456789'),
            )));

        $this->request->setOrderAdapter($orderAdapterMock);
        $this->request->setQuote($this->quoteMock);
        $this->request->setPaymentData($infoMock);

        static::assertEquals('12345678912',$this->request->getCustomerIdentity());
    }

    public function testGetCustomerDeliveryAddressZipCodeShouldReturnNull()
    {
        $expectedCustomerName = "John Doe";

        $orderAdapterMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapterMock->expects($this->atLeastOnce())
            ->method('getShippingAddress')
            ->willReturn(false);

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\Info')
            ->disableOriginalConstructor()
            ->setMethods([
                'getCcType',
                'getAdditionalInformation',
                'getCcNumber',
                'getCcOwner',
                'getCcExpMonth',
                'getCcExpYear',
                'getCcCid',
                'getCcSavecard',
            ])
            ->getMock();

        $infoMock->expects($this->any())
            ->method('getAdditionalInformation')
            ->will($this->returnValueMap(array(
                array('cc_savecard', true),
                array('cc_installments', 3),
                array('authentication_failure_type', 3),
                array('authentication_cavv', 44),
                array('authentication_xid', 55),
                array('authentication_eci', 66),
                array('authentication_version', 77),
                array('authentication_reference_id', 88),
                array('cc_token', '123456789'),
                array('cc_soptpaymenttoken', '123456789'),
            )));

        $this->request->setOrderAdapter($orderAdapterMock);
        $this->request->setQuote($this->quoteMock);
        $this->request->setPaymentData($infoMock);

        static::assertNull($this->request->getCustomerDeliveryAddressZipCode());
        static::assertNull($this->request->getCustomerDeliveryAddressCity());
        static::assertNull($this->request->getCustomerDeliveryAddressState());
        static::assertEquals('', $this->request->getCustomerDeliveryAddressCountry());
    }
}
