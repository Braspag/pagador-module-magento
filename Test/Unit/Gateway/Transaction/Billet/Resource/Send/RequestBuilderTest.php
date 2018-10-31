<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class RequestBuilderTest extends \PHPUnit\Framework\TestCase
{
	private $requestBuilder;

	private $requestMock;

    private $OrderRepositoryMock;

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
        $orderMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapter = $this->createMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\InfoInterface')
            ->getMock();

    	$paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
    		->setMethods(['getOrder', 'getPayment'])
    		->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue($orderAdapter));

    	$buildSubject = ['payment' => $paymentDataObjectMock];

        $this->requestMock->expects($this->once())
            ->method('setOrderAdapter')
            ->with($orderAdapter)
            ->willReturnSelf();

    	$result = $this->requestBuilder->build($buildSubject);

        static::assertSame($this->requestMock, $result);
    }
}
