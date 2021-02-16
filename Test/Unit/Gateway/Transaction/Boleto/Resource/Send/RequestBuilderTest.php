<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Boleto\Resource\Send;

use Webjump\Braspag\Pagador\Transaction\Api\AntiFraud\RequestInterface as RequestAntiFraudLibInterface;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\RequestBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\RequestInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\Request;

class RequestBuilderTest extends \PHPUnit\Framework\TestCase
{
	private $requestBuilder;

	private $requestFactoryMock;
	private $requestMock;
	private $requestAntiFraudMock;
	private $requestPaymentSplitMock;
	private $configMock;

    public function setUp()
    {
    	$this->requestFactoryMock = $this->getMockBuilder('Webjump\BraspagPagador\Gateway\Transaction\Boleto\Resource\Send\RequestFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->createMock(
            Request::class
        );

    	$this->requestAntiFraudMock = $this->createMock(
            RequestAntiFraudLibInterface::class
    	);
    	$this->requestPaymentSplitMock = $this->createMock(
            RequestPaymentSplitLibInterface::class
    	);
    	$this->configMock = $this->createMock(
            ConfigInterface::class
    	);

    	$this->requestBuilder = new RequestBuilder(
            $this->requestFactoryMock,
            $this->requestAntiFraudMock,
            $this->requestPaymentSplitMock,
            $this->configMock
    	);
    }

    public function testBuilder()
    {
        $orderMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapter = $this->createMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\InfoInterface')
            ->getMock();

    	$paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
    		->setMethods(['getOrder', 'getPayment'])
    		->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($orderAdapter);

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->willReturn($infoMock);

    	$buildSubject = ['payment' => $paymentDataObjectMock];

        $this->requestFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->requestMock);

    	$result = $this->requestBuilder->build($buildSubject);

        static::assertSame($this->requestMock, $result);
    }
}
