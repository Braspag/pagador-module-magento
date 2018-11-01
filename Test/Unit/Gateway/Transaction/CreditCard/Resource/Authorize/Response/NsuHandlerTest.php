<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\NsuHandler;

class NsuHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;

    public function setUp()
    {
    	$this->handler = new NsuHandler;
    }

    public function tearDown()
    {

    }

    public function testHandle()
    {
    	$responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

        $responseMock->expects($this->once())
            ->method('getPaymentProofOfSale')
            ->will($this->returnValue('674532'));

        $responseMock->expects($this->once())
            ->method('getPaymentPaymentId')
            ->will($this->returnValue('6e1bf77a-b28b-4660-b14f-455e2a1c95e9'));

        $responseMock->expects($this->once())
            ->method('getPaymentCardProvider')
            ->will($this->returnValue('Rede-Visa'));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentMock->expects($this->at(0))
            ->method('setAdditionalInformation')
            ->with('proof_of_sale', '674532');

        $paymentMock->expects($this->at(1))
            ->method('setAdditionalInformation')
            ->with('payment_token', '6e1bf77a-b28b-4660-b14f-455e2a1c95e9');

        $paymentMock->expects($this->at(2))
            ->method('getCcType')
            ->will($this->returnValue('Cielo-Visa'));

        $paymentMock->expects($this->at(3))
            ->method('setAdditionalInformation')
            ->with('send_provider', 'Cielo');

        $paymentMock->expects($this->at(4))
            ->method('setAdditionalInformation')
            ->with('receive_provider', 'Rede');

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods([])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

    	$handlingSubject = ['payment' => $paymentDataObjectMock];
    	$response = ['response' => $responseMock];

    	$this->handler->handle($handlingSubject, $response);
    }
}
