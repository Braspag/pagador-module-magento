<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    	$this->billetConfigInterfaceMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface');

    	$this->request = new Request(
    		$this->billetConfigInterfaceMock
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
            ->method('getExpirationDays')
            ->will($this->returnValue(3));

        $this->billetConfigInterfaceMock->expects($this->once())
            ->method('getPaymentAssignor')
            ->will($this->returnValue('Empresa Teste'));

    	$paymentMock = $this->getMock('Magento\Sales\Api\Data\OrderPaymentInterface');

    	$paymentMock->expects($this->once())
    	    ->method('getAmountAuthorized')
    	    ->will($this->returnValue('157.00'));

    	$billingAddressMock = $this->getMockBuilder('Magento\Sales\Model\Order\Address')
    		->disableOriginalConstructor()
    		->getMock();

    	$billingAddressMock->expects($this->once())
    	    ->method('getStreet')
    	    ->will($this->returnValue(array(
    	    	'Rua Teste',1, 'Casa 1', 'Centro'
    	    )));

    	$orderMock = $this->getMockBuilder('Magento\Sales\Model\Order')
    		->disableOriginalConstructor()
    		->getMock();

    	$orderMock->expects($this->once())
    	    ->method('getBillingAddress')
    	    ->will($this->returnValue($billingAddressMock));

    	$orderMock->expects($this->once())
    	    ->method('getPayment')
    	    ->will($this->returnValue($paymentMock));

    	$orderMock->expects($this->exactly(3))
    	    ->method('getIncrementId')
    	    ->will($this->returnValue('2016000001'));

    	$orderMock->expects($this->once())
    	    ->method('getCustomerName')
    	    ->will($this->returnValue('John Doe'));

        $orderMock->expects($this->once())
            ->method('getCreatedAt')
            ->will($this->returnValue('2015-01-02'));

    	$paymentInfoMock = $this->getMockBuilder('Magento\Payment\Model\Info')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentInfoMock->expects($this->once())
            ->method('getData')
            ->with('billet_type')
            ->will($this->returnValue('Simulado'));

    	$paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
    		->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
    		->getMock();

    	$paymentDataObjectMock->expects($this->once())
    	    ->method('getOrder')
    	    ->will($this->returnValue($orderMock));

    	$paymentDataObjectMock->expects($this->once())
    	    ->method('getPayment')
    	    ->will($this->returnValue($paymentInfoMock));

    	$this->request->setPayment($paymentDataObjectMock);

		static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
		static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
		static::assertEquals('2016000001', $this->request->getMerchantOrderId());
		static::assertEquals('John Doe', $this->request->getCustomerName());
		static::assertEquals('Boleto', $this->request->getPaymentType());
		static::assertEquals('15700', $this->request->getPaymentAmount());
		static::assertEquals("Rua Teste\n1\nCasa 1\nCentro", $this->request->getPaymentAddress());
		static::assertEquals('Simulado', $this->request->getPaymentProvider());
		static::assertEquals('2016000001', $this->request->getPaymentBoletoNumber());
		static::assertEquals('Empresa Teste', $this->request->getPaymentAssignor());
		static::assertEquals('Desmonstrative Teste', $this->request->getPaymentDemonstrative());
		static::assertEquals('2015-01-05', $this->request->getPaymentExpirationDate());
		static::assertEquals('2016000001', $this->request->getPaymentIdentification());
		static::assertEquals('Aceitar somente até a data de vencimento, após essa data juros de 1% dia.', $this->request->getPaymentInstructions());
    }
}
