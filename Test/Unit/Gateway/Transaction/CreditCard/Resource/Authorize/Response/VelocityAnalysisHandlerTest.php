<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\VelocityAnalysisHandler;
use Webjump\Braspag\Pagador\Transaction\Resource\CreditCard\Send\Response;

class VelocityAnalysisHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;
    private $responseMock;

    public function setUp()
    {
        $this->responseMock = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

    	$this->handler = new VelocityAnalysisHandler(
            $this->responseMock
        );
    }

    public function tearDown()
    {

    }

    public function testHandle()
    {
        $velocityReasonMock1 = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Velocity\Reasons\ResponseInterface');

        $velocityReasonMock1->expects($this->once())
            ->method('getRuleId')
            ->will($this->returnValue(1));

        $velocityReasonMock1->expects($this->once())
            ->method('getMessage')
            ->will($this->returnValue('volcity reason message'));

        $velocityReasonMock1->expects($this->once())
            ->method('getHitsQuantity')
            ->will($this->returnValue(100));

        $velocityReasonMock1->expects($this->once())
            ->method('getExpirationBlockTimeInSeconds')
            ->will($this->returnValue(200));

        $velocityMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Velocity\ResponseInterface');

        $velocityMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $velocityMock->expects($this->once())
            ->method('getResultMessage')
            ->will($this->returnValue('volcity result message'));

        $velocityMock->expects($this->once())
            ->method('getScore')
            ->will($this->returnValue(20));

        $velocityMock->expects($this->once())
            ->method('getRejectReasons')
            ->will($this->returnValue([
                $velocityReasonMock1
            ]));

        $this->responseMock->expects($this->once())
            ->method('getVelocityAnalysis')
            ->will($this->returnValue($velocityMock));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentMock->expects($this->at(0))
            ->method('setAdditionalInformation')
            ->with('braspag_pagador_velocity_id', 1);

        $paymentMock->expects($this->at(1))
            ->method('setAdditionalInformation')
            ->with('braspag_pagador_velocity_result_message', 'volcity result message');

        $paymentMock->expects($this->at(2))
            ->method('setAdditionalInformation')
            ->with('braspag_pagador_velocity_score', 20);

        $paymentMock->expects($this->at(3))
            ->method('setAdditionalInformation')
            ->with('braspag_pagador_velocity_reject_reasons', 'a:1:{i:0;a:4:{s:7:"rule_id";i:1;s:7:"message";s:22:"volcity reason message";s:13:"hits_quantity";i:100;s:32:"expiration_block_time_in_seconds";i:200;}}');

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

    	$handlingSubject = ['payment' => $paymentDataObjectMock];
    	$response = ['response' => $this->responseMock];

    	$this->handler->handle($handlingSubject, $response);
    }

    public function testHandleWithoutVelocity()
    {
        $this->responseMock->expects($this->once())
            ->method('getVelocityAnalysis')
            ->will($this->returnValue(null));

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
        $response = ['response' => $this->responseMock];

        $this->handler->handle($handlingSubject, $response);
    }
}
