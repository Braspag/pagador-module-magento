/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
/*browser:true*/
/*global define*/
define(
    [
        'Webjump_BraspagPagador/js/vendor/silentorderpost'
    ],
    function(
        SilentOrderPost
    ) {
        'use strict';

        return {
            
            getAccessToken: function (code) {
                return window.checkoutConfig.payment.ccform.silentorderpost.accesstoken[code];
            },

            getPaymentToken: function(accesstoken) {
                var paymentToken = '';

                var options = {
                    accessToken: accesstoken,

                    onSuccess: function (e) {
                        console.log(e);
                        paymentToken = e.PaymentToken;
                    },

                    onError: function (e) {
                        console.log(e);
                    },

                    onInvalid: function (e) {
                        console.log(e);
                    },

                    environment: "sandbox",
                    language: "PT"
                };

                SilentOrderPost.bpSop_silentOrderPost(options);

                return paymentToken;
            }
        }
    }
);