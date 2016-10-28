<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    	$this->billetConfigInterfaceMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface');

        $this->dateMock = $this->getMockBuilder('Magento\Framework\Stdlib\DateTime\DateTime')
            ->disableOriginalConstructor()
            ->getMock();

    	$this->request = new Request(
    		$this->billetConfigInterfaceMock,
            $this->dateMock
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

    	$paymentMock = $this->getMock('Magento\Sales\Api\Data\OrderPaymentInterface');


        $billingAddressMock = $this->getMockBuilder('Magento\Sales\Model\Order\Address')
            ->disableOriginalConstructor()
            ->getMock();

        $billingAddressMock->expects($this->once())
            ->method('getFirstname')
            ->will($this->returnValue('John'));

        $billingAddressMock->expects($this->once())
            ->method('getLastname')
            ->will($this->returnValue('Doe'));


        $billingAddressMock->expects($this->once())
            ->method('getStreet')
            ->will($this->returnValue(array(
                'Rua Teste',1, 'Casa 1', 'Centro'
            )));

        $orderAdapterMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapterMock->expects($this->exactly(3))
            ->method('getBillingAddress')
            ->will($this->returnValue($billingAddressMock));

    	$orderAdapterMock->expects($this->once())
    	    ->method('getGrandTotalAmount')
    	    ->will($this->returnValue('157.00'));

    	$orderAdapterMock->expects($this->exactly(3))
    	    ->method('getOrderIncrementId')
    	    ->will($this->returnValue('2016000001'));

    	$paymentInfoMock = $this->getMockBuilder('Magento\Payment\Model\Info')
            ->disableOriginalConstructor()
            ->getMock();

    	$this->request->setOrderAdapter($orderAdapterMock);

		static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
		static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
		static::assertEquals('2016000001', $this->request->getMerchantOrderId());
		static::assertEquals('John Doe', $this->request->getCustomerName());
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
