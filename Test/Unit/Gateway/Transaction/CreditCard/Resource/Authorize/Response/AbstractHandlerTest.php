<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\AbstractHandler;

class AbstractHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;

    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    public function testHandle()
    {
        $this->handler = $this->getMockForAbstractClass('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\AbstractHandler');
        

        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));
 
        $this->handler->expects($this->once())
             ->method('_handle')
             ->with($paymentMock, $responseMock)
             ->will($this->returnValue($this->handler));

    	$handlingSubject = ['payment' => $paymentDataObjectMock];
    	$response = ['response' => $responseMock];

    	$this->handler->handle($handlingSubject, $response);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Payment data object should be provided
     */
    public function testHandleWithoutPayment()
    {
        $this->handler = $this->getMockForAbstractClass('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\AbstractHandler');

        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');
 
        $this->handler->expects($this->never())
             ->method('_handle');

        $handlingSubject = [];
        $response = ['response' => $responseMock];

        $this->handler->handle($handlingSubject, $response);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Braspag CreditCard Send Response Lib object should be provided
     */
    public function testHandleWithoutResponse()
    {
        $this->handler = $this->getMockForAbstractClass('Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\AbstractHandler');

        $responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');
 
        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->getMock();

        $this->handler->expects($this->never())
             ->method('_handle');

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = [];

        $this->handler->handle($handlingSubject, $response);
    }
}
