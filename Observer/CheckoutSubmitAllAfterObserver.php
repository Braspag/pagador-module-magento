<?php

namespace Webjump\BraspagPagador\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Service\InvoiceService;
use Webjump\BraspagPagador\Model\SplitManager;

/**
 * Checkout Submit All After Observer
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2019 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class CheckoutSubmitAllAfterObserver implements ObserverInterface
{
    protected $config;

    protected $invoiceService;

    protected $orderManagement;

    protected $_transactionFactory;

    /**
     * @var
     */
    protected $objectFactory;

    /**
     * @var
     */
    protected $splitManager;

    public function __construct(
        \Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface $config,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Sales\Api\OrderManagementInterface $orderManagement,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Framework\DataObjectFactory $objectFactory,
        SplitManager $splitManager
    ) {
        $this->config = $config;
        $this->invoiceService = $invoiceService;
        $this->orderManagement = $orderManagement;
        $this->_transactionFactory = $transactionFactory;
        $this->objectFactory = $objectFactory;
        $this->splitManager = $splitManager;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        $payment = $order->getPayment();

        $doInvoice = false;

        try {

            if ($this->config->isAuthorizeAndCapture()
                && $order->getId()
                && preg_match("#braspag_pagador_creditcard#is", $payment->getMethodInstance()->getCode())
            ) {
                if ($payment->getIsFraudDetected()
                    && $payment->getMethodInstance()->getConfigData('reject_order_status') === 'canceled'
                ){
                    $this->orderManagement->cancel($order->getId());
                    return $this;
                }

                $doInvoice = true;
            }

            if ($order->getId()
                && preg_match("#braspag_pagador_debitcard#is", $payment->getMethodInstance()->getCode())
            ) {
                $braspagPaymentStatus = $payment->getAdditionalInformation('braspag_payment_status');

                if ($braspagPaymentStatus == '2') {
                    $doInvoice = true;
                }
            }

            if ($doInvoice) {
                if (!$payment->getIsFraudDetected()
                    && !$payment->getIsTransactionPending()
                    && $order->canInvoice()
                    && !$order->hasInvoices()
                ) {
                    $invoice = $this->invoiceService->prepareInvoice($order);
                    $invoice->setRequestedCaptureCase(Invoice::CAPTURE_OFFLINE);
                    $invoice->register();

                    $transaction = $this->_transactionFactory->create()
                        ->addObject($invoice)
                        ->addObject($invoice->getOrder());

                    $transaction->save();
                }
            }

            $dataSplitPayment = $payment->getAdditionalInformation('split_payments');

            if (!empty($dataSplitPayment)) {

                $dataSplitPaymentObject = $this->objectFactory->create();
                $dataSplitPaymentObject->addData($dataSplitPayment);

                $this->splitManager->createPaymentSplitByOrder($payment->getOrder(), $dataSplitPaymentObject);
            }

        } catch (\Exception $e) {
            $order->addStatusHistoryComment('Exception message: '.$e->getMessage(), false);
            $order->save();
            return null;
        }

        return $this;
    }
}
