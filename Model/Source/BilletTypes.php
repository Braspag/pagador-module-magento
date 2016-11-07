<?php

namespace Webjump\BraspagPagador\Model\Source;

class BilletTypes implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
    	return [
    		['value' => 'Simulado', 'label' => 'Simulado*'],
    		['value' => 'Bradesco', 'label' => 'Bradesco'],
    		['value' => 'BancoDoBrasil', 'label' => 'Banco do Brasil'],
    		['value' => 'CitiBank', 'label' => 'CitiBank'],
    		['value' => 'Itau', 'label' => 'Itau'],
    		['value' => 'Brb', 'label' => 'Brb'],
    		['value' => 'Caixa', 'label' => 'Caixa'],
    		['value' => 'Santander', 'label' => 'Santander'],
    		['value' => 'HSBC', 'label' => 'HSBC'],
    	];
    }
}