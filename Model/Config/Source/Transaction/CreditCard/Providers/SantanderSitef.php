<?php

namespace Webjump\BraspagPagador\Model\Config\Source\Transaction\CreditCard\Providers;

class SantanderSitef implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
    	return [
    		['value' => 'Visa', 'label' => 'Visa'],
    		['value' => 'Master', 'label' => 'Master'],
    	];
    }
}