<?php

namespace Webjump\BraspagPagador\Model\Source;

/**
 * CC Types
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

class PaymentSplitType extends \Magento\Payment\Model\Source\Cctype
{
    const PAYMENT_SPLIT_TYPE_TRANSACTIONAL = 'transactional';
    const PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST = 'transactional-post';
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::PAYMENT_SPLIT_TYPE_TRANSACTIONAL,
                'label' => __('Transactional'),
            ],
            [
                'value' => self::PAYMENT_SPLIT_TYPE_TRANSACTIONAL_POST,
                'label' => __('Transactional Post')
            ]
        ];
    }
}
