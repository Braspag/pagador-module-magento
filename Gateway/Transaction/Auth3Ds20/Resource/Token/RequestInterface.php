<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token;

use Webjump\Braspag\Pagador\Transaction\Api\Auth3Ds20\Token\RequestInterface as BraspaglibRequestInterface;

interface RequestInterface extends BraspaglibRequestInterface
{
    const BPMPI_ACCESS_TOKEN_COOKIE_NAME = 'bpmpi_access_token';
}