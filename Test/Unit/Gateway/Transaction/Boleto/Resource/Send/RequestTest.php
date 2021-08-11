<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Boleto\Resource\Send;

use Magento\Checkout\Model\Session;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    protected $objectManagerHelper;
    protected $boletoConfigInterfaceMock;
    protected $validatorMock;
    protected $helperData;
    protected $quoteMock;
    protected $sessionMock;
    protected $customerMock;

    public function setUp()
    {
        $this->objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuote'])
            ->getMock();

    	$this->boletoConfigInterfaceMock = $this->getMockBuilder('Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface')
            ->disableOriginalConstructor()
            ->setMethods([
                'getSession',
                'getMerchantId',
                'getMerchantKey',
                'getMerchantName',
                'getMCC',
                'getEstablishmentCode',
                'getIsTestEnvironment',
                'isPaymentSplitActive',
                'getPaymentSplitType',
                'getPaymentInstructions',
                'getExpirationDate',
                'getPaymentAssignor',
                'getPaymentProvider',
                'getPaymentAssignorAddress',
                'getPaymentIdentification',
                'getIdentityAttributeCode',
                'getCustomerStreetAttribute',
                'getCustomerNumberAttribute',
                'getCustomerComplementAttribute',
                'getCustomerDistrictAttribute',
                'getPaymentSplitTransactionalPostSendRequestAutomatically',
                'getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours',
                'getPaymentSplitDefaultMrd',
                'getPaymentSplitDefaultFee',
                'getPaymentBank',
                'hasAntiFraud',
                'getPaymentDemonstrative'
            ])
            ->getMock();

    	$this->validatorMock = $this->createMock('Webjump\BraspagPagador\Helper\Validator');

        $this->quoteMock = $this->createMock(\Magento\Quote\Model\Quote::class);
        $this->customerMock = $this->createMock(\Magento\Customer\Model\Customer::class);

    	$this->helperData = $this->getMockBuilder('\Webjump\BraspagPagador\Helper\Data')
            ->disableOriginalConstructor()
            ->setMethods(['removeSpecialCharacters'])
            ->getMock();

        $this->dateMock = $this->getMockBuilder('Magento\Framework\Stdlib\DateTime\DateTime')
            ->disableOriginalConstructor()
            ->getMock();

    	$this->request = $this->objectManagerHelper->getObject(
            Request::class,
            [
                'config' => $this->boletoConfigInterfaceMock,
                'validator' => $this->validatorMock,
                'helperData' => $this->helperData
            ]
        );
    }

    public function tearDown()
    {

    }

    public function testGetData()
    {
    	$paymentMock = $this->createMock('Magento\Sales\Api\Data\OrderPaymentInterface');

        $billingAddressMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\AddressAdapterInterface')
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

        $orderAdapterMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $paymentInfoMock = $this->getMockBuilder('Magento\Payment\Model\Info')
            ->disableOriginalConstructor()
            ->getMock();

        $expectedAddress = "Avenida Paulista, 13 Bela Vista São Paulo/SP - 01311-300";
        $expectedPaymentNotification = "2016000001";
        $expectedCustomerName = "John Doe";

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getMerchantId')
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getMerchantKey')
            ->will($this->returnValue('0123456789012345678901234567890123456789'));

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getIsTestEnvironment')
            ->will($this->returnValue(true));

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getPaymentDemonstrative')
            ->will($this->returnValue('Desmonstrative Teste'));

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getPaymentInstructions')
            ->will($this->returnValue('Aceitar somente até a data de vencimento, após essa data juros de 1% dia.'));

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getExpirationDate')
            ->will($this->returnValue('2015-01-05'));

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getPaymentAssignor')
            ->will($this->returnValue('Empresa Teste'));

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getPaymentProvider')
            ->will($this->returnValue('Simulado'));

        $this->boletoConfigInterfaceMock->expects($this->exactly(2))
            ->method('getIdentityAttributeCode')
            ->will($this->returnValue('taxvat'));

        $this->boletoConfigInterfaceMock->expects($this->exactly(1))
            ->method('getCustomerStreetAttribute')
            ->will($this->returnValue('street_1'));

        $this->boletoConfigInterfaceMock->expects($this->exactly(1))
            ->method('getCustomerNumberAttribute')
            ->will($this->returnValue('street_2'));

        $this->boletoConfigInterfaceMock->expects($this->exactly(1))
            ->method('getCustomerComplementAttribute')
            ->will($this->returnValue('street_3'));

        $this->boletoConfigInterfaceMock->expects($this->exactly(1))
            ->method('getCustomerDistrictAttribute')
            ->will($this->returnValue('street_4'));

        $this->validatorMock->expects($this->once())
            ->method('sanitizeDistrict')
            ->with('Centro')
            ->willReturn('Centro');

        $this->boletoConfigInterfaceMock->expects($this->exactly(1))
            ->method('getPaymentBank')
            ->will($this->returnValue('bank'));

        $billingAddressMock->expects($this->once())
            ->method('getFirstname')
            ->will($this->returnValue('John'));

        $billingAddressMock->expects($this->once())
            ->method('getLastname')
            ->will($this->returnValue('Doe'));

        $billingAddressMock->expects($this->atLeastOnce())
            ->method('getData')
            ->with('taxvat')
            ->will($this->returnValue('12345678912'));

        $billingAddressMock->expects($this->once())
            ->method('getEmail')
            ->will($this->returnValue('braspag@webjump.com.br'));

        $billingAddressMock->expects($this->atLeastOnce())
            ->method('getStreetLine')
            ->withConsecutive(['1'],['2'],['3'],['4'])
            ->willReturnOnConsecutiveCalls('rua x', '123', 'casa 123', 'Centro');

        $billingAddressMock->expects($this->once())
            ->method('getPostcode')
            ->will($this->returnValue('12345678'));

        $billingAddressMock->expects($this->once())
            ->method('getCity')
            ->will($this->returnValue('São Paulo'));

        $billingAddressMock->expects($this->once())
            ->method('getRegionCode')
            ->will($this->returnValue('SP'));

    	$orderAdapterMock->expects($this->once())
    	    ->method('getGrandTotalAmount')
    	    ->will($this->returnValue('157.00'));

    	$orderAdapterMock->expects($this->exactly(2))
    	    ->method('getOrderIncrementId')
    	    ->will($this->returnValue('2016000001'));

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getPaymentAssignorAddress')
            ->willReturn($expectedAddress);

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getPaymentIdentification')
            ->willReturn($expectedPaymentNotification);

        $orderAdapterMock->expects($this->atLeastOnce())
            ->method('getBillingAddress')
            ->willReturn($billingAddressMock);

        $this->helperData->expects($this->once())
            ->method('removeSpecialCharacters')
            ->willReturn($expectedCustomerName);

        $this->quoteMock->expects($this->atLeastOnce())
            ->method('getBillingAddress')
            ->willReturn($billingAddressMock);

        $this->quoteMock->expects($this->atLeastOnce())
            ->method('getCustomer')
            ->willReturn($this->customerMock);

        $this->sessionMock->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->boletoConfigInterfaceMock->expects($this->once())
            ->method('getSession')
            ->willReturn($this->sessionMock);

        $this->request->setOrderAdapter($orderAdapterMock);

		static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
		static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
		static::assertEquals('2016000001', $this->request->getMerchantOrderId());
		static::assertEquals(true, $this->request->isTestEnvironment());
		static::assertEquals($expectedCustomerName, $this->request->getCustomerName());

		static::assertEquals('12345678912', $this->request->getCustomerIdentity());
		static::assertEquals('CPF', $this->request->getCustomerIdentityType());
		static::assertEquals('braspag@webjump.com.br', $this->request->getCustomerEmail());
		static::assertNull($this->request->getCustomerBirthDate());

		static::assertEquals('rua x', $this->request->getCustomerAddressStreet());
		static::assertEquals('123', $this->request->getCustomerAddressNumber());
		static::assertEquals('casa 123', $this->request->getCustomerAddressComplement());
		static::assertEquals('12345678', $this->request->getCustomerAddressZipCode());
		static::assertEquals('Centro', $this->request->getCustomerAddressDistrict());
		static::assertEquals('São Paulo', $this->request->getCustomerAddressCity());
		static::assertEquals('SP', $this->request->getCustomerAddressState());
		static::assertEquals('BRA', $this->request->getCustomerAddressCountry());

		static::assertEquals('bank', $this->request->getPaymentBank());
		static::assertEquals($this->customerMock, $this->request->getCustomerBoleto());

		static::assertEquals('15700', $this->request->getPaymentAmount());
		static::assertEquals($expectedAddress, $this->request->getPaymentAddress());
		static::assertEquals('Simulado', $this->request->getPaymentProvider());
		static::assertEquals('2016000001', $this->request->getPaymentBoletoNumber());
		static::assertEquals('Empresa Teste', $this->request->getPaymentAssignor());
		static::assertEquals('Desmonstrative Teste', $this->request->getPaymentDemonstrative());
		static::assertEquals('2015-01-05', $this->request->getPaymentExpirationDate());
		static::assertEquals($expectedPaymentNotification, $this->request->getPaymentIdentification());
		static::assertEquals('Aceitar somente até a data de vencimento, após essa data juros de 1% dia.', $this->request->getPaymentInstructions());
    }
}
