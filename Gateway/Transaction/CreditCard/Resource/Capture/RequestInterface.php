<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture;

use Magento\Payment\Gateway\Data\OrderAdapterInterface;

interface RequestInterface
{
    public function setOrderAdapter(OrderAdapterInterface $order);

    public function setPaymentId($paymentId);
}
