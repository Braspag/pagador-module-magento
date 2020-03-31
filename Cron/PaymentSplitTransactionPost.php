<?php

namespace Webjump\BraspagPagador\Cron;

use \Psr\Log\LoggerInterface;
use Webjump\BraspagPagador\Model\SplitManager;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Command\SplitPaymentTransactionPostCommand as SplitPaymentCreditCardTransactionPostCommand;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as SplitPaymentCreditCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Command\SplitPaymentTransactionPostCommand as SplitPaymentDebitCardTransactionPostCommand;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as SplitPaymentDebitCardConfig;
use Webjump\BraspagPagador\Model\Source\PaymentSplitType;
use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider as ConfigProviderCreditCard;
use Webjump\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui\ConfigProvider as ConfigProviderDebitCard;

class PaymentSplitTransactionPost {

    protected $logger;
    protected $splitManager;
    protected $splitPaymentCreditCardTransactionPostCommand;
    protected $splitPaymentDebitCardTransactionPostCommand;
    protected $configCreditCardInterface;
    protected $configDebitCardInterface;

    public function __construct(
        LoggerInterface $logger,
        SplitPaymentCreditCardTransactionPostCommand $splitPaymentCreditCardTransactionPostCommand,
        SplitPaymentDebitCardTransactionPostCommand $splitPaymentDebitCardTransactionPostCommand,
        SplitManager $splitManager,
        SplitPaymentCreditCardConfig $configCreditCardInterface,
        SplitPaymentDebitCardConfig $configDebitCardInterface
    ){
        $this->logger = $logger;
        $this->splitPaymentCreditCardTransactionPostCommand = $splitPaymentCreditCardTransactionPostCommand;
        $this->splitPaymentDebitCardTransactionPostCommand = $splitPaymentDebitCardTransactionPostCommand;
        $this->splitManager = $splitManager;
        $this->configCreditCardInterface = $configCreditCardInterface;
        $this->configDebitCardInterface = $configDebitCardInterface;
    }

    /**
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function execute()
    {
        if ($this->configCreditCardInterface->isPaymentSplitActive()
            && $this->configCreditCardInterface->getPaymentSplitTransactionalPostSendRequestAutomatically()
            && $this->configCreditCardInterface->getPaymentSplitType() == PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
        ) {
            $creditCardDays = intval($this->configCreditCardInterface
                ->getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXDays());

            $creditCardOrders = $this->splitManager
                ->getTransactionPostOrdersToExecuteByDays($creditCardDays, ConfigProviderCreditCard::CODE);

            $this->processOrders($creditCardOrders, $this->splitPaymentCreditCardTransactionPostCommand);
        }

        if ($this->configDebitCardInterface->isPaymentSplitActive()
            && $this->configDebitCardInterface->getPaymentSplitTransactionalPostSendRequestAutomatically()
            && $this->configDebitCardInterface->getPaymentSplitType() == PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
        ) {
            $debitCardDays = intval($this->configDebitCardInterface
                ->getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXDays());

            $debitCardOrders = $this->splitManager
                ->getTransactionPostOrdersToExecuteByDays($debitCardDays, ConfigProviderDebitCard::CODE);

            $this->processOrders($debitCardOrders, $this->splitPaymentDebitCardTransactionPostCommand);
        }

        $this->logger->info('Cron PaymentSplitTransactionPost Executed');
    }

    /**
     * @param $orders
     * @param $splitPaymentTransactionPostCommand
     * @return $this
     */
    protected function processOrders($orders, $splitPaymentTransactionPostCommand)
    {
        foreach ($orders as $order) {
            try {
                $splitPaymentTransactionPostCommand->execute(['order' => $order, 'payment' => $order->getPayment()]);
            } catch (\Exception $e) {
                $order->addCommentToStatusHistory('Exception message: Split Payment Error - Transaction Post: '.$e->getMessage(), false);
                $order->save();
                continue;
            }
        }

        return $this;
    }
}