<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Billet\Resource\Send;

use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Billet\Resource\Send\RequestInterface;

class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{
	private $requestBuilder;

	private $requestMock;

    public function setUp()
    {
    	$this->requestMock = $this->getMock(
    		RequestInterface::class
    	);

    	$this->requestBuilder = new RequestBuilder(
    		$this->requestMock
    	);	
    }

    public function testBuilder()
    {
    	$paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
    		->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
    		->getMock();

    	$buildSubject = ['payment' => $paymentDataObjectMock];

        $this->requestMock->expects($this->once())
            ->method('setPaymentDataObject')
            ->with($paymentDataObjectMock);

    	$result = $this->requestBuilder->build($buildSubject);

        static::assertSame($this->requestMock, $result);
    }
}
