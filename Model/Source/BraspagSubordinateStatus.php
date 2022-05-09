<?php

namespace Webjump\BraspagPagador\Model\Source;

/**
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

/**
 * Class BraspagSubordinateStatus
 * @package Webjump\BraspagPagador\Model\Source
 */
class BraspagSubordinateStatus implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        foreach ($this->getSubordinateStatuses() as $code => $name) {
            $options[] = ['value' => $code, 'label' => $name];
        }

        return $options;
    }

    /**
     * @return array
     */
    public function getSubordinateStatuses()
    {
        return [
            'UnderAnalysis' => __('UnderAnalysis'),
            'Approved' => __('Approved'),
            'ApprovedWithRestriction' => __('Approved With Restriction'),
            'Rejected' => __('Rejected')
        ];
    }
}
