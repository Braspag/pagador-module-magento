<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Capture\Response;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\Response\SplitHandler;
use Webjump\Braspag\Pagador\Transaction\Resource\Actions\Response;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Response\AbstractHandler;
use Webjump\BraspagPagador\Model\SplitManager;
use Webjump\BraspagPagador\Model\SplitDataAdapter;

class SplitHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;
    private $splitManager;
    private $responseMock;
    private $splitAdapter;
    private $paymentSplitResponseMock;
    private $dataObjectMock;

    public function setUp()
    {
        $this->splitManager = $this->getMockBuilder(SplitManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseMock = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->splitAdapter = $this->getMockBuilder(SplitDataAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataObjectMock = $this->getMockBuilder(\Magento\Framework\DataObject::class)
            ->setMethods(['getData'])
            ->getMock();

        $this->paymentSplitResponseMock = $this->createMock(\Webjump\Braspag\Pagador\Transaction\Resource\PaymentSplit\Response::class);

        $this->orderMock = $this->getMockBuilder('\Magento\Sales\Model\Order')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentMock = $this->getMockBuilder('\Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentDataObjectMock = $this->getMockBuilder('\Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

    	$this->handler = new SplitHandler(
            $this->splitManager,
            $this->responseMock,
            $this->splitAdapter
        );
    }

    public function tearDown()
    {

    }

    public function testHandleShouldHandleWithSuccess()
    {
        $this->paymentSplitResponseMock->expects($this->once())
            ->method('getSplits')
            ->willReturn(['123','123']);

        $this->splitAdapter->expects($this->once())
            ->method('adapt')
            ->with(['123','123'])
            ->willReturn($this->dataObjectMock);

        $this->responseMock->expects($this->once())
            ->method('getPaymentSplitPayments')
            ->willReturn($this->paymentSplitResponseMock);

        $this->paymentMock->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue($this->orderMock));

        $this->paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($this->paymentMock));


    	$handlingSubject = ['payment' => $this->paymentDataObjectMock];
    	$response = ['response' => $this->responseMock];

    	$result = $this->handler->handle($handlingSubject, $response);

    	$this->assertEquals($this->responseMock, $result);
    }

    public function testHandleShouldNotHandleWhenInvalidPaymentSplitResponse()
    {
        $this->responseMock->expects($this->once())
            ->method('getPaymentSplitPayments')
            ->willReturn(false);

        $this->paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($this->paymentMock));


        $handlingSubject = ['payment' => $this->paymentDataObjectMock];
        $response = ['response' => $this->responseMock];

        $result = $this->handler->handle($handlingSubject, $response);

        $this->assertEquals($this->handler, $result);
    }
}
