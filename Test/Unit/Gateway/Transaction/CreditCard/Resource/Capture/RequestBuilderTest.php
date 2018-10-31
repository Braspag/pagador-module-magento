<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Capture;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\RequestBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\RequestInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Payment\Model\InfoInterface;

class RequestBuilderTest extends \PHPUnit\Framework\TestCase
{
    private $requestBuilder;

    private $requestMock;

    private $OrderRepositoryMock;

    private $paymentMock;

    public function setUp()
    {
        $this->requestMock = $this->createMock(
            RequestInterface::class
        );

        $this->paymentMock = $this->getMockBuilder(InfoInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

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

        $paymentDataObjectMock
            ->expects($this->once())
            ->method('getPayment')
            ->willReturn($this->paymentMock);

        $buildSubject = ['payment' => $paymentDataObjectMock];

        $this->requestMock->expects($this->atLeastOnce())
            ->method('setOrderAdapter');

        $result = $this->requestBuilder->build($buildSubject);

        static::assertSame($this->requestMock, $result);
    }
}
