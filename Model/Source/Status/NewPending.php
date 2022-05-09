<?php

namespace Webjump\BraspagPagador\Model\Source\Status;


use Magento\Sales\Model\Config\Source\Order\Status;

/**
 * @codeCoverageIgnore
 */
class NewPending extends Status
{
    /**
     * @var string[]
     */
    protected $_stateStatuses = [
        \Magento\Sales\Model\Order::STATE_NEW,
        \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT
    ];
}
