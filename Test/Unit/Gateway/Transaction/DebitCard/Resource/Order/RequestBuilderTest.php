<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\DebitCard\Resource\Order;

use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\RequestBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\AntiFraud\Request as AntiFraudRequest;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Resource\Order\RequestFactory;
use Magento\Quote\Model\Quote\ItemFactory;
use Magento\Quote\Model\QuoteFactory;


class RequestBuilderTest extends \PHPUnit\Framework\TestCase
{
    private $requestBuilder;
    private $requestFactoryMock;
    private $configMock;
    private $antiFraudRequestMock;
    private $paymentSplitRequestMock;
    private $quoteFactoryMock;
    private $quoteItemFactoryMock;
    private $orderAdapter;
    private $itemMock;
    private $requestBuilderMock;

    public function setUp()
    {
        $this->requestFactoryMock = $this->getMockBuilder(RequestFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(ConfigInterface::class)
            ->getMockForAbstractClass();

        $this->antiFraudRequestMock = $this->getMockBuilder(AntiFraudRequest::class)
            ->setMethods(['setOrderAdapter', 'setPaymentData'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteItemFactoryMock = $this->getMockBuilder(ItemFactory::class)
            ->setMethods(['create', 'load'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteFactoryMock = $this->getMockBuilder(QuoteFactory::class)
            ->setMethods(['create', 'load'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentSplitRequestMock = $this->getMockBuilder(RequestPaymentSplitLibInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['setConfig', 'getSplits'])
            ->getMock();

        $this->orderAdapter = $this->createMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

        $this->requestBuilderMock = $this->getMockBuilder(RequestBuilder::class)
            ->setMethods(['setOrderAdapter', 'setPaymentData', 'setQuote', 'setPaymentSplitRequest'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemMock = $this->getMockBuilder(Magento\Sales\Api\Data\OrderItemInterface::class)
            ->setMethods(['create', 'load', 'getQuoteItemId', 'getQuoteId'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestBuilder = new RequestBuilder(
            $this->requestFactoryMock,
            $this->antiFraudRequestMock,
            $this->paymentSplitRequestMock,
            $this->configMock,
            $this->quoteFactoryMock,
            $this->quoteItemFactoryMock
        );
    }

    public function testBuildShouldBuildWithSuccess()
    {
        $itemsMock = [$this->itemMock, $this->itemMock, $this->itemMock];
        $quoteIdItemMock = 76534;
        $quoteIdMock = 22;

        $orderMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\InfoInterface')
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue($this->orderAdapter));

        $this->configMock->expects($this->once())
            ->method('hasAntiFraud')
            ->willReturn(true);

        $this->itemMock->expects($this->any())
            ->method('getQuoteItemId')
            ->will($this->returnValue($quoteIdItemMock));

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($infoMock));

        $this->requestFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->requestBuilderMock);

        $this->requestBuilderMock->expects($this->once())
            ->method('setOrderAdapter')
            ->willReturn($this->orderAdapter);

        $this->requestBuilderMock->expects($this->once())
            ->method('setPaymentData')
            ->with($infoMock);

        $buildSubject = ['payment' => $paymentDataObjectMock];

        $this->requestBuilder->build($buildSubject);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Payment data object should be provided
     */
    public function testBuildShouldThrowExceptionAfterInvalidPayment()
    {
        $itemsMock = [$this->itemMock, $this->itemMock, $this->itemMock];
        $quoteIdItemMock = 76534;
        $quoteIdMock = 22;

        $orderMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\InfoInterface')
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getPayment'])
            ->getMock();

        $this->itemMock->expects($this->any())
            ->method('getQuoteItemId')
            ->will($this->returnValue($quoteIdItemMock));

        $buildSubject = ['payment' => false];

        $this->requestBuilder->build($buildSubject);
    }

    public function testBuildShouldBuildWithSuccessWhenPaymentSplitIsEnabledAndTypeIsTransactional()
    {
        $itemsMock = [$this->itemMock, $this->itemMock, $this->itemMock];
        $quoteIdItemMock = 76534;
        $quoteIdMock = 22;

        $orderMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\InfoInterface')
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue($this->orderAdapter));

        $this->configMock->expects($this->once())
            ->method('hasAntiFraud')
            ->willReturn(false);

        $this->configMock->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configMock->expects($this->once())
            ->method('getPaymentSplitType')
            ->willReturn('transactional');

        $this->itemMock->expects($this->any())
            ->method('getQuoteItemId')
            ->will($this->returnValue($quoteIdItemMock));

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($infoMock));

        $this->requestFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->requestBuilderMock);

        $this->requestBuilderMock->expects($this->once())
            ->method('setOrderAdapter')
            ->willReturn($this->orderAdapter);

        $this->requestBuilderMock->expects($this->once())
            ->method('setPaymentData')
            ->with($infoMock);

        $this->paymentSplitRequestMock->expects($this->once())
            ->method('setConfig')
            ->with($this->configMock)
            ->willReturnSelf();

        $this->requestBuilderMock->expects($this->once())
            ->method('setPaymentSplitRequest')
            ->with($this->paymentSplitRequestMock)
            ->willReturnSelf();

        $buildSubject = ['payment' => $paymentDataObjectMock];

        $this->requestBuilder->build($buildSubject);
    }

}
