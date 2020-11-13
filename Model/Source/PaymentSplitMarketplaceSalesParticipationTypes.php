<?php

namespace Webjump\BraspagPagador\Model\Source;

use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Option\ArrayInterface;

/**
 * CC Types
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

class PaymentSplitMarketplaceSalesParticipationTypes implements ArrayInterface
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    public function __construct(
        EventManager $eventManager
    ) {
        $this->eventManager = $eventManager;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => "0",
                'label' => __("-- Empty --"),
            ],
            [
            'value' => "1",
            'label' => __("Percent"),
            ],
            [
                'value' => "2",
                'label' => __("Fixed Value"),
            ]
        ];
    }
}
