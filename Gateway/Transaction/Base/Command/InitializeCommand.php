<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Command;

use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Sales\Model\Order;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Sales\Model\Order\Payment;
use Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui\ConfigProvider as CreditCardProvider;


/**
 * Class CaptureCommand
 */
class InitializeCommand implements CommandInterface
{
    protected $eventManager;

    public function __construct(
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
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

        if (empty($payment->getMethodInstance()->getConfigData('order_status'))) {
            $stateObject->setData(OrderInterface::STATE, Order::STATE_NEW);
        }

        if ($payment->getMethod() === CreditCardProvider::CODE
            && empty($payment->getMethodInstance()->getConfigData('order_status'))
        ) {
            $stateObject->setData(OrderInterface::STATE, Order::STATE_PROCESSING);
        }

        if (!empty($payment->getMethodInstance()->getConfigData('order_status'))) {

            $stateObject->setData(OrderInterface::STATE, Order::STATE_NEW);

            if (in_array($payment->getMethodInstance()->getConfigData('order_status'), ['fraud', 'processing'])) {
                $stateObject->setData(OrderInterface::STATE, Order::STATE_PROCESSING);
            }

            $stateObject->setData(OrderInterface::STATUS, $payment->getMethodInstance()->getConfigData('order_status'));
        }

        if ($isFraudDetected = $payment->getIsFraudDetected()) {
            if ($payment->getMethodInstance()->getConfigData('reject_order_status') != 'canceled') {
                $stateObject->setData(OrderInterface::STATE, Order::STATE_PAYMENT_REVIEW);
                $stateObject->setData(OrderInterface::STATUS, $payment->getMethodInstance()->getConfigData('reject_order_status'));
            }
        }

        if ($isTransactionPending = $payment->getIsTransactionPending()) {
            $stateObject->setData(OrderInterface::STATE, Order::STATE_PAYMENT_REVIEW);
            $stateObject->setData(OrderInterface::STATUS, $payment->getMethodInstance()->getConfigData('review_order_status'));
        }

        $stateObject->setData('is_notified', false);

        $this->eventManager->dispatch(
            'webjump_braspagador_creditcard_transaction_initialize',
            [   'state_object' => $stateObject,
                'payment' => $payment,
                'invoice' => false
            ]
        );
    }
}
