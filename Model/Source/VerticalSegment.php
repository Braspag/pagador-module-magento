<?php

namespace Webjump\BraspagPagador\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class VerticalSegment implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            [
                'value' => 'Retail',
                'label' => __('Retail'),
            ],
            [
                'value' => 'Cosmeticos',
                'label' => __('Cosmeticos')
            ],
            [
                'value' => 'Joalheria',
                'label' => __('Joalheria')
            ],
            [
                'value' => 'DigitalGoods',
                'label' => __('Digital Goods')
            ],
            [
                'value' => 'Servicos',
                'label' => __('Servicos')
            ],
            [
                'value' => 'Turismo',
                'label' => __('Turismo')
            ],
            [
                'value' => 'Generico',
                'label' => __('Generico')
            ]
        ];
    }
}
