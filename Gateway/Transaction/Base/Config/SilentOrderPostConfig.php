<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class SilentOrderPostConfig extends AbstractConfig implements SilentOrderPostConfigInterface
{
	protected $config;

	protected $code;

	public function __construct(
		ScopeConfigInterface $config,
		$code
	){
		$this->setCode($code);
		$this->setConfig($config);
	}

	public function isActive()
	{
		return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_IS_ACTIVE);
	}

	public function getUrl()
	{
		if ($this->isTestMode()) {
			return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_URL_HOMOLOG);
		}

		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_URL_PRODUCTION);
	}

	protected function _getConfig($uri)
	{
		return parent::_getConfig(sprintf($uri, $this->getCode()));
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
