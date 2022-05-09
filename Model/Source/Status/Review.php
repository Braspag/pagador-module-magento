<?php

namespace Webjump\BraspagPagador\Model\Source\Status;


use Magento\Sales\Model\Config\Source\Order\Status;

/**
 * @codeCoverageIgnore
 */
class Review extends Status
{
    /**
     * @var string[]
     */
    protected $_stateStatuses = [
        \Magento\Sales\Model\Order::STATE_PAYMENT_REVIEW,
        \Magento\Sales\Model\Order::STATE_CANCELED
    ];
}
