<?php

/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 */

namespace Braspag\BraspagPagador\Gateway\Mpi\Config;

/**
 * Reads admin configuration for the MPI V3 flow.
 *
 * Provides the Client credentials required for the AUTH request and the
 * merchant identity fields (EstablishmentCode / MerchantName / MCC) required
 * by the AUTH body. The merchant identity fields are read from the Global
 * Settings section (paths already declared in the module's `global.xml`) —
 * reusing them avoids duplicating admin fields and lets stores that already
 * had these globals filled work with MPI V3 without extra configuration.
 */
interface ConfigInterface
{
    /**
     * MPI V3 API bases per environment.
     */
    const API_BASE_SANDBOX     = 'https://mpisandbox.braspag.com.br/v3';
    const API_BASE_PRODUCTION  = 'https://mpi.braspag.com.br/v3';

    /**
     * MPI V3 frontend script bases per environment.
     */
    const SCRIPT_BASE_SANDBOX     = 'https://mpisandbox.braspag.com.br/Scripts/V3';
    const SCRIPT_BASE_PRODUCTION  = 'https://mpi.braspag.com.br/Scripts/V3';

    /**
     * AUTH endpoint suffix appended to the API base.
     */
    const AUTH_ENDPOINT_PATH = '/auth/token';

    /**
     * MPI V3 Client credentials — stored under the same node V2 already used
     * so that upgrading a store does not require reconfiguring the client id/
     * secret pair. Client secret is encrypted at rest via Magento's Encrypted
     * backend model.
     */
    const XML_PATH_CLIENT_ID           = 'braspag_braspag/authentication3ds20_accesstokengeneration/clientid';
    const XML_PATH_CLIENT_SECRET       = 'braspag_braspag/authentication3ds20_accesstokengeneration/clientsecret';

    /**
     * Merchant identity fields — shared with the module's Global Settings
     * section (`etc/adminhtml/system/global.xml`). Reusing avoids duplicate
     * admin UI and lets pre-existing config carry over. Also matches the
     * constants exposed by the module's base config (`Base\Config\ConfigInterface`).
     */
    const XML_PATH_ESTABLISHMENT_CODE  = 'braspag_braspag/pagador/establishment_code';
    const XML_PATH_MERCHANT_NAME       = 'braspag_braspag/pagador/merchant_name';
    const XML_PATH_MCC                 = 'braspag_braspag/pagador/mcc';

    /**
     * Sandbox toggle — shared with the module's base Braspag environment flag.
     * Keeps parity between the MPI V3 URLs and the /v2/sales/ endpoint the
     * transaction eventually hits.
     */
    const XML_PATH_IS_SANDBOX = 'braspag_braspag/pagador/test_mode';

    /**
     * Debug log toggle. When true, MPI V3 commands write full request/response
     * traces to var/log/braspag_mpi_v3.log — with sensitive data (PAN, tokens)
     * redacted. Meant for validation phases; keep off in production.
     */
    const XML_PATH_DEBUG_ACTIVE = 'braspag_braspag/authentication3ds20_accesstokengeneration/debug_active';

    /**
     * @return string|null Client ID configured in admin
     */
    public function getClientId();

    /**
     * @return string|null Decrypted Client Secret configured in admin
     */
    public function getClientSecret();

    /**
     * @return string|null Numeric establishment code (10 digits) — from Global Settings
     */
    public function getEstablishmentCode();

    /**
     * @return string|null Merchant display name (max 25 chars) — from Global Settings
     */
    public function getMerchantName();

    /**
     * @return string|null MCC (4-digit Merchant Category Code) — from Global Settings
     */
    public function getMcc();

    /**
     * Whether the Braspag environment toggle is set to sandbox.
     *
     * @return bool
     */
    public function isSandbox();

    /**
     * Base URL of the MPI V3 API for the currently configured environment.
     *
     * @return string
     */
    public function getApiBase();

    /**
     * Base URL of the frontend MPI scripts (mpi.js, mpiHelpers.js) for the
     * currently configured environment.
     *
     * @return string
     */
    public function getScriptBase();

    /**
     * Full URL for the AUTH endpoint (POST) — issued backend-side to obtain
     * the access_token.
     *
     * @return string
     */
    public function getAuthEndpoint();

    /**
     * Whether MPI V3 debug logging is enabled in admin.
     *
     * @return bool
     */
    public function isDebugActive();
}
