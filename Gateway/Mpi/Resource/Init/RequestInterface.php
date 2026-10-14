<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Init;

/**
 * Payload for POST /v3/3ds/init.
 *
 * Doc body: { "orderNumber": "...", "currency": "986", "amount": "1000" }
 * Currency is numeric ISO 4217 (986 = BRL) and amount is the integer number
 * of minor units as a string.
 */
interface RequestInterface
{
    /**
     * @return string
     */
    public function getOrderNumber();

    /**
     * @return string Numeric ISO 4217 code (e.g. "986" for BRL)
     */
    public function getCurrency();

    /**
     * @return string Amount in minor units, as string
     */
    public function getAmount();

    /**
     * @return array Body in the exact shape Cielo expects
     */
    public function toArray();
}
