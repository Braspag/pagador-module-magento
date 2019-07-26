<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    protected $objectManagerHelper;
    protected $billetConfigInterfaceMock;
    protected $validatorMock;
    protected $helperData;

    public function setUp()
    {
        $this->objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

    	$this->billetConfigInterfaceMock = $this->createMock('Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface');
    	$this->validatorMock = $this->createMock('Webjump\BraspagPagador\Helper\Validator');

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
                'config' => $this->billetConfigInterfaceMock,
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
    	$this->billetConfigInterfaceMock->expects($this->once())
    	    ->method('getMerchantId')
    	    ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

    	$this->billetConfigInterfaceMock->expects($this->once())
    	    ->method('getMerchantKey')
    	    ->will($this->returnValue('0123456789012345678901234567890123456789'));

    	$this->billetConfigInterfaceMock->expects($this->once())
    	    ->method('getPaymentDemonstrative')
    	    ->will($this->returnValue('Desmonstrative Teste'));

    	$this->billetConfigInterfaceMock->expects($this->once())
    	    ->method('getPaymentInstructions')
    	    ->will($this->returnValue('Aceitar somente até a data de vencimento, após essa data juros de 1% dia.'));

        $this->billetConfigInterfaceMock->expects($this->once())
            ->method('getExpirationDate')
            ->will($this->returnValue('2015-01-05'));

        $this->billetConfigInterfaceMock->expects($this->once())
            ->method('getPaymentAssignor')
            ->will($this->returnValue('Empresa Teste'));

        $this->billetConfigInterfaceMock->expects($this->once())
            ->method('getPaymentProvider')
            ->will($this->returnValue('Simulado'));

    	$paymentMock = $this->createMock('Magento\Sales\Api\Data\OrderPaymentInterface');


        $billingAddressMock = $this->createMock('Magento\Payment\Gateway\Data\AddressAdapterInterface');

        $billingAddressMock->expects($this->once())
            ->method('getFirstname')
            ->will($this->returnValue('John'));

        $billingAddressMock->expects($this->once())
            ->method('getLastname')
            ->will($this->returnValue('Doe'));

        $orderAdapterMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

    	$orderAdapterMock->expects($this->once())
    	    ->method('getGrandTotalAmount')
    	    ->will($this->returnValue('157.00'));

    	$orderAdapterMock->expects($this->exactly(2))
    	    ->method('getOrderIncrementId')
    	    ->will($this->returnValue('2016000001'));

    	$paymentInfoMock = $this->getMockBuilder('Magento\Payment\Model\Info')
            ->disableOriginalConstructor()
            ->getMock();

        $expectedAddress = "Avenida Paulista, 13 Bela Vista São Paulo/SP - 01311-300";
        $expectedPaymentNotification = "2016000001";
        $expectedCustomerName = "John Doe";

        $this->billetConfigInterfaceMock->expects($this->once())
            ->method('getPaymentAssignorAddress')
            ->willReturn($expectedAddress);

        $this->billetConfigInterfaceMock->expects($this->once())
            ->method('getPaymentIdentification')
            ->willReturn($expectedPaymentNotification);


        $orderAdapterMock->expects($this->atLeastOnce())
            ->method('getBillingAddress')
            ->willReturn($billingAddressMock);

        $this->request->setOrderAdapter($orderAdapterMock);

        $this->helperData->expects($this->once())
            ->method('removeSpecialCharacters')
            ->willReturn($expectedCustomerName);

		static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
		static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
		static::assertEquals('2016000001', $this->request->getMerchantOrderId());
		static::assertEquals($expectedCustomerName, $this->request->getCustomerName());
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
