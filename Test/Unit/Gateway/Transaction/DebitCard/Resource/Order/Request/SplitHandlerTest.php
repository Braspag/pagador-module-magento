<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Resource\Order\Request;

use Magento\Quote\Model\Quote;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\Request\SplitHandler;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\Request;
use Magento\Payment\Gateway\Request\HandlerInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Request\AbstractHandler;
use Webjump\BraspagPagador\Model\SplitManager;
use Webjump\BraspagPagador\Model\SplitDataAdapter;

class SplitHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;
    private $splitManager;
    private $requestMock;
    private $splitAdapter;
    private $paymentSplitRequestMock;
    private $dataObjectMock;
    private $sessionMock;
    private $quoteMock;

    public function setUp()
    {
        $this->splitManager = $this->getMockBuilder(SplitManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->splitAdapter = $this->getMockBuilder(SplitDataAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataObjectMock = $this->getMockBuilder(\Magento\Framework\DataObject::class)
            ->setMethods(['getData'])
            ->getMock();

        $this->quoteMock = $this->createMock(Quote::class);

        $this->sessionMock = $this->createMock(\Magento\Checkout\Model\Session::class);

        $this->paymentSplitRequestMock = $this->createMock(\Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\PaymentSplit\Request::class);

    	$this->handler = new SplitHandler(
            $this->splitManager,
            $this->requestMock,
            $this->sessionMock
        );
    }

    public function tearDown()
    {

    }

    public function testHandleShouldHandleWithSuccess()
    {
        $this->paymentSplitRequestMock->expects($this->once())
            ->method('prepareSplits')
            ->willReturnSelf();

        $this->paymentSplitRequestMock->expects($this->once())
            ->method('getSplits')
            ->willReturn($this->dataObjectMock);

        $this->sessionMock->expects($this->exactly(1))
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->requestMock->expects($this->exactly(3))
            ->method('getPaymentSplitRequest')
            ->willReturn($this->paymentSplitRequestMock);

        $paymentMock = $this->getMockBuilder('\Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('\Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

    	$handlingSubject = ['payment' => $paymentDataObjectMock];
    	$request = ['request' => $this->requestMock];

    	$result = $this->handler->handle($handlingSubject, $request);

    	$this->assertEquals($this->requestMock, $result);
    }

    public function testHandleShouldNotHandleWhenInvalidPaymentSplitRequest()
    {
        $this->requestMock->expects($this->exactly(1))
            ->method('getPaymentSplitRequest')
            ->willReturn(false);

        $paymentMock = $this->getMockBuilder('\Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('\Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $request = ['request' => $this->requestMock];

        $result = $this->handler->handle($handlingSubject, $request);

        $this->assertEquals($this->handler, $result);
    }
}
