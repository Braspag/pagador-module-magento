<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\OAuth2\Config;

use Braspag\BraspagPagador\Gateway\Transaction\Base\Config\Config as BaseConfig;

/**
 * Class Config
 * @package Braspag\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config
 */
class Config extends BaseConfig implements ConfigInterface
{
    /**
     * @return mixed
     */
    public function getOAuth2ClientId()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_OAUTH2_CLIENT_ID);
    }

    /**
     * @return mixed
     */
    public function getOAuth2ClientSecret()
    {
        return $this->_getConfig(self::CONFIG_XML_BRASPAG_OAUTH2_CLIENT_SECRET);
    }
}