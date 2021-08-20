<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model;

use Magento\Framework\DB\Transaction;
use Magento\Framework\Event\Manager;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory as OrderPaymentCollectionFactory;
use Magento\Sales\Model\Service\CreditmemoService;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Model\Service\OrderService;
use Psr\Log\LoggerInterface;
use Webjump\BraspagPagador\Api\NotificationManagerInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\PaymentStatus\RequestInterface as PaymentStatusRequest;
use Webjump\Braspag\Pagador\Transaction\FacadeInterface as BraspagApi;

class NotificationManager implements NotificationManagerInterface
{
    /**
     * @var \Webjump\Braspag\Pagador\Transaction\FacadeInterface
     */
    protected $api;

    /**
     * @var Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\PaymentStatus\RequestInterface
     */
    protected $paymentStatusRequest;

    /**
     * @var Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory
     */
    protected $orderPaymentCollectionFactory;

    /**
     * @var \Magento\Sales\Model\Service\InvoiceService
     */
    protected $invoiceService;

    /**
     * @var \Magento\Framework\DB\Transaction
     */
    protected $transaction;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\InvoiceSender
     */
    protected $invoiceSender;

    /**
     * @var \Magento\Sales\Model\Service\OrderService
     */
    protected $orderService;

    /**
     * @var \Magento\Sales\Model\Order\CreditmemoFactory
     */
    protected $creditmemoFactory;

    /**
     * @var \Magento\Sales\Model\Service\CreditmemoService
     */
    protected $creditmemoService;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var string
     **/
    protected $config;

    /**
     * @var LoggerInterface
     **/
    protected $logger;

    /** @var array $types  */
    protected $types = [
        'braspag_pagador_creditcard' => 'creditCard',
        'braspag_pagador_creditcardtoken' => 'creditCard',
        'braspag_pagador_boleto' => 'boleto'
    ];

    public function __construct(
        BraspagApi $api,
        PaymentStatusRequest $paymentStatusRequest,
        OrderPaymentCollectionFactory $orderPaymentCollectionFactory,
        InvoiceService $invoiceService,
        Transaction $transaction,
        InvoiceSender $invoiceSender,
        OrderService $orderService,
        CreditmemoFactory $creditmemoFactory,
        CreditmemoService $creditmemoService,
        Manager $eventManager,
        \Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface $config,
        LoggerInterface $logger,
        \Magento\Sales\Model\Order\Status $orderStatusModel
    )
    {
        $this
            ->setApi($api)
            ->setPaymentStatusRequest($paymentStatusRequest)
            ->setOrderPaymentCollectionFactory($orderPaymentCollectionFactory)
            ->setInvoiceService($invoiceService)
            ->setTransaction($transaction)
            ->setInvoiceSender($invoiceSender)
            ->setOrderService($orderService)
            ->setCreditmemoFactory($creditmemoFactory)
            ->setCreditmemoService($creditmemoService);

        $this->config = $config;
        $this->logger = $logger;
        $this->eventManager = $eventManager;
        $this->orderStatusModel = $orderStatusModel;
    }

