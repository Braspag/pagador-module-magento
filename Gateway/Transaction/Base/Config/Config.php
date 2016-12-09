<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

/**
 * Braspag Transaction Base Config
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Config extends AbstractConfig implements ConfigInterface
{
	public function getMerchantId()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_ID);
	}

	public function getMerchantKey()
	{
		return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_MERCHANT_KEY);
	}
}
