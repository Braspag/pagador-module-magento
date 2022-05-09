<?php

namespace Webjump\BraspagPagador\Model\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * CC Types
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

class PaymentSplitMdrTypes implements ArrayInterface
{
    const PAYMENT_SPLIT_MDR_TYPE_UNIQUE = 1;
    const PAYMENT_SPLIT_MDR_TYPE_MULTIPLE = 2;
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::PAYMENT_SPLIT_MDR_TYPE_UNIQUE,
                'label' => __('Unique'),
            ],
            [
                'value' => self::PAYMENT_SPLIT_MDR_TYPE_MULTIPLE,
                'label' => __('Multiple')
            ]
        ];
    }
}
