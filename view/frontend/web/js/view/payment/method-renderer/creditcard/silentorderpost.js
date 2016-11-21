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

            getPaymentToken: function(code, messageContainer) {
                messageContainer = messageContainer || globalMessageList;

                var options = {
                    accessToken: this.getAccessToken(code),

                    onSuccess: function (e) {
                        console.log(e);
                        console.log(e.PaymentToken);
                        paymentToken.setPaymentToken(e.PaymentToken);
                    },

                    onError: function (e) {
                        console.log(e);
                        messageContainer.addErrorMessage({message: e.Text, parameters: [], trace: ''});
                    },

                    onInvalid: function (e) {
                        console.log(e);
                        for (var i = e.length - 1; i >= 0; i--) {
                            messageContainer.addErrorMessage({message: e[i].Message, parameters: [], trace: ''});
                        }
                    },

                    environment: "sandbox",
                    language: "PT"
                };

                SilentOrderPost.bpSop_silentOrderPost(options);

                console.log(paymentToken.getPaymentToken());

                return paymentToken.getPaymentToken();
            }
        }
    }
);