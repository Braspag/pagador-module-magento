<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\RequestBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\Request as AntiFraudRequest;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\AntiFraudConfigInterface;

class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $requestBuilder;
    private $requestMock;
    private $scopeConfigMock;
    private $antiFraudRequestMock;

    public function setUp()
    {
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)
            ->getMockForAbstractClass();

        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->getMockForAbstractClass();

        $this->antiFraudRequestMock = $this->getMockBuilder(AntiFraudRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock
            ->method('setAntiFraudRequest');

        $this->requestBuilder = new RequestBuilder(
            $this->requestMock,
            $this->antiFraudRequestMock,
            $this->scopeConfigMock
        );
    }

    public function testBuilder()
    {
        $this->markTestIncomplete();
        $orderMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapter = $this->getMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\InfoInterface')
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue($orderAdapter));

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($infoMock));


        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(AntiFraudConfigInterface::XML_PATH_ACTIVE)
            ->will($this->returnValue(true));

        $this->antiFraudRequestMock->expects($this->once())
            ->method('setOrderAdapter')
            ->with($orderMock);

        $this->antiFraudRequestMock->expects($this->once())
            ->method('setPaymentData')
            ->with($infoMock);

        $this->requestMock->expects($this->once())
            ->method('setOrderAdapter')
            ->with($orderMock);

        $this->requestMock->expects($this->once())
            ->method('setPaymentData')
            ->with($infoMock);

        $this->requestMock->expects($this->once())
            ->method('setAntiFraudRequest')
            ->with($this->antiFraudRequestMock);


        $buildSubject = ['payment' => $paymentDataObjectMock];

        $result = $this->requestBuilder->build($buildSubject);

        static::assertSame($this->requestMock, $result);
    }

    public function testAntiFraudDisable()
    {
        $orderMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $orderAdapter = $this->getMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\InfoInterface')
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue($orderAdapter));

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($infoMock));

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(AntiFraudConfigInterface::XML_PATH_ACTIVE)
            ->will($this->returnValue(false));

        $this->antiFraudRequestMock->expects($this->never())
            ->method('setOrderAdapter')
            ->with($orderMock);

        $this->antiFraudRequestMock->expects($this->never())
            ->method('setPaymentData')
            ->with($infoMock);

        $this->requestMock->expects($this->once())
            ->method('setOrderAdapter')
            ->with($orderMock);

        $this->requestMock->expects($this->once())
            ->method('setPaymentData')
            ->with($infoMock);

        $this->requestMock->expects($this->never())
            ->method('setAntiFraudRequest')
            ->with($this->antiFraudRequestMock);

        $buildSubject = ['payment' => $paymentDataObjectMock];

        $this->requestBuilder->build($buildSubject);
    }
}
