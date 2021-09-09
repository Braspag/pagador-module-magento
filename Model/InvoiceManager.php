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
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Service\InvoiceService;
use Psr\Log\LoggerInterface;

use Webjump\BraspagPagador\Model\NotificationManager;

/**
 * Class InvoiceManager
 * @package Webjump\BraspagPagador\Model
 */
class InvoiceManager
{
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
     * @var Magento\Sales\Model\Order\Status
     */
    protected $orderStatusModel;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * InvoiceManager constructor.
     * @param InvoiceService $invoiceService
     * @param Transaction $transaction
     * @param InvoiceSender $invoiceSender
     * @param Manager $eventManager
     * @param Order\Status $orderStatusModel
     */
    public function __construct(
        InvoiceService $invoiceService,
        Transaction $transaction,
        InvoiceSender $invoiceSender,
        Manager $eventManager,
        \Magento\Sales\Model\Order\Status $orderStatusModel
    ){
        $this->setInvoiceService($invoiceService);
        $this->setTransaction($transaction);
        $this->setInvoiceSender($invoiceSender);
        $this->setEventManager($eventManager);
        $this->setOrderStatusModel($orderStatusModel);
    }

    /**
     * @return mixed
     */
    public function getInvoiceService()
    {
        return $this->invoiceService;
    }

    /**
     * @param $invoiceService
     * @return $this
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
     * @param $transaction
     * @return $this
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
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param EventManager $eventManager
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return Magento\Sales\Model\Order\Status
     */
    public function getOrderStatusModel()
    {
        return $this->orderStatusModel;
    }

    /**
     * @param Magento\Sales\Model\Order\Status $orderStatusModel
     */
    public function setOrderStatusModel($orderStatusModel)
    {
        $this->orderStatusModel = $orderStatusModel;
    }

    /**
     * @param Order $order
     * @param $amount
     * @return bool
     * @throws \Exception
     */
    public function createInvoice(Order $order, $amount)
    {
        if (!$order->canInvoice()) {
            return true;
        }

        $invoice = $this->getInvoiceService()->prepareInvoice($order);
        $invoice->setRequestedCaptureCase(Invoice::CAPTURE_OFFLINE);
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

        $processingState = \Magento\Sales\Model\Order::STATE_PROCESSING;
        $processingDefaultStatus = $this->getOrderStatusModel()->loadDefaultByState($processingState);

        $order
            ->setState($processingState)
            ->setStatus($processingDefaultStatus->getStatus());

        $order->save();
        $this->getEventManager()->dispatch('webjump_braspagPagador_setstate_after', ['order' => $order]);

        return true;
    }
}
