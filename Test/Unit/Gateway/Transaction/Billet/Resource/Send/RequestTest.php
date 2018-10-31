<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    protected $objectManagerHelper;

    public function setUp()
    {
        $this->objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

    	$this->billetConfigInterfaceMock = $this->getMock('Webjump\BraspagPagador\Gateway\Transaction\Billet\Config\ConfigInterface');

        $this->dateMock = $this->getMockBuilder('Magento\Framework\Stdlib\DateTime\DateTime')
            ->disableOriginalConstructor()
            ->getMock();

    	$this->request = $this->objectManagerHelper->getObject(
            Request::class,
            [
                'config' => $this->billetConfigInterfaceMock
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

    	$paymentMock = $this->getMock('Magento\Sales\Api\Data\OrderPaymentInterface');


        $billingAddressMock = $this->getMock('Magento\Payment\Gateway\Data\AddressAdapterInterface');

        $billingAddressMock->expects($this->once())
            ->method('getFirstname')
            ->will($this->returnValue('John'));

        $billingAddressMock->expects($this->once())
            ->method('getLastname')
            ->will($this->returnValue('Doe'));

        $billingAddressMock->expects($this->once())
            ->method('getStreetLine1')
            ->will($this->returnValue('Avenida Paulista, 13'));

        $billingAddressMock->expects($this->once())
            ->method('getStreetLine2')
            ->will($this->returnValue('Bela Vista'));

        $billingAddressMock->expects($this->once())
            ->method('getCity')
            ->will($this->returnValue('São Paulo'));

        $billingAddressMock->expects($this->once())
            ->method('getRegionCode')
            ->will($this->returnValue('SP'));

        $billingAddressMock->expects($this->once())
            ->method('getPostcode')
            ->will($this->returnValue('01311-300'));

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
		static::assertEquals("Avenida Paulista, 13 Bela Vista São Paulo/SP - 01311-300", $this->request->getPaymentAddress());
		static::assertEquals('Simulado', $this->request->getPaymentProvider());
		static::assertEquals('2016000001', $this->request->getPaymentBoletoNumber());
		static::assertEquals('Empresa Teste', $this->request->getPaymentAssignor());
		static::assertEquals('Desmonstrative Teste', $this->request->getPaymentDemonstrative());
		static::assertEquals('2015-01-05', $this->request->getPaymentExpirationDate());
		static::assertEquals('2016000001', $this->request->getPaymentIdentification());
		static::assertEquals('Aceitar somente até a data de vencimento, após essa data juros de 1% dia.', $this->request->getPaymentInstructions());
    }
}
