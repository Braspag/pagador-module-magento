<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;


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
    const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_TEST_MODE = 'webjump_braspag/pagador/test_mode';

    protected $config;
	protected $context;

	public function __construct(
	    ContextInterface $context,
	    array $data = []
    )
    {
        $this->setContext($context);
        $this->_construct($data);
    }

    protected function _construct(array $data = [])
    {
    }

    protected function _getConfig($uri)
    {
        return $this->getConfig()->getValue($uri, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    protected function isTestMode()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_TEST_MODE);
    }

    protected function getContext()
    {
        return $this->context;
    }

    protected function setContext(ContextInterface $context)
    {
        $this->context = $context;
        return $this;
    }

    protected function getConfig()
    {
        return $this->getContext()->getConfig();
    }

    public function getSession()
    {
        return $this->getContext()->getSession();
    }

    protected function getStoreManager()
    {
        return $this->getContext()->getStoreManager();
    }

    protected function getDateTime()
    {
        return $this->getContext()->getDateTime();
    }
}
