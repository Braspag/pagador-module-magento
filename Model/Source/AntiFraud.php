<?php

namespace Braspag\BraspagPagador\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class AntiFraud implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            [
                'value' => '',
                'label' => __('Select'),
            ],
            [
                'value' => 'ClearSale',
                'label' => __('ClearSale'),
            ],
            [
                'value' => 'Cybersource',
                'label' => __('Cybersource'),
            ]
        ];
    }
}