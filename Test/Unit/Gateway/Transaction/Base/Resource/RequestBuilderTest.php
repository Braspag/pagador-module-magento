<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Resource;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;

class RequestBuilderTest extends \PHPUnit\Framework\TestCase
{
	private $requestBuilder;

	private $requestMock;

    public function setUp()
    {
    	$this->requestMock = $this->createMock(
    		RequestInterface::class
    	);

    	$this->requestBuilder = new RequestBuilder(
    		$this->requestMock
    	);	
    }

    public function testBuilder()
    {
        $orderMock = $this->getMockBuilder(OrderAdapterInterface::class)
            ->getMock();

        $orderAdapter = $this->createMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\InfoInterface')
            ->getMock();

    	$paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
    		->setMethods(['getOrder', 'getPayment'])
    		->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($orderAdapter);

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($infoMock));

    	$buildSubject = ['payment' => $paymentDataObjectMock];

        $this->requestMock->expects($this->once())
            ->method('setOrderAdapter')
            ->with($orderAdapter)
            ->willReturnSelf();

        $this->requestMock->expects($this->once())
            ->method('setPaymentData')
            ->with($infoMock);

    	$result = $this->requestBuilder->build($buildSubject);

        static::assertSame($this->requestMock, $result);
    }
}
