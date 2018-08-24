<?php

namespace Webjump\BraspagPagador\Model\Payment\Info;

use Magento\Sales\Api\Data\OrderInterface;


class CreditCardFactory implements CreditCardFactoryInterface
{
    /**
     * @param OrderInterface $order
     * @return CreditCard
     */
    public function create(OrderInterface $order)
    {
        return new CreditCard($order);
    }
}
