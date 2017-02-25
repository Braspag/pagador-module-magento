<?php

namespace Webjump\BraspagPagador\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Webjump\BraspagPagador\Api\RedirectPaymentInterface;
use Magento\Framework\DataObject;

/**
 *
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class RedirectPayment implements RedirectPaymentInterface
{
    protected $OrderRepository;

    public function __construct(
        OrderRepositoryInterface $OrderRepository
    ){
        $this->setOrderRepository($OrderRepository);
    }

    public function getLink($orderId)
    {
        $order = $this->getOrderRepository()->get((int) $orderId);
        $orderPayment = $order->getPayment();

        $additionalInformation = $orderPayment->getAdditionalInformation();
        if (!is_object($additionalInformation)) {
            $additionalInformation = new DataObject($additionalInformation ?: []);
        }

        return $additionalInformation->getRedirectUrl();
    }


    /**
     * @return OrderRepositoryInterface
     */
    protected function getOrderRepository()
    {
        return $this->OrderRepository;
    }

    protected function setOrderRepository($OrderRepository)
    {
        $this->OrderRepository = $OrderRepository;

        return $this;
    }
}