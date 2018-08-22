<?php

namespace Webjump\BraspagPagador\Model\Payment\Info;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;


class CreditCard
{
    protected $order;

    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * @param OrderInterface $order
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * @return OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return OrderPaymentInterface
     */
    public function getPayment()
    {
        if (! ($this->getOrder()->getPayment()) instanceof OrderPaymentInterface) {
            throw new \InvalidArgumentException;
        }

        return $this->getOrder()->getPayment();
    }

}
