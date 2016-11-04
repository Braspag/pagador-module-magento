<?php

namespace Webjump\BraspagPagador\Model\Config\Source\Transaction\CreditCard\Providers;

class RedeCard implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
    	return [
    		['value' => 'Visa', 'label' => 'Visa'],
    		['value' => 'Master', 'label' => 'Master'],
            ['value' => 'Hipercard', 'label' => 'Hipercard'],
            ['value' => 'Hiper', 'label' => 'Hiper'],
            ['value' => 'Diners', 'label' => 'Diners'],
    	];
    }
}