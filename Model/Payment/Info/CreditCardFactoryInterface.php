<?php

namespace Braspag\BraspagPagador\Model\Payment\Info;

use Magento\Sales\Api\Data\OrderInterface;

interface CreditCardFactoryInterface
{
    public function create(OrderInterface $order);
}