<?php

namespace Webjump\BraspagPagador\Cron;

use \Psr\Log\LoggerInterface;
use Webjump\BraspagPagador\Model\SplitManager;
use Webjump\BraspagPagador\Gateway\Transaction\PaymentSplit\Command\TransactionPostCommand as SplitPaymentTransactionPostCommand;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface as SplitPaymentCreditCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config\ConfigInterface as SplitPaymentDebitCardConfig;
use Webjump\BraspagPagador\Gateway\Transaction\Boleto\Config\ConfigInterface as SplitPaymentBoletoConfig;
use Webjump\BraspagPagador\Model\Source\PaymentSplitType;
use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider as ConfigProviderCreditCard;
use Webjump\BraspagPagador\Model\Payment\Transaction\DebitCard\Ui\ConfigProvider as ConfigProviderDebitCard;
use Webjump\BraspagPagador\Model\Payment\Transaction\Boleto\Ui\ConfigProvider as ConfigProviderBoleto;

class PaymentSplitTransactionPost {

    protected $logger;
    protected $splitManager;
    protected $splitPaymentTransactionPostCommand;
    protected $configCreditCardInterface;
    protected $configDebitCardInterface;

    /**
     * PaymentSplitTransactionPost constructor.
     * @param LoggerInterface $logger
     * @param SplitPaymentTransactionPostCommand $splitPaymentTransactionPostCommand
     * @param SplitManager $splitManager
     * @param SplitPaymentCreditCardConfig $configCreditCardInterface
     * @param SplitPaymentDebitCardConfig $configDebitCardInterface
     * @param SplitPaymentBoletoConfig $configBoletoInterface
     */
    public function __construct(
        LoggerInterface $logger,
        SplitPaymentTransactionPostCommand $splitPaymentTransactionPostCommand,
        SplitManager $splitManager,
        SplitPaymentCreditCardConfig $configCreditCardInterface,
        SplitPaymentDebitCardConfig $configDebitCardInterface,
        SplitPaymentBoletoConfig $configBoletoInterface
    ){
        $this->logger = $logger;
        $this->splitPaymentTransactionPostCommand = $splitPaymentTransactionPostCommand;
        $this->splitManager = $splitManager;
        $this->configCreditCardInterface = $configCreditCardInterface;
        $this->configDebitCardInterface = $configDebitCardInterface;
        $this->configBoletoInterface = $configBoletoInterface;
    }

    /**
     * @throws \Magento\Payment\Gateway\Command\CommandException
     */
    public function execute()
    {
        $orders = [];

        if ($this->configCreditCardInterface->isPaymentSplitActive()
            && $this->configCreditCardInterface->getPaymentSplitTransactionalPostSendRequestAutomatically()
            && $this->configCreditCardInterface->getPaymentSplitType() == PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
        ) {
            $creditCardHours = intval($this->configCreditCardInterface
                ->getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours());

            $orders = $this->splitManager
                ->getTransactionPostOrdersToExecuteByHours($creditCardHours, ConfigProviderCreditCard::CODE);
        }

        if ($this->configDebitCardInterface->isPaymentSplitActive()
            && $this->configDebitCardInterface->getPaymentSplitTransactionalPostSendRequestAutomatically()
            && $this->configDebitCardInterface->getPaymentSplitType() == PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
        ) {
            $debitCardHours = intval($this->configDebitCardInterface
                ->getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours());

            $orders = $this->splitManager
                ->getTransactionPostOrdersToExecuteByHours($debitCardHours, ConfigProviderDebitCard::CODE);
        }

        if ($this->configBoletoInterface->isPaymentSplitActive()
            && $this->configBoletoInterface->getPaymentSplitTransactionalPostSendRequestAutomatically()
            && $this->configBoletoInterface->getPaymentSplitType() == PaymentSplitType::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST
        ) {
            $boletoHours = intval($this->configBoletoInterface
                ->getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours());

            $orders = $this->splitManager
                ->getTransactionPostOrdersToExecuteByHours($boletoHours, ConfigProviderBoleto::CODE);
        }

        $this->processOrders($orders);

        $this->logger->info('Cron PaymentSplitTransactionPost Executed');
    }

    /**
     * @param $orders
     * @return $this
     */
    protected function processOrders($orders)
    {
        foreach ($orders as $order) {
            try {
                $this->splitPaymentTransactionPostCommand->execute(['order' => $order, 'payment' => $order->getPayment()]);
            } catch (\Exception $e) {
                $order->addCommentToStatusHistory('Exception message: Split Payment Error - Transaction Post: '.$e->getMessage(), false);
                $order->save();
                continue;
            }
        }

        return $this;
    }
}
