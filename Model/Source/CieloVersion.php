<?php

namespace Webjump\BraspagPagador\Model\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Cielo Versions
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

class CieloVersion implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '1.5',
                'label' => __('Cielo 1.5'),
            ],
            [
                'value' => '3.0',
                'label' => __('Cielo 3.0')
            ]
        ];
    }
}
