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

    const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_TEST_MODE = 'payment/braspag_pagador_global/test_mode';

    public function __construct(
        ScopeConfigInterface $config
    ){
        $this->setConfig($config);
    }

    protected function isTestMode()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_TEST_MODE);
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
