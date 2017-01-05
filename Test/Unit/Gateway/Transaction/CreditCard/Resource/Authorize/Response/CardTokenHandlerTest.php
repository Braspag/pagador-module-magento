<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\CardTokenHandler;

class CardTokenHandlerTest extends \PHPUnit_Framework_TestCase
{
	private $handler;

    public function setUp()
    {
        $this->cardTokenRepositoryMock = $this->getMock('Webjump\BraspagPagador\Api\CardTokenRepositoryInterface');

    	$this->handler = new CardTokenHandler(
            $this->cardTokenRepositoryMock
        );
    }

    public function tearDown()
    {

    }

    public function testHandle()
    {
    	$responseMock = $this->getMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

        $responseMock->expects($this->exactly(3))
            ->method('getPaymentCardToken')
            ->will($this->returnValue('6e1bf77a-b28b-4660-b14f-455e2a1c95e9'));

        $responseMock->expects($this->once())
            ->method('getPaymentCardNumberEncrypted')
            ->will($this->returnValue('453.***.***.***.5466'));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

        $cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->disableOriginalConstructor()
            ->getMock();

        $this->cardTokenRepositoryMock->expects($this->once())
            ->method('get')
            ->with('6e1bf77a-b28b-4660-b14f-455e2a1c95e9')
            ->will($this->returnValue(null));

        $this->cardTokenRepositoryMock->expects($this->once())
            ->method('create')
            ->with('453.***.***.***.5466', '6e1bf77a-b28b-4660-b14f-455e2a1c95e9')
            ->will($this->returnValue($cardTokenMock));

        $this->cardTokenRepositoryMock->expects($this->once())
            ->method('save')
            ->with($cardTokenMock)
            ->will($this->returnValue($cardTokenMock));

    	$handlingSubject = ['payment' => $paymentDataObjectMock];
    	$response = ['response' => $responseMock];

    	$this->handler->handle($handlingSubject, $response);
    }

    public function testHandleWithAlreadyCardToken()
    {
        $responseMock = $this->getMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

        $responseMock->expects($this->exactly(2))
            ->method('getPaymentCardToken')
            ->will($this->returnValue('6e1bf77a-b28b-4660-b14f-455e2a1c95e9'));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

        $cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->disableOriginalConstructor()
            ->getMock();

        $this->cardTokenRepositoryMock->expects($this->once())
            ->method('get')
            ->with('6e1bf77a-b28b-4660-b14f-455e2a1c95e9')
            ->will($this->returnValue($cardTokenMock));

        // $this->cardTokenRepositoryMock->expects($this->once())
        //     ->method('create')
        //     ->with('453.***.***.***.5466', '6e1bf77a-b28b-4660-b14f-455e2a1c95e9')
        //     ->will($this->returnValue($cardTokenMock));

        // $this->cardTokenRepositoryMock->expects($this->once())
        //     ->method('save')
        //     ->with($cardTokenMock)
        //     ->will($this->returnValue($cardTokenMock));

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }
}
