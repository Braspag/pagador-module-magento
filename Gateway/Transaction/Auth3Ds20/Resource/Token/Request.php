<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token;

use Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Config\Config;

class Request extends Config implements RequestInterface
{
    public function getAccessToken()
    {
        $clientId = $this->getAuth3Ds20ClientId();
        $clientSecret = $this->getAuth3Ds20ClientSecret();

        return base64_encode($clientId.":".$clientSecret);
    }
}