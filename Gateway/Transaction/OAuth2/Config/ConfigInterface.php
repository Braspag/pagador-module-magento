<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\OAuth2\Config;

/**
 * Interface ConfigInterface
 * @package Braspag\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config
 */
interface ConfigInterface
{
    const CONFIG_XML_BRASPAG_OAUTH2_CLIENT_ID = 'braspag_braspag/oauth2_accesstokengeneration/clientid';
    const CONFIG_XML_BRASPAG_OAUTH2_CLIENT_SECRET = 'braspag_braspag/oauth2_accesstokengeneration/clientsecret';

    public function getOAuth2ClientId();

    public function getOAuth2ClientSecret();
}