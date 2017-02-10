<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\MDD;

use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\AntiFraud\MDD\GeneralRequestInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Model\InfoInterface;

interface AdapterGeneralInterface extends GeneralRequestInterface
{
    public function setOrderAdapter(OrderAdapterInterface $order);

    public function setPaymentData(InfoInterface $payment);

    public function getMobileDetect();

    public function getOrderAdapter();

    public function getPaymentData();

    public function getOrderCollectionFactory();
}