<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\OAuth2\Config;

/**
 * Interface ConfigInterface
 * @package Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config
 */
interface ConfigInterface
{
    const CONFIG_XML_BRASPAG_OAUTH2_CLIENT_ID = 'webjump_braspag/oauth2_accesstokengeneration/clientid';
    const CONFIG_XML_BRASPAG_OAUTH2_CLIENT_SECRET = 'webjump_braspag/oauth2_accesstokengeneration/clientsecret';

    public function getOAuth2ClientId();

    public function getOAuth2ClientSecret();
}