    /**
     * @inheritDoc
     */
    public function save($PaymentId, $ChangeType, $RecurrentPaymentId = '')
    {
        $this->logger->info("Payment Notification: $PaymentId $ChangeType $RecurrentPaymentId");

        // @todo more handlers of notification actions
        if ($ChangeType == self::NOTIFICATION_PAYMENT_STATUS_CHANGED) {
            return $this->paymentStatusChanged($PaymentId);
        }

        if ($ChangeType == self::NOTIFICATION_ANTI_FRAUD_STATUS_CHANGED) {
            return $this->antifraudStatusChanged($PaymentId);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function paymentStatusChanged(string $paymentId)
    {
        $orderPaymentCollection = $this->getOrderPaymentCollectionFactory()->create();
        $orderPayment = $orderPaymentCollection
            ->addAttributeToFilter('last_trans_id', ['like' => $paymentId.'%'])
            ->getFirstItem();

        if (!$orderPayment->getId()) {
            return false;
        }

        $request = $this->getPaymentStatusRequest();
        $request->setPaymentId($paymentId);
        $request->setStoreId($orderPayment->getOrder()->getStoreId());

        $type = 'boleto';
        $method = $orderPayment->getMethod();

        if (!empty($this->types[$method])) {
            $type = $this->types[$method];
        }

        $paymentInfo = $this->getApi()->checkPaymentStatus($request, $type);
        if (!$paymentInfo) {
            return false;
        }

        // @todo Status as constants in sdk
        // @todo Use command pattern
        $paymentStatus = $paymentInfo->getPaymentStatus();

        // 2 = caoture
        if ($type == 'boleto' && $paymentStatus == 2) {
            return $this->createInvoice($orderPayment->getOrder());
        }

        // 2 = capture
        if ($this->config->isCreateInvoiceOnNotificationCaptured() && $type == 'creditCard' && $paymentStatus == 2) {
            return $this->createInvoice($orderPayment->getOrder());
        }

        // 3 = Denied/10 = Voided/13 = Aborted
        if (in_array($paymentStatus, [3, 10, 13])) {
            return $this->cancelOrder($orderPayment->getOrder(), true);
        }

        // 11 = Refunded
        if ($paymentStatus == 11) {
            return $this->createCreditMemo($orderPayment->getOrder());
        }

        return true;
    }

    /**
     * @param string $paymentId
     * @return bool
     */
    public function antifraudStatusChanged(string $paymentId)
    {
        $orderPaymentCollection = $this->getOrderPaymentCollectionFactory()->create();
        $orderPayment = $orderPaymentCollection
            ->addAttributeToFilter('last_trans_id', ['like' => $paymentId.'%'])
            ->getFirstItem();

        if (!$orderPayment->getId()) {
            return false;
        }

        $request = $this->getPaymentStatusRequest();
        $request->setPaymentId($paymentId);
        $request->setStoreId($orderPayment->getOrder()->getStoreId());

        $type = 'creditCard';
        $method = $orderPayment->getMethod();

        if (!empty($this->types[$method])) {
            $type = $this->types[$method];
        }

        if ($type != 'creditCard') {
            return false;
        }

        $paymentInfo = $this->getApi()->checkPaymentStatus($request, $type);
        if (!$paymentInfo) {
            return false;
        }

        $paymentStatus = $paymentInfo->getPaymentStatus();

        $paymentFraudAnalysis = $paymentInfo->getPaymentFraudAnalysis();
        if (!$paymentFraudAnalysis) {
            return false;
        }

        if (in_array($paymentFraudAnalysis->getStatus(),
            [self::ANTIFRAUD_STATUS_REVIEW, self::ANTIFRAUD_STATUS_PENDENT])
        ) {
            return false;
        }

        if ($paymentFraudAnalysis->getStatus() == self::ANTIFRAUD_STATUS_ACCEPT) {

            if ($paymentStatus == 1) {

                $newState = \Magento\Sales\Model\Order::STATE_NEW;

                if (!empty($orderPayment->getMethodInstance()->getConfigData('order_status'))) {

                    $orderPayment->getOrder()->setState($newState)
                        ->setStatus($orderPayment->getMethodInstance()->getConfigData('order_status'));
                    $orderPayment->getOrder()->save();
                    return true;
                }

                $newDefaultStatus = $this->orderStatusModel->loadDefaultByState($newState);
                $orderPayment->getOrder()->setState($newState)->setStatus($newDefaultStatus->getStatus());
                $orderPayment->getOrder()->save();

                return true;
            }

            if ($paymentStatus == 2  && !$this->config->isCreateInvoiceOnNotificationCaptured()) {

                $processingState = \Magento\Sales\Model\Order::STATE_PROCESSING;

                $processingDefaultStatus = $this->orderStatusModel->loadDefaultByState($processingState);

                $orderPayment->getOrder()->setState($processingState)->setStatus($processingDefaultStatus->getStatus());
                $orderPayment->getOrder()->save();

                return true;
            }

            if ($paymentStatus == 2 && $this->config->isCreateInvoiceOnNotificationCaptured()) {
                $this->createInvoice($orderPayment->getOrder(), false);
                return true;
            }
        }

        if (in_array($paymentFraudAnalysis->getStatus(),
            [self::ANTIFRAUD_STATUS_UNFINISHED, self::ANTIFRAUD_STATUS_PROVIDERERROR, self::ANTIFRAUD_STATUS_REJECT])
        ) {
            return $this->cancelOrder($orderPayment->getOrder());
        }

        return false;
    }

    /**
     * @param Order $order
     * @param bool $online
     * @return bool
     * @throws \Exception
     */
    public function createInvoice(Order $order, $online = false)
    {
        if ($order->hasInvoices()) {
            return true;
        }

        $invoice = $this->getInvoiceService()->prepareInvoice($order);
        $invoice->setRequestedCaptureCase($online ? Invoice::CAPTURE_ONLINE : Invoice::CAPTURE_OFFLINE);
        $invoice->register();
        $invoice->save();

        $transactionSave = $this->getTransaction()
            ->addObject($invoice)
            ->addObject($invoice->getOrder());

        $transactionSave->save();
        $this->getInvoiceSender()->send($invoice);

        $order
            ->addStatusHistoryComment(
                __('Customer notified about invoice #%1.', $invoice->getIncrementId())
            )
            ->setIsCustomerNotified(true)
            ->save();

        $order->setState('processing')->setStatus('processing');
        $order->save();
        $this->eventManager->dispatch('webjump_braspagPagador_setstate_after', ['order' => $order]);

        return true;
    }

    /**
     * @param $order
     * @param bool $useService
     * @return bool
     */
    protected function cancelOrder($order, $useService = false)
    {
        if (!$useService) {
            $order->cancel();
            $order->save();

            return true;
        }

        return $this->getOrderService()->cancel($order->getId());
    }

    /**
     * @param $order
     * @return boolean
     */
    protected function createCreditMemo($order)
    {
        $creditmemo = $this->getCreditmemoFactory()->createByOrder($order);
        $this->getCreditmemoService()->refund($creditmemo, true);

        return true;
    }

    /**
     * @return BraspagApi
     */
    public function getApi(): BraspagApi
    {
        return $this->api;
    }

    /**
     * @param BraspagApi $api
     * @return NotificationManager
     */
    public function setApi(BraspagApi $api): NotificationManager
    {
        $this->api = $api;
        return $this;
    }

    /**
     * @return Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\PaymentStatus\RequestInterface
     */
    public function getPaymentStatusRequest()
    {
        return $this->paymentStatusRequest;
    }

    /**
     * @param $paymentStatusRequest
     * @return NotificationManager
     */
    public function setPaymentStatusRequest($paymentStatusRequest): NotificationManager
    {
        $this->paymentStatusRequest = $paymentStatusRequest;
        return $this;
    }

    /**
     * @return Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory
     */
    public function getOrderPaymentCollectionFactory()
    {
        return $this->orderPaymentCollectionFactory;
    }

    /**
     * @param Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory $orderCollectionFactory
     * @return $this
     */
    public function setOrderPaymentCollectionFactory($orderPaymentCollectionFactory)
    {
        $this->orderPaymentCollectionFactory = $orderPaymentCollectionFactory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvoiceService()
    {
        return $this->invoiceService;
    }

    /**
     * @param mixed $invoiceService
     * @return NotificationManager
     */
    public function setInvoiceService($invoiceService)
    {
        $this->invoiceService = $invoiceService;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     * @return NotificationManager
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvoiceSender()
    {
        return $this->invoiceSender;
    }

    /**
     * @param mixed $invoiceSender
     *
     * @return self
     */
    public function setInvoiceSender($invoiceSender)
    {
        $this->invoiceSender = $invoiceSender;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderService()
    {
        return $this->orderService;
    }

    /**
     * @param mixed $orderService
     *
     * @return self
     */
    public function setOrderService($orderService)
    {
        $this->orderService = $orderService;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreditmemoFactory()
    {
        return $this->creditmemoFactory;
    }

    /**
     * @param mixed $creditmemoFactory
     *
     * @return self
     */
    public function setCreditmemoFactory($creditmemoFactory)
    {
        $this->creditmemoFactory = $creditmemoFactory;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreditmemoService()
    {
        return $this->creditmemoService;
    }

    /**
     * @param mixed $creditmemoService
     *
     * @return self
     */
    public function setCreditmemoService($creditmemoService)
    {
        $this->creditmemoService = $creditmemoService;

        return $this;
    }
}
