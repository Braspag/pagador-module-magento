<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{
	private $requestBuilder;

	private $requestMock;

    private $OrderRepositoryMock;

    public function setUp()
    {
    	$this->requestMock = $this->getMock(
    		RequestInterface::class
    	);

        $this->orderRepositoryMock = $this->getMock(
            OrderRepositoryInterface::class
        );

    	$this->requestBuilder = new RequestBuilder(
    		$this->requestMock,
            $this->orderRepositoryMock
    	);	
    }

    public function testBuilder()
    {
        $orderMock = $this->getMockBuilder('Magento\Sales\Api\Data\OrderInterface')
            ->getMock();

        $this->orderRepositoryMock->expects($this->once())
            ->method('get')
            ->with(1)
            ->will($this->returnValue($orderMock));

        $orderAdapter = $this->getMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

        $orderAdapter->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\InfoInterface')
            ->getMock();

    	$paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
    		->setMethods(['getOrder', 'getPayment'])
    		->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue($orderAdapter));

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($infoMock));

    	$buildSubject = ['payment' => $paymentDataObjectMock];

        $this->requestMock->expects($this->once())
            ->method('setOrder')
            ->with($orderMock);

        $this->requestMock->expects($this->once())
            ->method('setPaymentInfo')
            ->with($infoMock);

    	$result = $this->requestBuilder->build($buildSubject);

        static::assertSame($this->requestMock, $result);
    }
}
