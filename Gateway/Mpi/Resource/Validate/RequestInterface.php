<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Validate;

/**
 * VALIDATE body carrier. IMPORTANT: Cielo expects the body keys in lowercase
 * (`ordernumber`, `currency`, `totalamount`, `card`, `transactionId`).
 */
interface RequestInterface
{
    /**
     * @return array
     */
    public function toArray();
}
