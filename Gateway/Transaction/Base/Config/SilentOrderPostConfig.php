<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;


class SilentOrderPostConfig extends Config implements SilentOrderPostConfigInterface
{
	protected $code;

    protected function _construct(array $data = [])
    {
        $code = '';
        if (isset($data['code'])) {
            $code = $data['code'];
        }

        $this->setCode($code);
    }

	public function isActive()
	{
		return (bool) $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_IS_ACTIVE);
	}

	public function getPaymentTokenUrl()
	{
		if ($this->isTestMode()) {
			return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_URL_HOMOLOG);
		}

		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_URL_PRODUCTION);
	}

    public function getAccessTokenUrl()
    {
        $url = $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_ACCESSTOKEN_URL_PRODUCTION);

        if ($this->isTestMode()) {
            $url = $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_SILENTORDERPOST_ACCESSTOKEN_URL_HOMOLOG);
        }

        return $url . $this->getMerchantId();
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
