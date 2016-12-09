<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config;


use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\AbstractConfig;

class AntiFraudConfig extends AbstractConfig implements AntiFraudConfigInterface
{
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
}
