<?php

namespace Webjump\BraspagPagador\Model\Config\Source\Transaction\Billet;

class Provider implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
    	return [
    		['value' => 'Simulado', 'label' => 'Simulado'],
    	];
    }
}
