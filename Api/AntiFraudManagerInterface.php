<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Api;

interface AntiFraudManagerInterface
{
    /**
     * @param string $customerId
     * @return mixed
     */
    public function getFingerPrintData($customerId);
}
