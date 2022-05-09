<?php
namespace Webjump\BraspagPagador\Test\Unit\Cron;

use Magento\Sales\Model\Order;
use \Psr\Log\LoggerInterface;
use Webjump\BraspagPagador\Cron\PaymentSplitTransactionPost;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as SplitPaymentBoletoConfig;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as SplitPaymentCreditCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as SplitPaymentDebitCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\TransactionPostCommand as SplitPaymentTransactionPostCommand;
use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider as ConfigProviderCreditCard;
use Webjump\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui\ConfigProvider as ConfigProviderDebitCard;
use Webjump\BraspagPagador\Model\Payment\Transaction\Boleto\Ui\ConfigProvider as ConfigProviderBoleto;
use Webjump\BraspagPagador\Model\SplitManager;

class PaymentSplitTransactionPostTest extends \PHPUnit\Framework\TestCase
{
    private $cron;
    private $logger;
    private $splitPaymentTransactionPostCommand;
    private $splitManager;
    private $configCreditCardInterface;
    private $configDebitCardInterface;
    private $configBoletoInterface;
    private $orderMock;
    private $orderPayment;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->splitPaymentTransactionPostCommand = $this->createMock(SplitPaymentTransactionPostCommand::class);
        $this->splitManager = $this->createMock(SplitManager::class);
        $this->configCreditCardInterface = $this->createMock(SplitPaymentCreditCardConfig::class);
        $this->configDebitCardInterface = $this->createMock(SplitPaymentDebitCardConfig::class);
        $this->configBoletoInterface = $this->createMock(SplitPaymentBoletoConfig::class);
        $this->orderMock = $this->createMock(Order::class);
        $this->orderPayment = $this->createMock(\Magento\Sales\Model\Order\Payment::class);

        $this->cron = $objectManager->getObject(
            PaymentSplitTransactionPost::class,
            [
                'logger' => $this->logger,
                'splitPaymentTransactionPostCommand' => $this->splitPaymentTransactionPostCommand,
                'splitManager' => $this->splitManager,
                'configCreditCardInterface' => $this->configCreditCardInterface,
                'configDebitCardInterface' => $this->configDebitCardInterface,
                'configBoletoInterface' => $this->configBoletoInterface
            ]
        );
    }

    public function testExecuteShouldProcessTransactionalPostsWhenCreditCardPaymentSplitIsEnabled()
    {
        $this->configCreditCardInterface->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configCreditCardInterface->expects($this->once())
            ->method('getPaymentSplitTransactionalPostSendRequestAutomatically')
            ->willReturn(true);

        $this->configCreditCardInterface->expects($this->once())
            ->method('getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours')
            ->willReturn(10);

        $this->configCreditCardInterface->expects($this->once())
            ->method('getPaymentSplitType')
            ->willReturn(\Webjump\BraspagPagador\Model\Source\PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST);

        $this->splitManager->expects($this->once())
            ->method('getTransactionPostOrdersToExecuteByHours')
            ->with(10, ConfigProviderCreditCard::CODE)
            ->willReturn([$this->orderMock]);

        $resultPage = $this->cron->execute();
    }

    public function testExecuteShouldProcessTransactionalPostsWhenDebitCardPaymentSplitIsEnabled()
    {
        $this->configDebitCardInterface->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configDebitCardInterface->expects($this->once())
            ->method('getPaymentSplitTransactionalPostSendRequestAutomatically')
            ->willReturn(true);

        $this->configDebitCardInterface->expects($this->once())
            ->method('getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours')
            ->willReturn(10);

        $this->configDebitCardInterface->expects($this->once())
            ->method('getPaymentSplitType')
            ->willReturn(\Webjump\BraspagPagador\Model\Source\PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST);

        $this->splitManager->expects($this->once())
            ->method('getTransactionPostOrdersToExecuteByHours')
            ->with(10, ConfigProviderDebitCard::CODE)
            ->willReturn([$this->orderMock]);

        $resultPage = $this->cron->execute();
    }

    public function testExecuteShouldProcessTransactionalPostsWhenBoletoPaymentSplitIsEnabled()
    {
        $this->configBoletoInterface->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configBoletoInterface->expects($this->once())
            ->method('getPaymentSplitTransactionalPostSendRequestAutomatically')
            ->willReturn(true);

        $this->configBoletoInterface->expects($this->once())
            ->method('getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours')
            ->willReturn(10);

        $this->configBoletoInterface->expects($this->once())
            ->method('getPaymentSplitType')
            ->willReturn(\Webjump\BraspagPagador\Model\Source\PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST);

        $this->splitManager->expects($this->once())
            ->method('getTransactionPostOrdersToExecuteByHours')
            ->with(10, ConfigProviderBoleto::CODE)
            ->willReturn([$this->orderMock]);

        $resultPage = $this->cron->execute();
    }

    public function testExecuteShouldNotProcessOrderAfterAnException()
    {
        $this->configBoletoInterface->expects($this->once())
            ->method('isPaymentSplitActive')
            ->willReturn(true);

        $this->configBoletoInterface->expects($this->once())
            ->method('getPaymentSplitTransactionalPostSendRequestAutomatically')
            ->willReturn(true);

        $this->configBoletoInterface->expects($this->once())
            ->method('getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours')
            ->willReturn(10);

        $this->configBoletoInterface->expects($this->once())
            ->method('getPaymentSplitType')
            ->willReturn(\Webjump\BraspagPagador\Model\Source\PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST);

        $this->orderMock->expects($this->exactly(1))
            ->method('getPayment')
            ->willReturn($this->orderPayment);

        $this->splitManager->expects($this->once())
            ->method('getTransactionPostOrdersToExecuteByHours')
            ->with(10, ConfigProviderBoleto::CODE)
            ->willReturn([$this->orderMock]);

        $this->splitPaymentTransactionPostCommand->expects($this->once())
            ->method('execute')
            ->with(['order' => $this->orderMock, 'payment' => $this->orderPayment])
            ->willThrowException(new \Exception('Error'));

        $resultPage = $this->cron->execute();
    }
}
