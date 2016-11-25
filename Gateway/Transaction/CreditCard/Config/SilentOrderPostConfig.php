<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class SilentOrderPostConfig implements SilentOrderPostConfigInterface
{
	protected $config;

	protected $code;

	public function __construct(
		$code,
		ScopeConfigInterface $config
	){
		$this->setCode($code);
		$this->setConfig($config);
	}

	public function isActive()
	{
		return (boolean) $this->getConfig()->getValue("payment/{$this->getCode()}/silentorderpost_active", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getUrl()
	{
		if ($this->isSandBoxMode()) {
			return $this->getConfig()->getValue("payment/{$this->getCode()}/silentorderpost_url_homolog", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		}

		return $this->getConfig()->getValue("payment/{$this->getCode()}/silentorderpost_url_production", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	protected function isSandBoxMode()
	{
		return $this->getConfig()->getValue("payment/{$this->getCode()}/silentorderpost_is_sandbox_mode", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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

    protected function getCode()
    {
        return $this->code;
    }

    protected function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}
