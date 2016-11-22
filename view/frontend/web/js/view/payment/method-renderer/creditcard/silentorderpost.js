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
        'Webjump_BraspagPagador/js/view/payment/method-renderer/creditcard/paymenttoken',
        'jquery'
    ],
    function(
        paymentToken,
        $
    ) {
        'use strict';

        return {
            
            isActive: function (code) {
                return window.checkoutConfig.payment.ccform.silentorderpost.active[code];
            },

            getAccessToken: function (code) {
                return window.checkoutConfig.payment.ccform.silentorderpost.accesstoken[code];
            },

            getUrl: function (code) {
                return window.checkoutConfig.payment.ccform.silentorderpost.url[code];
            },

            getPaymentToken: function(options) {
                var settings = {
                    "async": false, //deprecated
                    "url": this.getUrl(options.code),
                    "method": "POST",
                    "data": {
                        "HolderName": options.holderName,
                        "RawNumber": options.rawNumber,
                        "Expiration": options.expiration,
                        "SecurityCode": options.securityCode,
                        "AccessToken": this.getAccessToken(options.code)
                    }
                }

                $.ajax(settings).done(function (result) {
                    paymentToken.setPaymentToken(result.PaymentToken);
                    options.successCallBack();
                }).fail(function () {
                    paymentToken.setPaymentToken(false);
                    options.failCallBack();
                });

                return paymentToken.getPaymentToken();
            }
        }
    }
);