<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config implements ConfigInterface
{
	protected $config;

	public function __construct(
		ScopeConfigInterface $config
	){
		$this->setConfig($config);
	}

	public function getMerchantId()
	{
		return $this->getConfig()->getValue('payment/braspag_pagador_billet/merchant_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getMerchantKey()
	{
		return $this->getConfig()->getValue('payment/braspag_pagador_billet/merchant_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

    protected function getConfig()
    {
        return $this->config;
    }

    protected function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }
}
