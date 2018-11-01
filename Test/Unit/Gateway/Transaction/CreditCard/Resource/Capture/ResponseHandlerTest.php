<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Capture;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\ResponseHandler;

class ResponseHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;

    public function setUp()
    {
    	$this->handler = new ResponseHandler();
    }

    public function tearDown()
    {

    }

    public function testHandle()
    {
    	$responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\Actions\Capture\ResponseInterface');

    	$paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
    		->disableOriginalConstructor()
    	    ->getMock();

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
