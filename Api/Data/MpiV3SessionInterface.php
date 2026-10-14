<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Api\Data;

/**
 * Data carrier for the /V1/braspag/mpi/session response.
 *
 * Existe porque a serialização REST do Magento não preserva chaves de arrays
 * associativos declarados como `@return array` — o resultado chega no browser
 * como array indexado por int, perdendo os nomes dos campos. Um Data Interface
 * com getters explícitos força o `ServiceOutputProcessor` a emitir JSON com
 * chaves nomeadas.
 */
interface MpiV3SessionInterface
{
    const REFERENCE_ID       = 'reference_id';
    const TOKEN              = 'token';
    const RESERVED_ORDER_ID  = 'reserved_order_id';

    /**
     * @return string
     */
    public function getReferenceId();

    /**
     * @param string $referenceId
     * @return $this
     */
    public function setReferenceId($referenceId);

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token);

    /**
     * @return string
     */
    public function getReservedOrderId();

    /**
     * @param string $reservedOrderId
     * @return $this
     */
    public function setReservedOrderId($reservedOrderId);
}
