<?php

namespace Webjump\BraspagPagador\Test\Unit\Observer;

use Magento\Framework\DataObject;
use Magento\Payment\Model\MethodInterface;
use Magento\Sales\Model\Order\Invoice;
use Webjump\BraspagPagador\Model\SplitManager;
use Webjump\BraspagPagador\Observer\CheckoutSubmitAllAfterObserver;
use Magento\Framework\Event\Observer;

class CheckoutSubmitAllAfterObserverTest extends \PHPUnit\Framework\TestCase
{
    protected $observer;
    protected $observerMock;
    protected $orderMock;
    protected $eventMock;
    protected $methodInterfaceMock;
    protected $configMock;
    protected $invoiceServiceMock;
    protected $invoiceMock;
    protected $orderManagementMock;
    protected $transactionFactoryMock;
    protected $transactionMock;
    protected $objectFactoryMock;
    protected $objectMock;
    protected $splitManagerMock;

    public function setUp()
    {
        $this->orderMock = $this->createMock(\Magento\Sales\Model\Order::class);

        $this->paymentMock = $this->createMock(\Magento\Sales\Model\Order\Payment::class);

        $this->orderMock->expects($this->once())
            ->method('getPayment')
            ->willReturn($this->paymentMock);

        $this->eventMock = $this->getMockBuilder('Magento\Framework\Event')
            ->setMethods(['getOrder'])
            ->getMock();

        $this->eventMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($this->orderMock);

        $this->observerMock = $this->createMock(Observer::class);
        $this->observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturn($this->eventMock);

        $this->configMock = $this->createMock(\Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface::class);
        $this->invoiceServiceMock = $this->createMock(\Magento\Sales\Model\Service\InvoiceService::class);
        $this->orderManagementMock = $this->createMock(\Magento\Sales\Api\OrderManagementInterface::class);
        $this->transactionFactoryMock = $this->getMockBuilder('Magento\Framework\DB\TransactionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->objectFactoryMock = $this->getMockBuilder('Magento\Framework\DataObjectFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->objectMock = $this->createMock(DataObject::class);

        $this->splitManagerMock = $this->createMock(\Webjump\BraspagPagador\Model\SplitManager::class);

        $this->methodInterfaceMock = $this->getMockForAbstractClass(
            MethodInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['getInfoInstance', 'getCode', 'getConfigData']
        );

        $this->transactionMock = $this->createMock(\Magento\Framework\DB\Transaction::class);

        $this->invoiceMock = $this->getMockBuilder('Magento\Sales\Model\Order\Invoice')
            ->disableOriginalConstructor()
            ->setMethods(['setRequestedCaptureCase', 'register', 'getOrder'])
            ->getMock();

        $this->observer = new CheckoutSubmitAllAfterObserver(
            $this->configMock,
            $this->invoiceServiceMock,
            $this->orderManagementMock,
            $this->transactionFactoryMock,
            $this->objectFactoryMock,
            $this->splitManagerMock
        );
    }

    public function testExecuteShouldReturnSelfAfterSuccess()
    {
        $return = $this->observer->execute($this->observerMock);

        $this->assertSame($this->observer, $return);
    }

    public function testExecuteShouldCancelOrderAndReturnSelfAfterCreditcardFraudDetected()
    {
        $this->configMock->expects($this->once())
            ->method('isAuthorizeAndCapture')
            ->will($this->returnValue(true));

        $this->orderMock->expects($this->exactly(2))
            ->method('getId')
            ->will($this->returnValue('123'));

        $this->methodInterfaceMock->expects($this->once())
            ->method('getCode')
            ->will($this->returnValue('braspag_pagador_creditcard'));

        $this->methodInterfaceMock->expects($this->once())
            ->method('getConfigData')
            ->with('reject_order_status')
            ->willReturn('canceled');

        $this->paymentMock->expects($this->exactly(2))
            ->method('getMethodInstance')
            ->willReturn($this->methodInterfaceMock);

        $this->paymentMock->expects($this->once())
            ->method('getIsFraudDetected')
            ->will($this->returnValue(true));

        $this->orderManagementMock->expects($this->once())
            ->method('cancel')
            ->willReturnSelf();

        $return = $this->observer->execute($this->observerMock);

        $this->assertSame($this->observer, $return);
    }

    public function testExecuteShouldInvoiceOrderAndReturnSelfAfterCreditcardPaymentSuccess()
    {
        $this->configMock->expects($this->once())
            ->method('isAuthorizeAndCapture')
            ->will($this->returnValue(true));

        $this->orderMock->expects($this->exactly(1))
            ->method('getId')
            ->will($this->returnValue('123'));

        $this->methodInterfaceMock->expects($this->once())
            ->method('getCode')
            ->will($this->returnValue('braspag_pagador_creditcard'));

        $this->paymentMock->expects($this->exactly(1))
            ->method('getMethodInstance')
            ->willReturn($this->methodInterfaceMock);

        $this->paymentMock->expects($this->exactly(2))
            ->method('getIsFraudDetected')
            ->willReturn(false);

        $this->paymentMock->expects($this->once())
            ->method('getIsTransactionPending')
            ->willReturn(false);

        $this->orderMock->expects($this->once())
            ->method('canInvoice')
            ->willReturn(true);

        $this->orderMock->expects($this->once())
            ->method('hasInvoices')
            ->willReturn(false);

        $this->invoiceMock->expects($this->once())
            ->method('setRequestedCaptureCase')
            ->with(Invoice::CAPTURE_OFFLINE)
            ->willReturnSelf();

        $this->invoiceMock->expects($this->once())
            ->method('register')
            ->willReturnSelf();

        $this->invoiceMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($this->orderMock);

        $this->invoiceServiceMock->expects($this->once())
            ->method('prepareInvoice')
            ->with($this->orderMock)
            ->willReturn($this->invoiceMock);

        $this->transactionMock->expects($this->exactly(2))
            ->method('addObject')
            ->withConsecutive($this->invoiceMock, $this->orderMock)
            ->willReturnSelf();

        $this->transactionMock->expects($this->once())
            ->method('save')
            ->willReturnSelf();

        $this->transactionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->transactionMock);

        $return = $this->observer->execute($this->observerMock);

        $this->assertSame($this->observer, $return);
    }

