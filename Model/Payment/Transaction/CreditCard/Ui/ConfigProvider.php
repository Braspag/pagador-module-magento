<?php

namespace Webjump\BraspagPagador\Model\Payment\Transaction\CreditCard\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Braspag Transaction CreditCard Authorize Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'braspag_pagador_creditcard';

    protected $installment = [];

    public function getConfig()
    {
        return [
            'payment' => [
                'ccform' => [
                    'installments' => $this->getInstallments(),
                ]
            ]
        ];
    }

    protected function getInstallments()
    {
    	if (!empty($this->installments)) {
    		return $this->installments;
    	}

    	$installmentsMax = 10;

    	for ($i=1; $i < $installmentsMax++; $i++) { 
    		$this->installments[self::CODE][$i] = __('Installments ' . $i);
    	}

    	return $this->installments;
    }
}
