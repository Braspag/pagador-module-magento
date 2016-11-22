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
        'Webjump_BraspagPagador/js/vendor/silentorderpost',
        'Magento_Ui/js/model/messageList',
        'Webjump_BraspagPagador/js/view/payment/method-renderer/creditcard/paymenttoken',
        'jquery',
    ],
    function(
        SilentOrderPost,
        globalMessageList,
        paymentToken,
        $
    ) {
        'use strict';

        return {
            
            isActive: function () {
                return true;
            },

            getAccessToken: function (code) {
                return window.checkoutConfig.payment.ccform.silentorderpost.accesstoken[code];
            },

            getPaymentToken: function(creditcard, messageContainer) {
                messageContainer = messageContainer || globalMessageList;

                var settings = {
                  "async": false,
                  "url": "https://homologacao.pagador.com.br/post/api/public/v1/card",
                  "method": "POST",
                  "data": {
                    "HolderName": creditcard.creditCardOwner(),
                    "RawNumber": creditcard.creditCardNumber(),
                    "Expiration": creditcard.creditCardExpDate(),
                    "SecurityCode": creditcard.creditCardVerificationNumber(),
                    "AccessToken": this.getAccessToken(creditcard.getCode())
                  }
                }

                $.ajax(settings).done(function (result) {
                    paymentToken.setPaymentToken(result.PaymentToken);
                }).fail(function () {
                    paymentToken.setPaymentToken(false);
                    messageContainer.addErrorMessage({message: "Error geting the silent order post payment token!", parameters: [], trace: ''});
                });

                return paymentToken.getPaymentToken();
            },

            validate: function (paymentToken) {
                return (paymentToken);
            }
        }
    }
);