<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config;

/**
 * Interface ConfigInterface
 * @package Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config
 */
interface ConfigInterface
{
    const CONFIG_XML_BRASPAG_AUTHENTICATION3DS20_CLIENT_ID = 'webjump_braspag/authentication3ds20_accesstokengeneration/clientid';
    const CONFIG_XML_BRASPAG_AUTHENTICATION3DS20_CLIENT_SECRET = 'webjump_braspag/authentication3ds20_accesstokengeneration/clientsecret';

    public function getAuth3Ds20ClientId();

    public function getAuth3Ds20ClientSecret();
}