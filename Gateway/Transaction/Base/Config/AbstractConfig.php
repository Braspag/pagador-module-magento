<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;

use Magento\Framework\App\State;
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
    const CONFIG_XML_BRASPAG_PAGADOR_GLOBAL_TEST_MODE = 'webjump_braspag/pagador/test_mode';

    protected $config;
    protected $context;

    /**
     * @var State
     */
    protected $appState;

    /**
     * @var ContextInterface
     */
    protected $contextAdmin;

    protected $scopeConfig;

    public function __construct(
        ContextInterface $context,
        ContextInterface $contextAdmin,
        ScopeConfigInterface $scopeConfig,
        State $appState,
        array $data = []
    )
    {
        $this->setContext($context);
        $this->setContextAdmin($contextAdmin);
        $this->setScopeConfig($scopeConfig);
        $this->setAppState($appState);
        $this->_construct($data);
    }

    protected function _construct(array $data = [])
    {}

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
        $session = $this->getContext()->getSession();
        if ($this->getAppState()->getAreaCode() == 'adminhtml') {
            $session = $this->getContextAdmin()->getSession();
        }
        return $session;
    }

    protected function getStoreManager()
    {
        return $this->getContext()->getStoreManager();
    }

    protected function getDateTime()
    {
        return $this->getContext()->getDateTime();
    }

    /**
     * @return ContextInterface
     */
    public function getContextAdmin(): ContextInterface
    {
        return $this->contextAdmin;
    }

    /**
     * @param ContextInterface $contextAdmin
     */
    protected function setContextAdmin(ContextInterface $contextAdmin)
    {
        $this->contextAdmin = $contextAdmin;
    }

    /**
     * @return State
     */
    protected function getAppState(): State
    {
        return $this->appState;
    }

    /**
     * @param State $appState
     */
    protected function setAppState(State $appState)
    {
        $this->appState = $appState;
    }

    /**
     * @return mixed
     */
    public function getScopeConfig()
    {
        return $this->scopeConfig;
    }

    /**
     * @param mixed $scopeConfig
     */
    public function setScopeConfig($scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }
}
