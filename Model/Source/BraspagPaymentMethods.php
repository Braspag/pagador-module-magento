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
 * Class BraspagPaymentMethods
 * @package Webjump\BraspagPagador\Model\Source
 */
class BraspagPaymentMethods implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        foreach ($this->getPaymentMethods() as $code => $name) {
            $options[] = ['value' => $code, 'label' => $name];
        }

        return $options;
    }

    /**
     * @return array
     */
    public function getPaymentMethods()
    {
        return [
            'braspag_pagador_creditcard' => __('Braspag Pagador Credit Card'),
            'braspag_pagador_creditcardtoken' => __('Braspag Pagador Credit Card JustClick (Token)'),
            'braspag_pagador_boleto' => __('Braspag Pagador Boleto'),
            'braspag_pagador_debitcard' => __('Braspag Pagador Debit Card')
        ];
    }


}
