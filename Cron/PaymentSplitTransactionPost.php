<?php

namespace Webjump\BraspagPagador\Cron;

use \Psr\Log\LoggerInterface;
use Webjump\BraspagPagador\Model\SplitManager;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Command\SplitPaymentTransactionPostCommand;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface;
use Webjump\BraspagPagador\Model\Source\PaymentSplitType;

class PaymentSplitTransactionPost {

    protected $logger;
    protected $splitManager;
    protected $splitPaymentTransactionPostCommand;
    protected $configInterface;

    public function __construct(
        LoggerInterface $logger,
        SplitPaymentTransactionPostCommand $splitPaymentTransactionPostCommand,
        SplitManager $splitManager,
        ConfigInterface $configInterface
    ){
        $this->logger = $logger;
        $this->splitPaymentTransactionPostCommand = $splitPaymentTransactionPostCommand;
        $this->splitManager = $splitManager;
        $this->configInterface = $configInterface;
    }

    /**
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function execute()
    {
        if (!$this->configInterface->hasPaymentSplit()) {
            return $this;
        }

        $days = intval($this->configInterface->getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXDays());

        $orders = $this->splitManager->getTransactionPostOrdersToExecuteByDays($days);

        foreach ($orders as $order) {
            $this->splitPaymentTransactionPostCommand->execute(['order' => $order, 'payment' => $order->getPayment()]);
        }

        $this->logger->info('Cron PaymentSplitTransactionPost Executed');
    }
}