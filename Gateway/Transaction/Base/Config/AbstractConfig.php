<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Braspag Transaction Base AbstractConfig
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
abstract class AbstractConfig
{
	protected $config;

    public function __construct(
        ScopeConfigInterface $config
    ){
        $this->setConfig($config);
    }

	protected function _getConfig($uri)
	{
		return $this->getConfig()->getValue($uri, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

    protected function getConfig()
    {
        return $this->config;
    }

    protected function setConfig(ScopeConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }
}
