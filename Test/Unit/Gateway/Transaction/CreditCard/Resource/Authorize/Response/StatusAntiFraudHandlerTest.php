<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\StatusAntiFraudHandler;
use Webjump\Braspag\Pagador\Transaction\Resource\CreditCard\Send\Response;

class StatusAntiFraudHandlerTest extends \PHPUnit\Framework\TestCase
{
    /** @var StatusAntiFraudHandler  */
	private $handler;
    private $responseMock;

    public function setUp()
    {
        $this->responseMock = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

    	$this->handler = new StatusAntiFraudHandler(
            $this->responseMock
        );
    }

    public function testHandleAccept()
    {
        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentMock->expects($this->never())
            ->method('setIsTransactionPending')
            ->with(true);

        $paymentMock->expects($this->never())
            ->method('setIsFraudDetected')
            ->with(true);

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

        $antiFraudAnalisysMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\AntiFraud\ResponseInterface');

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(1));

        $this->responseMock->expects($this->once())
            ->method('getPaymentFraudAnalysis')
            ->will($this->returnValue($antiFraudAnalisysMock));

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $this->responseMock];

        $this->handler->handle($handlingSubject, $response);
    }

    public function testHandleReject()
    {
        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentMock->expects($this->once())
            ->method('setIsFraudDetected')
            ->with(true)
            ->will($this->returnSelf());

        $paymentMock->expects($this->never())
            ->method('setIsTransactionPending')
            ->with(true);

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

        $antiFraudAnalisysMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\AntiFraud\ResponseInterface');

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(2));

        $this->responseMock->expects($this->once())
            ->method('getPaymentFraudAnalysis')
            ->will($this->returnValue($antiFraudAnalisysMock));

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $this->responseMock];

        $this->handler->handle($handlingSubject, $response);
    }

    public function testHandleReview()
    {
        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentMock->expects($this->once())
            ->method('setIsTransactionPending')
            ->with(true)
            ->will($this->returnSelf());

        $paymentMock->expects($this->never())
            ->method('setIsFraudDetected')
            ->with(true);

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

        $antiFraudAnalisysMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\AntiFraud\ResponseInterface');

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(3));

        $this->responseMock->expects($this->once())
            ->method('getPaymentFraudAnalysis')
            ->willReturn($antiFraudAnalisysMock);

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $this->responseMock];

        $this->handler->handle($handlingSubject, $response);
    }

    public function testHandleWithoutAntiFraud()
    {
        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentMock->expects($this->never())
            ->method('setIsFraudDetected')
            ->with(true);

        $paymentMock->expects($this->never())
            ->method('setIsTransactionPending')
            ->with(true);

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $this->responseMock];

        $this->handler->handle($handlingSubject, $response);
    }
}
