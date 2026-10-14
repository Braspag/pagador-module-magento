<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Resource\Auth;

/**
 * Immutable payload carrier for the MPI V3 AUTH request.
 *
 * The Basic Auth header (Client ID + Client Secret) is composed here as well
 * so the command layer receives a fully ready-to-fire request object.
 */
interface RequestInterface
{
    /**
     * @return string Base64-encoded "clientId:clientSecret" for the Basic header
     */
    public function getBasicAuthCredential();

    /**
     * @return string Numeric establishment code (10 digits)
     */
    public function getEstablishmentCode();

    /**
     * @return string Merchant display name (max 25 chars)
     */
    public function getMerchantName();

    /**
     * @return string MCC (4 digits)
     */
    public function getMcc();

    /**
     * Body already normalized to the field names Cielo expects
     * (EstablishmentCode / MerchantName / MCC).
     *
     * @return array
     */
    public function toArray();
}
