<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\AbstractConfig;

class Config extends AbstractConfig implements ConfigInterface
{
    public function getSequence()
    {
        return $this->_getConfig(self::XML_PATH_SEQUENCE);
    }

    public function getSequenceCriteria()
    {
        return $this->_getConfig(self::XML_PATH_SEQUENCE_CRITERIA);
    }

    public function getCaptureOnLowRisk()
    {
        return (bool) $this->_getConfig(self::XML_PATH_CAPTURE_ON_LOW_RISK);
    }

    public function getVoidOnHighRisk()
    {
        return (bool) $this->_getConfig(self::XML_PATH_VOID_ON_HIGH_RISK);
    }

    public function userOrderIdToFingerPrint()
    {
        return (bool) $this->_getConfig(self::XML_ORDER_ID_TO_FINGERPRINT);
    }
}
