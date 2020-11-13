<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\Base\Command;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Command\InitializeCommand;
use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider as CreditCardProvider;
use Magento\Framework\DataObject;

//use Webjump\Braspag\Pagador\Transaction\FacadeInterface as BraspagApi;
//use Webjump\Braspag\Pagador\Transaction\Api\Auth3Ds20\Token\RequestInterface;

class InitializeCommandTest extends \PHPUnit\Framework\TestCase
{
	private $command;
	private $eventManager;
	private $requestHandlerMock;
	private $paymentDataObjectMock;
	private $orderPaymentMock;
	private $orderMock;
	private $dataObjectMock;
	private $paymentMethodMock;

    public function setUp()
    {
    	$this->eventManager = $this->createMock(\Magento\Framework\Event\ManagerInterface::class);
    	$this->paymentDataObjectMock = $this->createMock(\Magento\Payment\Gateway\Data\PaymentDataObjectInterface::class);
    	$this->orderPaymentMock = $this->createMock(\Magento\Sales\Model\Order\Payment::class);
    	$this->orderMock = $this->createMock(\Magento\Sales\Model\Order::class);
        $this->dataObjectMock = $this->createMock(DataObject::class);
        $this->paymentMethodMock = $this->createMock(\Magento\Payment\Model\MethodInterface::class);

    	$this->command = new InitializeCommand(
    		$this->eventManager
    	);
    }

    public function tearDown()
    {

    }

    public function testExecuteInitCommandShouldFinishWithSuccess()
    {
        $this->orderMock->expects($this->exactly(2))
            ->method('getBaseTotalDue')
            ->willReturn(12.00);

        $this->orderMock->expects($this->once())
            ->method('getTotalDue')
            ->willReturn(12.00);

        $this->orderPaymentMock->expects($this->exactly(3))
            ->method('getOrder')
            ->willReturn($this->orderMock);

        $this->orderPaymentMock->expects($this->exactly(1))
            ->method('getMethod')
            ->willReturn(CreditCardProvider::CODE);

        $this->paymentMethodMock->expects($this->exactly(1))
            ->method('getConfigData')
            ->with('order_status')
            ->willReturn('2');

        $this->orderPaymentMock->expects($this->exactly(1))
            ->method('getMethodInstance')
            ->willReturn($this->paymentMethodMock);

        $this->orderPaymentMock->expects($this->exactly(1))
            ->method('getIsFraudDetected')
            ->willReturn(false);

        $this->paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->willReturn($this->orderPaymentMock);

        $this->dataObjectMock->expects($this->exactly(4))
            ->method('setData')
            ->willReturnSelf();

    	$buildObject = ['stateObject' => $this->dataObjectMock, 'payment' => $this->paymentDataObjectMock];

    	$result = $this->command->execute($buildObject);
    }

    /**
     * @expectedException \LogicException
     * @throws \LogicException
     */
    public function testExecuteInitCommandShouldThrowExceptionAfterInvalidPayment()
    {
        $this->paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->willReturn(false);

        $buildObject = ['stateObject' => $this->dataObjectMock, 'payment' => $this->paymentDataObjectMock];

        $result = $this->command->execute($buildObject);
    }

    public function testExecuteInitCommandShouldSetPaymentReviewWhenFraudIsDetected()
    {
        $this->orderMock->expects($this->exactly(2))
            ->method('getBaseTotalDue')
            ->willReturn(12.00);

        $this->orderMock->expects($this->once())
            ->method('getTotalDue')
            ->willReturn(12.00);

        $this->orderPaymentMock->expects($this->exactly(3))
            ->method('getOrder')
            ->willReturn($this->orderMock);

        $this->orderPaymentMock->expects($this->exactly(1))
            ->method('getMethod')
            ->willReturn(CreditCardProvider::CODE);

        $this->paymentMethodMock->expects($this->exactly(3))
            ->method('getConfigData')
            ->withConsecutive(['order_status', null], ['reject_order_status', null])
            ->willReturnOnConsecutiveCalls(['2', 'review']);

        $this->orderPaymentMock->expects($this->exactly(3))
            ->method('getMethodInstance')
            ->willReturn($this->paymentMethodMock);

        $this->orderPaymentMock->expects($this->exactly(1))
            ->method('getIsFraudDetected')
            ->willReturn(true);

        $this->orderPaymentMock->expects($this->exactly(1))
            ->method('getIsTransactionPending')
            ->willReturn(false);

        $this->paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->willReturn($this->orderPaymentMock);

        $this->dataObjectMock->expects($this->exactly(6))
            ->method('setData')
            ->willReturnSelf();

        $buildObject = ['stateObject' => $this->dataObjectMock, 'payment' => $this->paymentDataObjectMock];

        $result = $this->command->execute($buildObject);
    }

    public function testExecuteInitCommandShouldSetPaymentReviewWhenTransactionIsPending()
    {
        $this->orderMock->expects($this->exactly(2))
            ->method('getBaseTotalDue')
            ->willReturn(12.00);

        $this->orderMock->expects($this->once())
            ->method('getTotalDue')
            ->willReturn(12.00);

        $this->orderPaymentMock->expects($this->exactly(3))
            ->method('getOrder')
            ->willReturn($this->orderMock);

        $this->orderPaymentMock->expects($this->exactly(1))
            ->method('getMethod')
            ->willReturn(CreditCardProvider::CODE);

        $this->paymentMethodMock->expects($this->exactly(3))
            ->method('getConfigData')
            ->withConsecutive(['order_status', null], ['reject_order_status', null])
            ->willReturnOnConsecutiveCalls(['2', 'review']);

        $this->orderPaymentMock->expects($this->exactly(3))
            ->method('getMethodInstance')
            ->willReturn($this->paymentMethodMock);

        $this->orderPaymentMock->expects($this->exactly(1))
            ->method('getIsFraudDetected')
            ->willReturn(false);

        $this->orderPaymentMock->expects($this->exactly(1))
            ->method('getIsTransactionPending')
            ->willReturn(true);

        $this->paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->willReturn($this->orderPaymentMock);

        $this->dataObjectMock->expects($this->exactly(6))
            ->method('setData')
            ->willReturnSelf();

        $buildObject = ['stateObject' => $this->dataObjectMock, 'payment' => $this->paymentDataObjectMock];

        $result = $this->command->execute($buildObject);
    }
}
