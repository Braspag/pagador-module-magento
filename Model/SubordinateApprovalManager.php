<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model;

use Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory as OrderPaymentCollectionFactory;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\PaymentStatus\RequestInterface as PaymentStatusRequest;
use Webjump\Braspag\Pagador\Transaction\FacadeInterface as BraspagApi;

/**
 * Class SubordinateApprovalManager
 * @package Webjump\BraspagPagador\Model
 */
class SubordinateApprovalManager
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
     * @var Magento\Sales\Model\Order\Status
     */
    protected $orderStatusModel;

    /**
     * @var Webjump\BraspagPagador\Model\InvoiceManager
     */
    protected $invoiceManager;

    /**
     * @var Webjump\BraspagPagador\Model\CreditMemoManager
     */
    protected $creditMemoManager;

    /**
     * @var array
     */
    protected $types = [
        'braspag_pagador_creditcard' => 'creditCard',
        'braspag_pagador_creditcardtoken' => 'creditCard',
        'braspag_pagador_boleto' => 'boleto'
    ];

    /**
     * PaymentManager constructor.
     * @param \Magento\Sales\Model\Order\Status $orderStatusModel
     * @param InvoiceManager $invoiceManager
     * @param CreditMemoManager $creditMemoManager
     * @param BraspagApi $api
     * @param PaymentStatusRequest $paymentStatusRequest
     * @param OrderPaymentCollectionFactory $orderPaymentCollectionFactory
     */
    public function __construct(
        \Magento\Sales\Model\Order\Status $orderStatusModel,
        \Webjump\BraspagPagador\Model\InvoiceManager $invoiceManager,
        \Webjump\BraspagPagador\Model\CreditMemoManager $creditMemoManager,
        BraspagApi $api,
        PaymentStatusRequest $paymentStatusRequest,
        OrderPaymentCollectionFactory $orderPaymentCollectionFactory
    ){
        $this->setOrderStatusModel($orderStatusModel);
        $this->setInvoiceManager($invoiceManager);
        $this->setCreditMemoManager($creditMemoManager);
        $this->setApi($api);
        $this->setPaymentStatusRequest($paymentStatusRequest);
        $this->setOrderPaymentCollectionFactory($orderPaymentCollectionFactory);
    }

    /**
     * @return Order\Status
     */
    public function getOrderStatusModel()
    {
        return $this->orderStatusModel;
    }

    /**
     * @param Order\Status $orderStatusModel
     */
    public function setOrderStatusModel($orderStatusModel)
    {
        $this->orderStatusModel = $orderStatusModel;
    }

    /**
     * @return Webjump\BraspagPagador\Model\InvoiceManager
     */
    public function getInvoiceManager()
    {
        return $this->invoiceManager;
    }

    /**
     * @param Webjump\BraspagPagador\Model\InvoiceManager $invoiceManager
     */
    public function setInvoiceManager($invoiceManager)
    {
        $this->invoiceManager = $invoiceManager;
    }

    /**
     * @return Webjump\BraspagPagador\Model\CreditMemoManager
     */
    public function getCreditMemoManager()
    {
        return $this->creditMemoManager;
    }

    /**
     * @param Webjump\BraspagPagador\Model\CreditMemoManager $creditMemoManager
     */
    public function setCreditMemoManager($creditMemoManager)
    {
        $this->creditMemoManager = $creditMemoManager;
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
     * @return PaymentManager
     */
    public function setApi(BraspagApi $api): \Webjump\BraspagPagador\Model\SubordinateApprovalManager
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
     * @param Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\PaymentStatus\RequestInterface $paymentStatusRequest
     */
    public function setPaymentStatusRequest($paymentStatusRequest)
    {
        $this->paymentStatusRequest = $paymentStatusRequest;
    }

    /**
     * @return Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory
     */
    public function getOrderPaymentCollectionFactory()
    {
        return $this->orderPaymentCollectionFactory;
    }

    /**
     * @param Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory $orderPaymentCollectionFactory
     */
    public function setOrderPaymentCollectionFactory($orderPaymentCollectionFactory)
    {
        $this->orderPaymentCollectionFactory = $orderPaymentCollectionFactory;
    }

    /**
     * @param $braspagPaymentData
     * @param $magentoPaymentData
     * @return bool
     */
    public function registerAuthorizedPayment($braspagPaymentData, $magentoPaymentData)
    {
        if (!isset($braspagPaymentData->getPayment()['Amount'])) {
            return false;
        }

        $amount = $braspagPaymentData->getPayment()['Amount'] / 100;

        $magentoPaymentData->setIsTransactionPending(false);

        $magentoPaymentData->registerAuthorizationNotification($amount);

        $newState = \Magento\Sales\Model\Order::STATE_NEW;

        $defaultMethodOrderStatus = $magentoPaymentData->getMethodInstance()->getConfigData('order_status');

        if (!empty($defaultMethodOrderStatus)) {

            $magentoPaymentData->getOrder()
                ->setState($newState)
                ->setStatus($defaultMethodOrderStatus);

            if (!$magentoPaymentData->getOrder()->save()) {
                return false;
            }

            return true;
        }

        $newDefaultStatus = $this->getOrderStatusModel()->loadDefaultByState($newState);
        $magentoPaymentData->getOrder()
            ->setState($newState)
            ->setStatus($newDefaultStatus->getStatus());

        if (!$magentoPaymentData->getOrder()->save()) {
            return false;
        }

        return true;
    }

    /**,
     * @param $braspagPaymentData
     * @param $magentoPaymentData
     * @param bool $createInvoice
     * @return bool
     */
    public function registerCapturedPayment($braspagPaymentData, $magentoPaymentData, $createInvoice = true)
    {
        if (!isset($braspagPaymentData->getPayment()['Amount'])) {
            return false;
        }

        $amount = $braspagPaymentData->getPayment()['Amount'] / 100;

        $magentoPaymentData->setIsTransactionPending(false);

        if ($createInvoice
            && $this->getInvoiceManager()->createInvoice($magentoPaymentData->getOrder(), $amount)
        ) {
            $magentoPaymentData->registerCaptureNotification($amount, true);

            if (!$magentoPaymentData->getOrder()->save()) {
                return false;
            }

            return true;
        }

        $magentoPaymentData->getOrder()
            ->addStatusHistoryComment(
                __('Registered notification about captured amount of %1.',
                    $magentoPaymentData->getOrder()->getBaseCurrency()->formatTxt($amount)
                ).
                __('Transaction ID: "%1-capture"', $braspagPaymentData->getPaymentPaymentId())
            )
            ->setIsCustomerNotified(true)
            ->save();

        $processingState = \Magento\Sales\Model\Order::STATE_PROCESSING;

        $processingDefaultStatus = $this->getOrderStatusModel()->loadDefaultByState($processingState);

        $magentoPaymentData->getOrder()
            ->setState($processingState)
            ->setStatus($processingDefaultStatus->getStatus());

        if (!$magentoPaymentData->getOrder()->save()) {
            return false;
        }

        return true;
    }

    /**
     * @param $braspagPaymentData
     * @param $orderPayment
     * @param bool $cancelOrder
     * @return bool
     */
    public function registerCanceledPayment($braspagPaymentData, $magentoPaymentData, $cancelOrder = true)
    {
        $magentoPaymentData->setIsTransactionPending(false);
        $magentoPaymentData->save();

        $magentoPaymentData->registerVoidNotification();

        if ($cancelOrder) {
            $magentoPaymentData->getOrder()->cancel();
        }

        if (!$magentoPaymentData->getOrder()->save()) {
            return false;
        }

        return true;
    }

    /**
     * @param $braspagPaymentData
     * @param $magentoPaymentData
     * @param bool $createCreditMemo
     * @return bool
     */
    public function registerRefundedPayment($braspagPaymentData, $magentoPaymentData, $createCreditMemo = true)
    {
        if (!isset($braspagPaymentData->getPayment()['VoidedAmount'])) {
            return false;
        }

        $amount = $braspagPaymentData->getPayment()['VoidedAmount'] / 100;

        try {
            if($createCreditMemo) {
                $magentoPaymentData->registerRefundNotification($amount, true);
            }
        } catch (\Exception $e) {
            return false;
        }

        $magentoPaymentData->getOrder()
            ->addStatusHistoryComment(
                __('Registered notification about refunded amount of %1.',
                    $magentoPaymentData->getOrder()->getBaseCurrency()->formatTxt($amount)
                ).
                __('Transaction ID: "%1-refund"', $braspagPaymentData->getPaymentPaymentId())
            )
            ->setIsCustomerNotified(true)
            ->save();

        $closedState = \Magento\Sales\Model\Order::STATE_CLOSED;

        $closedDefaultStatus = $this->getOrderStatusModel()->loadDefaultByState($closedState);

        $magentoPaymentData->getOrder()
            ->setState($closedState)
            ->setStatus($closedDefaultStatus->getStatus());

        if (!$magentoPaymentData->getOrder()->save()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $paymentId
     * @return array|bool
     */
    public function getPaymentInfo(string $paymentId)
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

        return [
            'paymentType' => $type,
            'paymentInfo' => $paymentInfo,
            'orderPayment' => $orderPayment
        ];
    }
}
