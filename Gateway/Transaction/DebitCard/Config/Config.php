<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\AbstractConfig;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\ConfigInterface as BaseConfigInterface;

class Config extends AbstractConfig implements ConfigInterface
{
	public function getMerchantId()
	{
		return $this->getConfig()->getValue(BaseConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_ID, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getMerchantKey()
	{
		return $this->getConfig()->getValue(BaseConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_KEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getPaymentReturnUrl()
	{
        return $this->_getConfig(BaseConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_RETURN_URL);
    }

	public function isSuperDebitoActive()
	{
		return (bool) $this->getConfig()->getValue('payment/braspag_pagador_debitcard/superdebit_active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

    public function getIsTestEnvironment()
    {
        return $this->_getConfig(BaseConfigInterface::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_IS_TEST_ENVIRONMENT);
    }
}
