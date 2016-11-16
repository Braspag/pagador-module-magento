<?php

namespace Webjump\BraspagPagador\Model;

/**
 * Cartoken Factory Interface
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
interface CardTokenFactoryInterface
{
    public function create($alias, $token);
}