    public function testExecuteShouldInvoiceOrderAndSaveSplitDataAndReturnSelfAfterCreditcardPaymentSuccess()
    {
        $this->configMock->expects($this->once())
            ->method('isAuthorizeAndCapture')
            ->will($this->returnValue(true));

        $this->orderMock->expects($this->exactly(1))
            ->method('getId')
            ->will($this->returnValue('123'));

        $this->methodInterfaceMock->expects($this->once())
            ->method('getCode')
            ->will($this->returnValue('braspag_pagador_creditcard'));

        $this->paymentMock->expects($this->exactly(1))
            ->method('getMethodInstance')
            ->willReturn($this->methodInterfaceMock);

        $this->paymentMock->expects($this->exactly(2))
            ->method('getIsFraudDetected')
            ->willReturn(false);

        $this->paymentMock->expects($this->once())
            ->method('getIsTransactionPending')
            ->willReturn(false);

        $this->orderMock->expects($this->once())
            ->method('canInvoice')
            ->willReturn(true);

        $this->orderMock->expects($this->once())
            ->method('hasInvoices')
            ->willReturn(false);

        $this->invoiceMock->expects($this->once())
            ->method('setRequestedCaptureCase')
            ->with(Invoice::CAPTURE_OFFLINE)
            ->willReturnSelf();

        $this->invoiceMock->expects($this->once())
            ->method('register')
            ->willReturnSelf();

        $this->invoiceMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($this->orderMock);

        $this->invoiceServiceMock->expects($this->once())
            ->method('prepareInvoice')
            ->with($this->orderMock)
            ->willReturn($this->invoiceMock);

        $this->transactionMock->expects($this->exactly(2))
            ->method('addObject')
            ->withConsecutive($this->invoiceMock, $this->orderMock)
            ->willReturnSelf();

        $this->transactionMock->expects($this->once())
            ->method('save')
            ->willReturnSelf();

        $this->transactionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->transactionMock);

