<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\OAuth2\Resource\Token;

use Webjump\Braspag\Pagador\Transaction\Api\OAuth2\Token\RequestInterface as BraspaglibRequestInterface;

interface RequestInterface extends BraspaglibRequestInterface
{
    const OAUTH2_ACCESS_TOKEN_COOKIE_NAME = 'oauth2_access_token';
}