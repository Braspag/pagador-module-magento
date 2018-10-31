<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\ResponseHandler;

class ResponseHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;

    public function setUp()
    {
    	$this->handler = new ResponseHandler();
    }


    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @@expectedExceptionMessage An error has occurred, please try again later.
     */
    public function testHandleWithExpectedGenericError()
    {
    	$responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\ResponseInterface');

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
        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\ResponseInterface');
        $handlingSubject = [];

        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @@expectedExceptionMessage Braspag Billet Send Response Lib object should be provided
     */
    public function testHandleWithExpectedBilletResponseError()
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
        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\Billet\Send\ResponseInterface');

        $responseMock->expects($this->once())
            ->method('getPaymentPaymentId')
            ->will($this->returnValue(123));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $responseMock->expects($this->once())
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
        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }
}
