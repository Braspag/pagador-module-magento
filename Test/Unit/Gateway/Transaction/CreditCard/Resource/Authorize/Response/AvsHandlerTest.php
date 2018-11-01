<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\AvsHandler;

class AvsHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;

    public function setUp()
    {
    	$this->handler = new AvsHandler;
    }

    public function tearDown()
    {

    }

    public function testHandle()
    {
    	$responseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface');

        $avsResponseMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Avs\ResponseInterface');

        $avsResponseMock->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue('status'));

        $avsResponseMock->expects($this->once())
            ->method('getReturnCode')
            ->will($this->returnValue(123));

        $responseMock->expects($this->once())
            ->method('getAvs')
            ->will($this->returnValue($avsResponseMock));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentMock->expects($this->at(0))
            ->method('setAdditionalInformation')
            ->with('braspag_pagador_avs_status', 'status');

        $paymentMock->expects($this->at(1))
            ->method('setAdditionalInformation')
            ->with('braspag_pagador_avs_return_code', 123);

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
