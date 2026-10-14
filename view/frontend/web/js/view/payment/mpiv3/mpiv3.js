/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 *
 * MPI V3 checkout view component.
 *
 * Owns the hidden-input template (`payment/mpiv3/mpiv3.html`) that the
 * renderers write Cavv/Xid/Eci/etc into. All heavy lifting — payload
 * hydration, script loading, ENROLL/VALIDATE calls — happens in
 * `js/model/mpiv3.js`; this component only exists so the checkout layout
 * has a mount point for the template.
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiElement',
        'Braspag_BraspagPagador/js/model/mpiv3'
    ],
    function (Component, mpiv3) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Braspag_BraspagPagador/payment/mpiv3/mpiv3'
            },

            /**
             * Kept for source-level compat with any external template that
             * still calls `bpmpiAuthLoad()` on this component — hands off to
             * the new lazy loader in `mpiv3.js`.
             */
            bpmpiAuthLoad: function () {
                if (mpiv3.isBpmpiEnabled()) {
                    mpiv3.bpmpiLoad('creditcard', '#credit-card-number');
                }
            }
        });
    }
);
