<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Session\SessionManagerInterface;

class AntiFraudConfig implements AntiFraudConfigInterface
{
    protected $config;
    protected $session;
    
    public function __construct(ScopeConfigInterface $config, SessionManagerInterface $session)
    {
        $this->setConfig($config);
        $this->setSession($session);
    }

    public function getSequence()
    {
        return $this->getConfig()->getValue(self::XML_PATH_SEQUENCE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getSequenceCriteria()
    {
        return $this->getConfig()->getValue(self::XML_PATH_SEQUENCE_CRITERIA, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getCaptureOnLowRisk()
    {
        return (bool) $this->getConfig()->getValue(self::XML_PATH_CAPTURE_ON_LOW_RISK, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getVoidOnHighRisk()
    {
        return (bool) $this->getConfig()->getValue(self::XML_PATH_VOID_ON_HIGH_RISK, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return ScopeConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param ScopeConfigInterface $config
     * @return $this
     */
    protected function setConfig(ScopeConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return SessionManagerInterface
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param SessionManagerInterface $session
     * @return $this
     */
    protected function setSession(SessionManagerInterface $session)
    {
        $this->session = $session;

        return $this;
    }
}
