<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\Config as BaseConfig;

/**
 * Class Config
 * @package Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config
 */
class Config extends BaseConfig implements ConfigInterface
{
    /**
     * @return mixed
     */
    public function getAuth3Ds20ClientId()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_AUTHENTICATION3DS20_CLIENT_ID);
    }

    /**
     * @return mixed
     */
    public function getAuth3Ds20ClientSecret()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_AUTHENTICATION3DS20_CLIENT_SECRET);
    }
}
