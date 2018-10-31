<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\StatusAntiFraudHandler;
use Webjump\BraspagPagador\Model\AntiFraud\Status\Config\ConfigInterface;

class StatusAntiFraudHandlerTest extends \PHPUnit\Framework\TestCase
{
    /** @var StatusAntiFraudHandler  */
	private $handler;

    public function setUp()
    {
    	$this->handler = new StatusAntiFraudHandler();
    }

    public function testHandleAccept()
    {
        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

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

        $antiFraudAnalisysMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\ResponseInterface');

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(1));

        $responseMock->expects($this->once())
            ->method('getPaymentFraudAnalysis')
            ->will($this->returnValue($antiFraudAnalisysMock));

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }

    public function testHandleReject()
    {
        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

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

        $antiFraudAnalisysMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\ResponseInterface');

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(2));

        $responseMock->expects($this->once())
            ->method('getPaymentFraudAnalysis')
            ->will($this->returnValue($antiFraudAnalisysMock));

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }

    public function testHandleReview()
    {
        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

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

        $antiFraudAnalisysMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\ResponseInterface');

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(3));

        $responseMock->expects($this->once())
            ->method('getPaymentFraudAnalysis')
            ->will($this->returnValue($antiFraudAnalisysMock));

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }

    public function testHandleWithoutAntiFraud()
    {
        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

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
        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }
}
