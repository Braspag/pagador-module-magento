<?php

namespace Webjump\BraspagPagador\Model\Source\Status;


use Magento\Sales\Model\Config\Source\Order\Status;

/**
 * @codeCoverageIgnore
 */
class NewPendingProcessing extends Status
{
    /**
     * @var string[]
     */
    protected $_stateStatuses = [
        \Magento\Sales\Model\Order::STATE_NEW,
        \Magento\Sales\Model\Order::STATE_PROCESSING
    ];
}
