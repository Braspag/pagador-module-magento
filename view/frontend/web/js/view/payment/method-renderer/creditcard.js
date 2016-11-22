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
        'Magento_Payment/js/view/payment/cc-form',
        'mage/translate',
        'Webjump_BraspagPagador/js/view/payment/method-renderer/creditcard/silentorderpost',
        'jquery',
        'Magento_Checkout/js/action/place-order',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (Component, $t, sopt, $, placeOrderAction, fullScreenLoader) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Webjump_BraspagPagador/payment/creditcard',
                creditCardInstallments: '',
                creditCardsavecard: 0,
                creditCardExpDate: '',
                creditCardSoptPaymentToken: ''
            },

            initObservable: function () {
                this._super()
                    .observe([
                        'creditCardType',
                        'creditCardNumber',
                        'creditCardOwner',
                        'creditCardExpYear',
                        'creditCardExpMonth',
                        'creditCardExpDate',
                        'creditCardVerificationNumber',
                        'creditCardInstallments',
                        'creditCardsavecard',
                        'creditCardSoptPaymentToken'
                    ]);

                return this;
            },

            getCode: function() {
                return 'braspag_pagador_creditcard';
            },

            isActive: function() {
                return true;
            },

            getData: function () {

                return {
                    'method': this.item.method,
                    'additional_data': {
                        'cc_cid': this.creditCardVerificationNumber (),
                        'cc_type': this.creditCardType(),
                        'cc_exp_year': this.creditCardExpYear(),
                        'cc_exp_month': this.creditCardExpMonth(),
                        'cc_number': this.creditCardNumber(),
                        'cc_owner': this.creditCardOwner(),
                        'cc_installments': this.creditCardInstallments(),
                        'cc_savecard': this.creditCardsavecard() ? 1 : 0,
                        'cc_soptpaymenttoken': this.creditCardSoptPaymentToken()
                    }
                };
            },

            isInstallmentsActive: function () {
                return window.checkoutConfig.payment.ccform.installments.active[this.getCode()];
            },

            getCcInstallments: function() {
                return window.checkoutConfig.payment.ccform.installments.list[this.getCode()];
            },

            getCcInstallmentsValues: function() {
                return _.map(this.getCcInstallments(), function (value, key) {
                    return {
                        'value': key,
                        'installments': value
                    };
                });
            },

            isSaveCardActive: function() {
                return window.isCustomerLoggedIn;
            },

            getSaveCardHelpHtml: function () {
                return '<span>' + $t('Add To Braspag JustClick') + '</span>';
            },

            updateCreditCardSoptPaymentToken: function () {
                var self = this;

                fullScreenLoader.startLoader();

                var options = {
                    holderName: self.creditCardOwner(),
                    rawNumber: self.creditCardNumber(),
                    expiration: self.creditCardExpDate(),
                    securityCode: self.creditCardVerificationNumber(),
                    code: self.getCode(),
                    successCallBack: function () {
                        fullScreenLoader.stopLoader();
                    },
                    failCallBack: function () {
                        fullScreenLoader.stopLoader();
                        this.messageContainer.addErrorMessage({message: "Error geting the silent order post payment token!", parameters: [], trace: ''});
                    }
                };

                this.creditCardSoptPaymentToken(sopt.getPaymentToken(options));
            },

            updateCreditCardExpData: function () {
                this.creditCardExpDate(this.pad(this.creditCardExpMonth(), 2) + '/' + this.creditCardExpYear());
            },

            pad: function(num, size) {
                var s = "00" + num;
                return s.substr(s.length-size);
            },

            getPlaceOrderDeferredObject: function () {

                if (sopt.isActive(this.getCode())) {
                    this.updateCreditCardExpData();
                    this.updateCreditCardSoptPaymentToken();
                }

                return $.when(
                    placeOrderAction(this.getData(), this.messageContainer)
                );
            }

        });
    }
);
