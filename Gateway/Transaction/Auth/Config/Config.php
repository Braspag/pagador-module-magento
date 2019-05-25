<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Auth\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\AbstractConfig;

/**
 * Class Config
 * @package Webjump\BraspagPagador\Gateway\Transaction\Auth\Config
 */
class Config extends AbstractConfig implements ConfigInterface
{
	public function getAuthenticationBasicToken()
	{
        return $this->getScopeConfig()->getValue(self::CONFIG_BRASPAG_PAGADOR_GLOBAL_AUTHENTICATION_TOKEN);
	}

	public function getMerchantName()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_NAME);
	}

	public function getEstablishmentCode()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_ESTABLISHMENT_CODE);
    }

	public function getMCC()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MMC);
    }

	public function getIsTestEnvironment()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_IS_TEST_ENVIRONMENT);
    }
}
