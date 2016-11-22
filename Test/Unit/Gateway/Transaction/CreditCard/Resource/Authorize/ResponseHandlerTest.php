<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\ResponseHandler;

class ResponseHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $handler;

    public function setUp()
    {
        $this->cardTokenFactoryMock = $this->getMock('Webjump\BraspagPagador\Model\CardTokenFactoryInterface');

        $this->handler = new ResponseHandler(
            $this->cardTokenFactoryMock
        );
    }

    public function tearDown()
    {

    }

    public function testHandle()
    {
        $responseMock = $this->getMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

        $responseMock->expects($this->once())
            ->method('getPaymentPaymentId')
            ->will($this->returnValue(123));

        $responseMock->expects($this->once())
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

        $paymentMock->expects($this->once())
            ->method('setTransactionId')
            ->with(123);

        $paymentMock->expects($this->once())
            ->method('getCcNumberEnc')
            ->will($this->returnValue('453.***.***.***.5466'));

        $cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->setMethods(['save'])
            ->disableOriginalConstructor()
            ->getMock();

        $cardTokenMock->expects($this->once())
            ->method('save')
            ->will($this->returnValue($cardTokenMock));

        $this->cardTokenFactoryMock->expects($this->once())
            ->method('create')
            ->with('453.***.***.***.5466', '6e1bf77a-b28b-4660-b14f-455e2a1c95e9')
            ->will($this->returnValue($cardTokenMock));

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }
}
