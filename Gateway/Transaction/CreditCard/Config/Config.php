<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config implements ConfigInterface
{
	protected $config;

	const ACTION_AUTHORIZE_CAPTURE = 'authorize_capture';

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
		return (self::ACTION_AUTHORIZE_CAPTURE === $this->getConfig()->getValue('payment/braspag_pagador_creditcard/payment_action', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
	}

	public function getSoftDescriptor()
	{
		return $this->getConfig()->getValue('payment/braspag_pagador_creditcard/soft_config', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getSilentOrderPostUri()
	{
		return 'https://homologacao.pagador.com.br/post/api/public/v1/accesstoken?merchantid=' . $this->getMerchantId();
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
