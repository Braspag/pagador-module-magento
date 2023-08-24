/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2019 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
/*browser:true*/
/*global define*/
define(
    [
        "jquery",
        "Braspag_BraspagPagador/js/vendor/card.view",
    ],
    function (
        $,
        cardView
    ) {
        'use strict';

        return {

            isCreditCardViewEnabled: function () {
                return window.checkoutConfig.payment.ccform.card_view.active;
            },

            isDebitCardViewEnabled: function () {
                return window.checkoutConfig.payment.dcform.card_view.active;
            },

            isVoucherViewEnabled: function () {
                return window.checkoutConfig.payment.vcform.card_view.active;
            },

            initialize: function () {
            },
        };
    }
);
