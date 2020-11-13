<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\AntiFraudHandler;
use Webjump\Braspag\Pagador\Transaction\Resource\CreditCard\Send\Response;

class AntiFraudHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;
	private $responseMock;

    public function setUp()
    {
        $this->responseMock = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

    	$this->handler = new AntiFraudHandler(
    	    $this->responseMock
        );
    }

    public function tearDown()
    {

    }

    public function testHandle()
    {
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

        $antiFraudAnalisysMock = $this->createMock('Webjump\Braspag\Pagador\Transaction\Api\AntiFraud\ResponseInterface');

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('id'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue('status'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getCaptureOnLowRisk')
            ->will($this->returnValue('capture on low risk'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getVoidOnHighRisk')
            ->will($this->returnValue('void on high risk'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getFraudAnalysisReasonCode')
            ->will($this->returnValue('reason code'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataAddressInfoCode')
            ->will($this->returnValue('address info code'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataFactorCode')
            ->will($this->returnValue('factor code'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataScore')
            ->will($this->returnValue('score'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataBinCountry')
            ->will($this->returnValue('bin country'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataCardIssuer')
            ->will($this->returnValue('card isssue'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataCardScheme')
            ->will($this->returnValue('card scheme'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataHostSeverity')
            ->will($this->returnValue('host severuty'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataInternetInfoCode')
            ->will($this->returnValue('internet info code'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataIpRoutingMethod')
            ->will($this->returnValue('rounting method'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataScoreModelUsed')
            ->will($this->returnValue('mode used'));

        $antiFraudAnalisysMock->expects($this->once())
            ->method('getReplyDataCasePriority')
            ->will($this->returnValue('case priority'));

        $this->responseMock->expects($this->once())
            ->method('getPaymentFraudAnalysis')
            ->will($this->returnValue($antiFraudAnalisysMock));

    	$handlingSubject = ['payment' => $paymentDataObjectMock];
    	$response = ['response' => $this->responseMock];

    	$this->handler->handle($handlingSubject, $response);
    }
}
