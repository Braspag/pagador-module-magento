<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Resource\Order\Response;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\Response\SplitHandler;
use Webjump\Braspag\Pagador\Transaction\Resource\DebitCard\Send\Response;
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

        $this->dataObjectMock->expects($this->once())
            ->method('getData')
            ->willReturn(['123','123']);

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
    	$response = ['response' => $this->responseMock];

    	$result = $this->handler->handle($handlingSubject, $response);

    	$this->assertEquals($this->responseMock, $result);
    }

    public function testHandleShouldNotHandleWhenInvalidPaymentSplitResponse()
    {
        $this->responseMock->expects($this->once())
            ->method('getPaymentSplitPayments')
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
        $response = ['response' => $this->responseMock];

        $result = $this->handler->handle($handlingSubject, $response);

        $this->assertEquals($this->handler, $result);
    }

    public function testGetSplitManager()
    {
        $result = $this->handler->getSplitManager();

        $this->assertEquals($this->splitManager, $result);
    }
}
