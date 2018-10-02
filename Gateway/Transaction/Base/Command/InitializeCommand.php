<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Command;


use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Sales\Model\Order;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Sales\Model\Order\Payment;
use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider as CreditCardProvider;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Service\InvoiceService;


/**
 * Class CaptureCommand
 */
class InitializeCommand implements CommandInterface
{
    /** @var protected $config description */
    protected $config;

    protected $invoiceService;

    protected $eventManager;

    public function __construct(
        \Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface $config,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        $this->config = $config;
        $this->invoiceService = $invoiceService;
        $this->eventManager = $eventManager;
    }

    public function execute(array $commandSubject)
    {
        /** @var \Magento\Framework\DataObject $stateObject */
        $stateObject = $commandSubject['stateObject'];

        $paymentDO = SubjectReader::readPayment($commandSubject);

        $payment = $paymentDO->getPayment();
        if (!$payment instanceof Payment) {
            throw new \LogicException('Order Payment should be provided');
        }

        $baseTotalDue = $payment->getOrder()->getBaseTotalDue();
        $totalDue = $payment->getOrder()->getTotalDue();

        $payment->authorize(true, $baseTotalDue);
        $payment->setAmountAuthorized($totalDue);
        $payment->setBaseAmountAuthorized($payment->getOrder()->getBaseTotalDue());

        $stateObject->setData(OrderInterface::STATE, Order::STATE_PENDING_PAYMENT);

        if ($payment->getMethod() === CreditCardProvider::CODE) {
            $stateObject->setData(OrderInterface::STATE, Order::STATE_PROCESSING);
        }

        $stateObject->setData(OrderInterface::STATUS, $payment->getMethodInstance()->getConfigData('order_status'));

        if ($isFraudDetected = $payment->getIsFraudDetected()) {
            $stateObject->setData(OrderInterface::STATE, Order::STATE_PAYMENT_REVIEW);
            $stateObject->setData(OrderInterface::STATUS, $payment->getMethodInstance()->getConfigData('reject_order_status'));
        }

        if ($isTransactionPending = $payment->getIsTransactionPending()) {
            $stateObject->setData(OrderInterface::STATE, Order::STATE_PAYMENT_REVIEW);
            $stateObject->setData(OrderInterface::STATUS, $payment->getMethodInstance()->getConfigData('review_order_status'));
        }

        $stateObject->setData('is_notified', false);

        $invoice = false;
        if ($this->config->isAuthorizeAndCapture() && !$isFraudDetected && !$isTransactionPending) {
            $invoice = $this->invoiceService->prepareInvoice($payment->getOrder());
            $invoice->setRequestedCaptureCase(Invoice::CAPTURE_OFFLINE);
            $invoice->register();
            $invoice->save();
        }

        $this->eventManager->dispatch(
            'webjump_braspagador_creditcard_transaction_initialize',
            ['state_object' => $stateObject, 'payment' => $payment, 'invoice' => $invoice]
        );
    }
}
