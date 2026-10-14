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
 * Manages the MPI V3 access_token.
 *
 * The access_token is a short-lived Bearer credential (TTL 20 min at Cielo)
 * that authorizes INIT, ENROLL and VALIDATE calls. It must never be exposed
 * to the frontend. Implementations are expected to cache the token backend-side
 * and refresh it preemptively before Cielo's expiry, plus reactively on 401
 * from downstream MPI calls.
 */
interface MpiV3AuthTokenManagerInterface
{
    /**
     * Returns a valid access_token for backend-only use with the MPI V3 API.
     *
     * The manager decides whether to serve a cached token or issue a fresh
     * AUTH request. Callers should NOT store the returned string beyond the
     * scope of a single MPI operation — always ask the manager again.
     *
     * @return string Bearer token
     * @throws \Magento\Framework\Exception\LocalizedException When AUTH fails
     */
    public function getAccessToken();

    /**
     * Invalidates the cached access_token, forcing the next getAccessToken()
     * call to reissue AUTH. Meant to be invoked when a downstream MPI call
     * responds 401 (token expired mid-flight).
     */
    public function invalidate();
}