        $dataSplit = ['2323','1212'];

        $this->paymentMock->expects($this->exactly(1))
            ->method('getAdditionalInformation')
            ->with('split_payments')
            ->willReturn($dataSplit);

        $this->objectMock->expects($this->exactly(1))
            ->method('addData')
            ->with($dataSplit)
            ->willReturnSelf();

        $this->objectFactoryMock->expects($this->exactly(1))
            ->method('create')
            ->willReturn($this->objectMock);

        $this->paymentMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($this->orderMock);

        $this->splitManagerMock->expects($this->exactly(1))
            ->method('createPaymentSplitByOrder')
            ->with($this->orderMock, $this->objectMock)
            ->willReturnSelf();

        $return = $this->observer->execute($this->observerMock);

        $this->assertSame($this->observer, $return);
    }

    public function testExecuteShouldReturnNullAfterAnException()
    {
        $this->configMock->expects($this->once())
            ->method('isAuthorizeAndCapture')
            ->will($this->returnValue(true));

        $this->orderMock->expects($this->exactly(1))
            ->method('getId')
            ->will($this->returnValue('123'));

        $this->methodInterfaceMock->expects($this->once())
            ->method('getCode')
            ->will($this->returnValue('braspag_pagador_creditcard'));

        $this->paymentMock->expects($this->exactly(1))
            ->method('getMethodInstance')
            ->willReturn($this->methodInterfaceMock);

        $this->paymentMock->expects($this->exactly(2))
            ->method('getIsFraudDetected')
            ->willReturn(false);

        $this->paymentMock->expects($this->once())
            ->method('getIsTransactionPending')
            ->willReturn(false);

        $this->orderMock->expects($this->once())
            ->method('canInvoice')
            ->willReturn(true);

        $this->orderMock->expects($this->once())
            ->method('hasInvoices')
            ->willReturn(false);

        $this->invoiceMock->expects($this->once())
            ->method('setRequestedCaptureCase')
            ->with(Invoice::CAPTURE_OFFLINE)
            ->willReturnSelf();

        $this->invoiceMock->expects($this->once())
            ->method('register')
            ->willReturnSelf();

        $this->invoiceMock->expects($this->once())
            ->method('getOrder')
            ->willReturn($this->orderMock);

        $this->invoiceServiceMock->expects($this->once())
            ->method('prepareInvoice')
            ->with($this->orderMock)
            ->willReturn($this->invoiceMock);

        $this->transactionMock->expects($this->exactly(2))
            ->method('addObject')
            ->withConsecutive($this->invoiceMock, $this->orderMock)
            ->willReturnSelf();

        $this->transactionMock->expects($this->once())
            ->method('save')
            ->willReturnSelf();

        $this->transactionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->transactionMock);

        $dataSplit = ['2323','1212'];

        $this->paymentMock->expects($this->exactly(1))
            ->method('getAdditionalInformation')
            ->with('split_payments')
            ->willReturn($dataSplit);

        $this->objectMock->expects($this->exactly(1))
            ->method('addData')
            ->with($dataSplit)
            ->willReturnSelf();

        $this->objectFactoryMock->expects($this->exactly(1))
            ->method('create')
            ->willReturn($this->objectMock);

        $this->paymentMock->expects($this->once())
            ->method('getOrder')
            ->willThrowException(new \Exception('Error'));

        $return = $this->observer->execute($this->observerMock);

        $this->assertNull($return);
    }
}
