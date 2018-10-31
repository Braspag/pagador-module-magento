<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Resource;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class RequestBuilderTest extends \PHPUnit\Framework\TestCase
{
	private $requestBuilder;

	private $requestMock;

    public function setUp()
    {
    	$this->requestMock = $this->getMock(
    		RequestInterface::class
    	);

    	$this->requestBuilder = new RequestBuilder(
    		$this->requestMock
    	);	
    }

    public function testBuilder()
    {
        $orderMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapter = $this->getMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

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
            ->method('setOrderAdapter')
            ->with($orderMock);

        $this->requestMock->expects($this->once())
            ->method('setPaymentData')
            ->with($infoMock);

    	$result = $this->requestBuilder->build($buildSubject);

        static::assertSame($this->requestMock, $result);
    }
}
