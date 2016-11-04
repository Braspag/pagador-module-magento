<?php

namespace Webjump\BraspagPagador\Model\Config\Source\Transaction\CreditCard\Providers;

class CieloSitef implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
    	return [
    		['value' => 'Visa', 'label' => 'Visa'],
    		['value' => 'Master', 'label' => 'Master'],
    		['value' => 'Amex', 'label' => 'Amex'],
    		['value' => 'Elo', 'label' => 'Elo'],
    		['value' => 'Aura', 'label' => 'Aura'],
    		['value' => 'Jcb', 'label' => 'Jcb'],
    		['value' => 'Diners', 'label' => 'Diners'],
    		['value' => 'Discover', 'label' => 'Discover'],
    	];
    }
}