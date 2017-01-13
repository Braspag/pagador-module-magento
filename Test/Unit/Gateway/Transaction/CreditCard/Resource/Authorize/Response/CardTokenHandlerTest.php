<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\CardTokenHandler;
use Magento\Framework\DataObject;

class CardTokenHandlerTest extends \PHPUnit_Framework_TestCase
{
	private $handler;

    private $eventManagerMock;

    private $cardTokenRepositoryMock;

    public function setUp()
    {
        $this->cardTokenRepositoryMock = $this->getMock('Webjump\BraspagPagador\Api\CardTokenRepositoryInterface');

        $this->eventManagerMock = $this->getMock('Magento\Framework\Event\ManagerInterface');

    	$this->handler = new CardTokenHandler(
            $this->cardTokenRepositoryMock,
            $this->eventManagerMock
        );
    }

    public function tearDown()
    {

    }

    public function testHandle()
    {
        $data = new DataObject([
            'alias' => 'xxxx-5466',
            'token' => '6e1bf77a-b28b-4660-b14f-455e2a1c95e9',
            'provider' => 'Cielo',
            'brand' => 'Visa',
        ]);

    	$responseMock = $this->getMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

        $responseMock->expects($this->exactly(3))
            ->method('getPaymentCardToken')
            ->will($this->returnValue('6e1bf77a-b28b-4660-b14f-455e2a1c95e9'));

        $responseMock->expects($this->once())
            ->method('getPaymentCardProvider')
            ->will($this->returnValue('Cielo'));

        $responseMock->expects($this->once())
            ->method('getPaymentCardBrand')
            ->will($this->returnValue('Visa'));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentMock->expects($this->once())
            ->method('getCcLast4')
            ->will($this->returnValue('5466'));

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
            ->with($data->toArray())
            ->will($this->returnValue($cardTokenMock));

        $this->cardTokenRepositoryMock->expects($this->once())
            ->method('save')
            ->with($cardTokenMock)
            ->will($this->returnValue($cardTokenMock));

        $this->eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->with('braspag_creditcard_token_handler_save_before',['card_data' => $data, 'payment' => $paymentMock, 'response' => $responseMock]);

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

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }
}
