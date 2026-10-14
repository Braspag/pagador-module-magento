<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Api;

/**
 * Starts an MPI V3 session by calling the INIT endpoint at Cielo and
 * returning the frontend-safe pair { referenceId, token(JWT) } that the
 * browser needs to bootstrap MPI.load() / MPI.init().
 *
 * The backend access_token stays server-side and is NOT exposed here.
 */
interface MpiV3SessionManagerInterface
{
    /**
     * Starts a new MPI V3 session for the given cart.
     *
     * @param string $cartId Masked or numeric quote id
     * @return \Braspag\BraspagPagador\Api\Data\MpiV3SessionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function startSession($cartId);
}
