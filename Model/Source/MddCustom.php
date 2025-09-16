<?php

namespace Braspag\BraspagPagador\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class MddCustom implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            [
                'value' => '',
                'label' => __('Select'),
            ],
            [
                'value' => 'reserved_order_id',
                'label' => __('OrderId'),
            ],
            [
                'value' => 'subtotal',
                'label' => __('SubTotal'),
            ],
            [
                'value' => 'grand_total',
                'label' => __('Total'),
            ],
            [
                'value' => 'items_qty',
                'label' => __('Qtd Itens'),
            ],
            [
                'value' => 'discount_amount',
                'label' => __('Discount Amount'),
            ],
            [
                'value' => 'tax_amount',
                'label' => __('Tax Amount'),
            ],
            [
                'value' => 'coupon_code',
                'label' => __('Coupon Code'),
            ],
            [
                'value' => 'shipping_method',
                'label' => __('Shipping Method'),
            ],
            [
                'value' => 'shipping_description',
                'label' => __('Shipping Description'),
            ],
            [
                'value' => 'customer_taxvat',
                'label' => __('CPF/CNPJ'),
            ],
            [
                'value' => 'customer_email',
                'label' => __('Customer Email'),
            ]
        ];
    }
}