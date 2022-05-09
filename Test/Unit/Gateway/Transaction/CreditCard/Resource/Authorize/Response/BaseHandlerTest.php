<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\BaseHandler;
use Webjump\Braspag\Pagador\Transaction\Resource\CreditCard\Send\Response;

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
            ->method('getAuthenticationUrl')
            ->will($this->returnValue('http://teste.com/'));

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
            ->method('setAdditionalInformation')
            ->with('redirect_url', 'http://teste.com/');

    	$handlingSubject = ['payment' => $paymentDataObjectMock];
    	$response = ['response' => $this->responseMock];

    	$this->handler->handle($handlingSubject, $response);
    }
}
