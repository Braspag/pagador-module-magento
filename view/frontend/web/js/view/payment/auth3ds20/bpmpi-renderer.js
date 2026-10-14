/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 *
 * Compatibility shim.
 *
 * The V3 flow does NOT need the hidden-input hydration this helper used to
 * do — the ENROLL/VALIDATE payloads are built server-side in
 * `Model/Mpi/PayloadBuilder.php`. This module remains only because the
 * credit/debit/voucher renderers still `require` it; every call is a safe
 * no-op except `renderBpmpiData`, which still writes to the 6 hidden inputs
 * MPI V3 actually consumes (auth_failure_type, auth_cavv, auth_xid,
 * auth_eci, auth_version, auth_reference_id).
 */
/*browser:true*/
/*global define*/
define(['jquery'], function ($) {
    'use strict';

    var V3_ACTIVE_CLASSES = [
        'bpmpi_auth_failure_type',
        'bpmpi_auth_cavv',
        'bpmpi_auth_xid',
        'bpmpi_auth_eci',
        'bpmpi_auth_version',
        'bpmpi_auth_reference_id'
    ];

    return {
        /**
         * Sets a hidden input's value only if it is one of the 6 that
         * survived the V3 migration. Every other call is a no-op — the field
         * now travels through the backend payload instead of the DOM.
         *
         * @param {string} className Bare class name, no leading dot
         * @param {*}      _unused   Signature parity with the legacy helper
         * @param {*}      value
         */
        renderBpmpiData: function (className, _unused, value) {
            if (V3_ACTIVE_CLASSES.indexOf(className) === -1) {
                return;
            }
            $('.' + className).val(value);
        },

        createInputHiddenElement: function () {
            // No-op: V2 used this to append cart-item inputs to the DOM; V3
            // pulls cart items from the quote on the backend.
        }
    };
});
