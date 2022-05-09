<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\RequestBuilder;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\RequestInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\Request as AntiFraudRequest;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Avs\RequestInterface as AvsRequest;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\RequestFactory;
use Magento\Quote\Model\Quote\ItemFactory;
use Magento\Quote\Model\QuoteFactory;


class RequestBuilderTest extends \PHPUnit\Framework\TestCase
{
    private $requestBuilder;
    private $requestFactoryMock;
    private $configMock;
    private $antiFraudRequestMock;
    private $avsRequestMock;
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

        $this->avsRequestMock = $this->getMockBuilder(AvsRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentSplitRequestMock = $this->getMockBuilder(RequestPaymentSplitLibInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderAdapter = $this->createMock('Magento\Payment\Gateway\Data\OrderAdapterInterface');

        $this->requestBuilderMock = $this->getMockBuilder(RequestBuilder::class)
            ->setMethods(['setOrderAdapter', 'setPaymentData', 'setQuote'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemMock = $this->getMockBuilder(Magento\Sales\Api\Data\OrderItemInterface::class)
            ->setMethods(['create', 'load', 'getQuoteItemId', 'getQuoteId'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestBuilder = new RequestBuilder(
            $this->requestFactoryMock,
            $this->antiFraudRequestMock,
            $this->avsRequestMock,
            $this->paymentSplitRequestMock,
            $this->configMock,
            $this->quoteFactoryMock,
            $this->quoteItemFactoryMock
        );
    }

    public function testBuilder()
    {

        $itemsMock = [$this->itemMock, $this->itemMock, $this->itemMock];
        $quoteIdItemMock = 76534;
        $quoteIdMock = 22;

        $this->markTestIncomplete();
        $orderMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->getMock();

        $infoMock = $this->getMockBuilder('Magento\Payment\Model\InfoInterface')
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getOrder')
            ->will($this->returnValue($orderAdapter));

        $this->expects($this->once())
            ->method('getQuoteByOrderItem')
            ->with($this->orderAdapter);

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($infoMock));

        $this->antiFraudRequestMock->expects($this->once())
            ->method('setOrderAdapter');

        $this->antiFraudRequestMock->expects($this->once())
            ->method('setPaymentData')
            ->with($infoMock);

        $this->requestBuilderMock->expects($this->once())
            ->method('setOrderAdapter')
            ->willReturn($this->orderAdapter);

        $this->requestBuilderMock->expects($this->once())
            ->method('setPaymentData')
            ->with($infoMock);

        $this->orderAdapter->expects($this->once())
            ->method('getItems')
            ->willReturn($itemsMock);

        $this->itemMock->expects($this->any())
            ->method('getQuoteItemId')
            ->will($this->returnValue($quoteIdItemMock));

        $this->quoteItemFactoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($this->itemMock));

        $this->itemMock->expects($this->once())
            ->method('load')
            ->with($quoteIdItemMock)
            ->will($this->returnSelf());

        $this->itemMock->expects($this->once())
            ->method('getQuoteId')
            ->will($this->returnValue($quoteIdMock));

        $this->quoteFactoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnSelf());

        $this->quoteFactoryMock->expects($this->once())
            ->method('load')
            ->with($quoteIdMock)
            ->willReturn($this->returnSelf());

        $buildSubject = ['payment' => $paymentDataObjectMock];

        $result = $this->requestBuilder->build($buildSubject);

        static::assertSame($this->requestFactoryMock, $result);
    }

    public function testAntiFraudDisable()
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

        $this->orderAdapter->expects($this->once())
            ->method('getItems')
            ->willReturn($itemsMock);

        $this->itemMock->expects($this->any())
            ->method('getQuoteItemId')
            ->will($this->returnValue($quoteIdItemMock));

        $this->quoteItemFactoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($this->itemMock));

        $this->itemMock->expects($this->once())
            ->method('load')
            ->with($quoteIdItemMock)
            ->will($this->returnSelf());

        $this->itemMock->expects($this->once())
            ->method('getQuoteId')
            ->will($this->returnValue($quoteIdMock));

        $this->quoteFactoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnSelf());

        $this->quoteFactoryMock->expects($this->once())
            ->method('load')
            ->with($quoteIdMock)
            ->willReturn($this->returnSelf());

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($infoMock));

        $this->antiFraudRequestMock->expects($this->never())
            ->method('setOrderAdapter')
            ->willReturn($this->orderAdapter);


        $this->antiFraudRequestMock->expects($this->never())
            ->method('setPaymentData')
            ->with($infoMock);

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
}
