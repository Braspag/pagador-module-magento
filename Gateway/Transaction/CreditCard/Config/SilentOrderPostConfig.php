<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class SilentOrderPostConfig implements SilentOrderPostConfigInterface
{
	protected $config;

	public function __construct(
		ScopeConfigInterface $config
	){
		$this->setConfig($config);
	}

	public function isActive($code)
	{
		return $this->getConfig()->getValue("payment/{$code}/silentorderpost_active", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getUrl($code)
	{
		if ($this->isSandBoxMode()) {
			return $this->getConfig()->getValue("payment/{$code}/silentorderpost_url_homolog", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		}

		return $this->getConfig()->getValue("payment/{$code}/silentorderpost_url_production", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	protected function isSandBoxMode()
	{
		return $this->getConfig()->getValue("payment/{$code}/silentorderpost_is_sandbox_mode", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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
