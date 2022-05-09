<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Boleto\Resource\Send\Response;

use Webjump\Braspag\Pagador\Transaction\Resource\Boleto\Send\Response;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\Response\BaseHandler;

class BaseHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;
	private $responseMock;

    public function setUp()
    {
        $this->responseMock = $this->createMock(
            Response::class
        );

    	$this->handler = new BaseHandler(
            $this->responseMock
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @@expectedExceptionMessage Braspag Payment Method Send Response Lib object should be provided
     */
    public function testHandleWithExpectedGenericError()
    {
    	$responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\Boleto\Send\ResponseInterface');

    	$paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
    		->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
    		->getMock();

    	$handlingSubject = ['payment' => $paymentDataObjectMock];
    	$response = ['response' => $responseMock];

    	$this->handler->handle($handlingSubject, $response);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @@expectedExceptionMessage Payment data object should be provided
     */
    public function testHandleWithExpectedPaymentDataError()
    {
        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\Boleto\Send\ResponseInterface');
        $handlingSubject = [];

        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @@expectedExceptionMessage Braspag Payment Method Send Response Lib object should be provided
     */
    public function testHandleWithExpectedBoletoResponseError()
    {
        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = [];

        $this->handler->handle($handlingSubject, $response);
    }

    /**
     * @test
     */
    public function testHandleSuccessfully()
    {
//        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\Boleto\Send\ResponseInterface');

        $this->responseMock->expects($this->once())
            ->method('getPaymentPaymentId')
            ->will($this->returnValue(123));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseMock->expects($this->once())
            ->method('getPaymentStatus')
            ->willReturn(1);

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

        $paymentMock->expects($this->once())
            ->method('setTransactionId')
            ->with(123);

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $this->responseMock];

        $this->handler->handle($handlingSubject, $response);
    }
}
