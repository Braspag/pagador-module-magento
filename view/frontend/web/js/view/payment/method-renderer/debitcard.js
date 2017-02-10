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
        'Webjump_BraspagPagador/js/view/payment/method-renderer/creditcard',
        'Webjump_BraspagPagador/js/action/redirect-after-placeorder',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Webjump_BraspagPagador/js/model/superdebito',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/action/redirect-on-success',
        'Magento_Checkout/js/model/error-processor',
        'jquery',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (
        Component,
        RedirectAfterPlaceOrder,
        additionalValidators,
        SuperDebito,
        quote,
        totals,
        redirectOnSuccessAction,
        errorProcessor,
        $,
        fullScreenLoader
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Webjump_BraspagPagador/payment/debitcard',
                redirectAfterPlaceOrder: false
            },

            initObservable: function () {
                this._super()
                    .observe([
                        'creditCardType',
                        'creditCardNumber',
                        'creditCardOwner',
                        'creditCardExpYear',
                        'creditCardExpMonth',
                        'creditCardVerificationNumber',
                        'merchantOrderNumber',
                        'customerName',
                        'amount'
                    ]);

                return this;
            },

            getData: function () {
                return {
                    'method': this.item.method,
                    'additional_data': {
                        'cc_cid': this.creditCardVerificationNumber(),
                        'cc_type': this.creditCardType(),
                        'cc_exp_year': this.creditCardExpYear(),
                        'cc_exp_month': this.creditCardExpMonth(),
                        'cc_number': this.creditCardNumber(),
                        'cc_owner': this.creditCardOwner()
                    }
                };
            },

            getCode: function() {
                return 'braspag_pagador_debitcard';
            },

            isActive: function() {
                return true;
            },

            updateCreditCardExpData: function () {
                this.creditCardExpDate(this.pad(this.creditCardExpMonth(), 2) + '/' + this.creditCardExpYear().slice(-2));
            },

            updateCustomerName: function () {
                this.customerName(quote.billingAddress().firstname + ' ' + quote.billingAddress().lastname);
            },

            updateMerchantId: function () {
                this.merchantOrderNumber('12000000000001');
            },

            updateAMount: function () {
                var grand_total = 0;

                if (totals.totals()) {
                    grand_total = parseFloat(totals.getSegment('grand_total').value);
                }

                this.amount(grand_total);
            },

            prepareData: function () {
                this.updateCreditCardExpData();
                this.updateCustomerName();
                this.updateMerchantId();
                this.updateAMount();
            },

            placeOrder: function (data, event) {
                var self = this;

                if (! this.validateForm('#'+ this.getCode() + '-form')) {
                    return;
                }

                if (event) {
                    event.preventDefault();
                }

                if (this.validate() && additionalValidators.validate()) {
                    this.isPlaceOrderActionAllowed(false);

                    this.getPlaceOrderDeferredObject()
                        .fail(
                            function () {
                                self.isPlaceOrderActionAllowed(true);
                            }
                        ).done(
                        function (orderId) {
                            if (SuperDebito.isActive(self.getCode())) {
                                return self.placeOrderWithSuperDebito(orderId);
                            }

                            fullScreenLoader.startLoader();
                            $.when(
                                RedirectAfterPlaceOrder(orderId)
                            ).done(
                                function (url) {
                                    console.log(url);
                                    if (url.length) {
                                        window.location.replace(url);
                                        return true;
                                    }

                                    if (self.redirectAfterPlaceOrder) {
                                        redirectOnSuccessAction.execute();
                                    }
                                }
                            ).fail(
                                function (response) {
                                    errorProcessor.process(response, messageContainer);
                                }
                            ).always(function () {
                                fullScreenLoader.stopLoader();
                            });
                        }
                    );
                }

                return false;
            },

            placeOrderWithSuperDebito: function (orderId) {
                this.prepareData();

                if (! this.validateForm('#'+ this.getCode() + '-form')) {
                    return;
                }

                var options = {
                    onInitialize: function(response) {
                        console.log(response);
                    },
                    onNotAuthenticable: function (response) {
                        redirectOnSuccessAction.execute();
                    },
                    onInvalid: function(response) {
                        console.log(response);
                    },
                    onError: function(response) {
                        console.log(response);
                    },
                    onAbort: function(response) {
                        console.log(response);
                    },
                    onRedirect: function(response) {
                        console.log(response);
                    },
                    onAuthorize: function(response) {
                        redirectOnSuccessAction.execute();
                    },
                    onNotAuthorize: function(response) {
                        redirectOnSuccessAction.execute();
                    },
                    onFinalize: function(response) {
                        console.log(response);
                    }
                };

                SuperDebito.start(options);
            }

        });
    }
);
