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
		return $this->getConfig()->getValue('payment/braspag_pagador_global/merchant_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getMerchantKey()
	{
		return $this->getConfig()->getValue('payment/braspag_pagador_global/merchant_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function isAuthorizeAndCapture()
	{
		return (\Magento\Authorizenet\Model\Authorizenet::ACTION_AUTHORIZE_CAPTURE === $this->getConfig()->getValue('payment/braspag_pagador_creditcard/payment_action', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
	}

	public function getSoftDescriptor()
	{
		return $this->getConfig()->getValue('payment/braspag_pagador_creditcard/soft_config', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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
