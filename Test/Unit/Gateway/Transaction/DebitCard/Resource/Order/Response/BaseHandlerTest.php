<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Resource\Order;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\Response\BaseHandler;
use Webjump\Braspag\Pagador\Transaction\Resource\DebitCard\Send\Response;

class BaseHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;
    private $responseMock;

    public function setUp()
    {
        $this->responseMock = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

    	$this->handler = new BaseHandler(
            $this->responseMock
        );
    }

    public function tearDown()
    {

    }

    public function testHandle()
    {
        $this->responseMock->expects($this->once())
            ->method('getPaymentPaymentId')
            ->will($this->returnValue(123));

        $this->responseMock->expects($this->once())
            ->method('getPaymentAuthenticationUrl')
            ->will($this->returnValue('http://redirect.url'));

        $this->responseMock->expects($this->once())
            ->method('getPaymentCardProvider')
            ->will($this->returnValue('Braspag'));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentMock->expects($this->once())
            ->method('setTransactionId')
            ->with(123);

        $paymentMock->expects($this->once())
            ->method('getCcType')
            ->willReturn('Braspag-Visa');

        $paymentMock->expects($this->atLeastOnce())
            ->method('setAdditionalInformation')
            ->withConsecutive(['send_provider', 'Braspag'], ['receive_provider', 'Braspag'], ['redirect_url', 'http://redirect.url']);

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

    	$handlingSubject = ['payment' => $paymentDataObjectMock];
    	$response = ['response' => $this->responseMock];

    	$this->handler->handle($handlingSubject, $response);
    }
}
