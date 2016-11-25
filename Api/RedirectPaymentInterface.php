<?php

namespace Webjump\BraspagPagador\Api;

/**
 * 
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
interface RedirectPaymentInterface
{
    /**
     * @param string $orderId
     * @return string url
     */
    public function getLink($orderId);
}