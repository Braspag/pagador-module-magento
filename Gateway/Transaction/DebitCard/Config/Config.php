<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\DebitCard\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * 
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
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

	public function getPaymentReturnUrl()
	{
		return $this->getConfig()->getValue('payment/braspag_pagador_debitcard/return_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function isSuperDebitoActive()
	{
		return (bool) $this->getConfig()->getValue('payment/braspag_pagador_debitcard/superdebit_active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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