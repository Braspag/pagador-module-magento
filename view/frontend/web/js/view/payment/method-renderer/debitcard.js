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
        'ko',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/action/place-order',
        'Webjump_BraspagPagador/js/view/payment/method-renderer/creditcard/silentorderpost',
        'Webjump_BraspagPagador/js/view/payment/method-renderer/creditcard/silentauthtoken',
        'Webjump_BraspagPagador/js/model/authentication3ds20',
        'Webjump_BraspagPagador/js/view/payment/auth3ds20/bpmpi-renderer',
        'Webjump_BraspagPagador/js/model/card.view',
        'Webjump_BraspagPagador/js/model/card'
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
        ko,
        fullScreenLoader,
        placeOrderAction,
        sopt,
        soptToken,
        authentication3ds20,
        bpmpiRenderer,
        cardView,
        card
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Webjump_BraspagPagador/payment/debitcard',
                redirectAfterPlaceOrder: window.checkoutConfig.payment.redirect_after_place_order,
                bpmpiInitControl: 0,
                bpmpiAuthFailureType: ko.observable(),
                bpmpiAuthCavv: ko.observable(),
                bpmpiAuthXid: ko.observable(),
                bpmpiAuthEci: ko.observable(),
                bpmpiAuthVersion: ko.observable(),
                bpmpiAuthReferenceId: ko.observable()
            },

            initialize: function () {
                this._super();
                this.bpmpiPlaceOrderInit();
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
                        'creditCardSoptPaymentToken',
                        'amount'
                    ]);

                return this;
            },

            updateCreditCardSoptPaymentToken: function () {
                var self = this;

                fullScreenLoader.startLoader();

                return $.when(
                    soptToken()
                ).done(function (transport) {

                    var options = {
                        holderName: self.creditCardOwner(),
                        rawNumber: self.creditCardNumber(),
                        expiration: self.creditCardExpDate(),
                        securityCode: self.creditCardVerificationNumber(),
                        code: 'braspag_pagador_creditcard',
                        authToken: transport,
                        successCallBack: function () {
                            fullScreenLoader.stopLoader();
                        },
                        failCallBack: function () {
                            fullScreenLoader.stopLoader();
                        }
                    };

                    var stoken = sopt.getPaymentToken(options);
                    self.creditCardSoptPaymentToken(stoken);

                    for (var i = 0; i < 5; i++){
                        if(!self.creditCardSoptPaymentToken()){
                            return self.updateCreditCardSoptPaymentToken();
                        } else {
                            break;
                        }
                    }

                    $.when(
                        placeOrderAction(self.getData(), self.messageContainer)
                    )
                        .fail(
                            function () {
                                self.isPlaceOrderActionAllowed(true);
                            }
                        ).done(
                        function (orderId) {

                            self.afterPlaceOrder();

                            if (orderId.length == 0) {
                                errorProcessor.process("O pagamento não pôde ser finalizado.", self.messageContainer);
                            } else {

                                fullScreenLoader.startLoader();
                                $.when(
                                    RedirectAfterPlaceOrder(orderId)
                                ).done(
                                    function (url) {
                                        if (self.redirectAfterPlaceOrder && url.length) {
                                            window.location.replace(url);
                                            return true;
                                        }

                                        if (self.redirectAfterPlaceOrder) {
                                            redirectOnSuccessAction.execute();
                                        }
                                    }
                                ).fail(
                                    function (response) {
                                        errorProcessor.process(response, self.messageContainer);
                                    }
                                ).always(function () {
                                    fullScreenLoader.stopLoader();
                                });
                            }
                        }
                    );
                });
            },

            debitCardTypeCustom: function() {

                let showType = this.showType();
                let creditCardNumber = this.creditCardNumber();
                let creditCardType = $('.debitcard-type');

                if (!showType && typeof creditCardNumber === undefined) {
                    return '';
                }

                if (!showType && creditCardType.length === 0) {
                    card.forceRegisterDebitCardType(creditCardNumber, creditCardType);

                    return creditCardType.val();
                }

                if (!showType) {
                    return creditCardType.val();
                }

                return this.creditCardType();
            },

            getData: function () {

                if (sopt.isActive('braspag_pagador_creditcard') && this.isSoptActive()) {

                    return {
                        'method': this.item.method,
                        'additional_data': {
                            'cc_cid': this.creditCardVerificationNumber(),
                            'cc_type': this.debitCardTypeCustom(),
                            'cc_owner': this.creditCardOwner(),
                            'cc_soptpaymenttoken': this.creditCardSoptPaymentToken(),
                            'authentication_failure_type': this.bpmpiAuthFailureType(),
                            'authentication_cavv': this.bpmpiAuthCavv(),
                            'authentication_xid': this.bpmpiAuthXid(),
                            'authentication_eci': this.bpmpiAuthEci(),
                            'authentication_version': this.bpmpiAuthVersion(),
                            'authentication_reference_id': this.bpmpiAuthReferenceId()
                        }
                    };
                }

                return {
                    'method': this.item.method,
                    'additional_data': {
                        'cc_cid': this.creditCardVerificationNumber(),
                        'cc_type': this.debitCardTypeCustom(),
                        'cc_exp_year': this.creditCardExpYear(),
                        'cc_exp_month': this.creditCardExpMonth(),
                        'cc_number': this.creditCardNumber(),
                        'cc_owner': this.creditCardOwner(),
                        'authentication_failure_type': this.bpmpiAuthFailureType(),
                        'authentication_cavv': this.bpmpiAuthCavv(),
                        'authentication_xid': this.bpmpiAuthXid(),
                        'authentication_eci': this.bpmpiAuthEci(),
                        'authentication_version': this.bpmpiAuthVersion(),
                        'authentication_reference_id': this.bpmpiAuthReferenceId()
                    }
                };
            },

            getCode: function() {
                return 'braspag_pagador_debitcard';
            },

            isActive: function() {
                return true;
            },

            loadDebitCardForm: function() {

                if (!cardView.isDebitCardViewEnabled()) {
                    return false;
                }

                new Card({
                    form: document.querySelector('.braspag-debitcard'),
                    container: '.debitcard-wrapper',
                    formSelectors: {
                        numberInput: 'input[name="payment[cc_number]',
                        expiryInput: 'input[name="payment[cc_exp_date]"]',
                        cvcInput: 'input[name="payment[cc_cid]"]',
                        nameInput: 'input[name="payment[cc_owner]"]'
                    },
                    debug: true,
                    placeholders: {
                        number: '&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;',
                        cvc: '&bull;&bull;&bull;',
                        expiry: '&bull;&bull;/&bull;&bull;',
                        name: 'Nome no cartão'
                    },
                    messages: {
                        validDate: 'sequência\nválida',
                        monthYear: 'mês/ano'
                    }
                });
            },

            updateCreditCardExpData: function () {

                let cardExpMonth = (this.creditCardExpMonth() != undefined ? this.pad(this.creditCardExpMonth(), 2) : '••');
                let cardExpYear = (this.creditCardExpYear() != undefined ? this.creditCardExpYear() : '••');
                let cardExpDate = cardExpMonth + '/' + cardExpYear;
                this.creditCardExpDate(cardExpDate);

                /**
                 * @TODO alterar o card expiry para text ao inves de select
                 */
                if (cardView.isDebitCardViewEnabled()) {
                    $('.debitcard-wrapper .jp-card-expiry').empty().append(cardExpDate);
                }
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

            getPlaceOrderDeferredObject: function () {

                let debitCardNumber = this.creditCardNumber();
                let debitCardType = $('.debitcard-type');

                card.forceRegisterDebitCardType(debitCardNumber, debitCardType);

                this.updateCreditCardExpData();
                var self = this;
                if (! (sopt.isActive('braspag_pagador_creditcard') && this.isSoptActive())) {
                    return $.when(
                        placeOrderAction(this.getData(), this.messageContainer)
                    ).fail(
                        function () {
                            self.isPlaceOrderActionAllowed(true);
                        }
                    ).done(
                        function (orderId) {
                            if (SuperDebito.isActive(self.getCode())) {
                                return self.placeOrderWithSuperDebito(orderId);
                            }

                            if (orderId.length == 0) {
                                errorProcessor.process("O pagamento não pôde ser finalizado.", self.messageContainer);
                            } else {
                                fullScreenLoader.startLoader();
                                $.when(
                                    RedirectAfterPlaceOrder(orderId)
                                ).done(
                                    function (url) {
                                        if (self.redirectAfterPlaceOrder && url.length) {
                                            window.location.replace(url);
                                            return true;
                                        }

                                        if (self.redirectAfterPlaceOrder) {
                                            redirectOnSuccessAction.execute();
                                        }
                                    }
                                ).fail(
                                    function (response) {
                                        errorProcessor.process(response, self.messageContainer);
                                    }
                                ).always(function () {
                                    fullScreenLoader.stopLoader();
                                });
                            }
                        }
                    );
                }

                this.updateCreditCardSoptPaymentToken();
            },

            isBpmpiEnabled: function() {
                return window.checkoutConfig.payment.dcform.bpmpi_authentication.active;
            },

            isBpmpiMasterCardNotifyOnlyEnabled: function() {
                return window.checkoutConfig.payment.dcform.bpmpi_authentication.mastercard_notify_only;
            },

            bpmpiPlaceOrderInit: function() {
                var self = this;

                if (self.isBpmpiEnabled()) {
                    if (self.bpmpiInitControl >= 1) {
                        return false;
                    }
                    self.bpmpiInitControl = 1;

                    $('.bpmpi_auth_failure_type').change(function () {

                        if (!$("#" + self.item.method).is(':checked')) {
                            return false;
                        }

                        self.bpmpiAuthFailureType($('.bpmpi_auth_failure_type').val());
                        self.bpmpiAuthCavv($('.bpmpi_auth_cavv').val());
                        self.bpmpiAuthXid($('.bpmpi_auth_xid').val());
                        self.bpmpiAuthEci($('.bpmpi_auth_eci').val());
                        self.bpmpiAuthVersion($('.bpmpi_auth_version').val());
                        self.bpmpiAuthReferenceId($('.bpmpi_auth_reference_id').val());

                        self.getPlaceOrderDeferredObject();
                        fullScreenLoader.stopLoader();

                        return false;
                    });
                }

                return false;
            },

            bpmpiPopulateDebitcard: function(){

                bpmpiRenderer.renderBpmpiData('bpmpi_paymentmethod', false, 'Debit');
                bpmpiRenderer.renderBpmpiData('bpmpi_auth', false, true);
                bpmpiRenderer.renderBpmpiData('bpmpi_cardnumber', false, this.creditCardNumber().replace(/\D/g,''));
                bpmpiRenderer.renderBpmpiData('bpmpi_billto_contactname', false, this.creditCardOwner());
                bpmpiRenderer.renderBpmpiData('bpmpi_cardexpirationmonth', false, this.creditCardExpMonth());
                bpmpiRenderer.renderBpmpiData('bpmpi_cardexpirationyear', false, this.creditCardExpYear());
                bpmpiRenderer.renderBpmpiData('bpmpi_installments', false, 1);
                bpmpiRenderer.renderBpmpiData('bpmpi_auth_notifyonly', false, this.isBpmpiMasterCardNotifyOnlyEnabled());

                return true;
            },

            bpmpiPopulateAdditionalData: function(){

                bpmpiRenderer.renderBpmpiData('bpmpi_mdd1', false, window.checkoutConfig.payment.dcform.bpmpi_authentication.mdd1);
                bpmpiRenderer.renderBpmpiData('bpmpi_mdd2', false, window.checkoutConfig.payment.dcform.bpmpi_authentication.mdd2);
                bpmpiRenderer.renderBpmpiData('bpmpi_mdd3', false, window.checkoutConfig.payment.dcform.bpmpi_authentication.mdd3);
                bpmpiRenderer.renderBpmpiData('bpmpi_mdd4', false, window.checkoutConfig.payment.dcform.bpmpi_authentication.mdd4);
                bpmpiRenderer.renderBpmpiData('bpmpi_mdd5', false, window.checkoutConfig.payment.dcform.bpmpi_authentication.mdd5);

                return true;
            },

            placeOrder: function (data, event) {

                var self = this;

                if (!this.validateForm('#'+ this.getCode() + '-form')) {
                    return;
                }

                if (event) {
                    event.preventDefault();
                }

                if (! (this.validate() && additionalValidators.validate())) {
                    return false;
                }

                if (this.validate() && additionalValidators.validate()) {
                    this.isPlaceOrderActionAllowed(false);

                    fullScreenLoader.startLoader();

                    if (!self.isBpmpiEnabled()) {
                        self.getPlaceOrderDeferredObject();
                        fullScreenLoader.stopLoader();
                        return false;
                    }

                    self.bpmpiPopulateDebitcard();
                    self.bpmpiPopulateAdditionalData();

                    authentication3ds20.bpmpiAuthenticate()
                        .then(function (data){
                            return false;
                        }).catch(function(){
                            fullScreenLoader.stopLoader();
                            return false;
                        });
                }

                return false;
            },

            placeOrderWithSuperDebito: function (orderId) {

                var self = this;

                this.prepareData();

                if (! this.validateForm('#'+ this.getCode() + '-form')) {
                    return;
                }

                var options = {
                    onInitialize: function(response) {
                        console.log(response);
                    },
                    onNotAuthenticable: function (response) {
                        console.log(response);
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
                        console.log(response);
                    },
                    onNotAuthorize: function(response) {
                        console.log(response);
                    },
                    onFinalize: function(response) {
                        console.log(response);
                    }
                };

                SuperDebito.start(options);

                fullScreenLoader.startLoader();
                $.when(
                    RedirectAfterPlaceOrder(orderId)
                ).done(
                    function (url) {
                        if (self.redirectAfterPlaceOrder && url.length) {
                            window.location.replace(url);
                            return true;
                        }

                        if (self.redirectAfterPlaceOrder) {
                            redirectOnSuccessAction.execute();
                        }
                    }
                ).fail(
                    function (response) {
                        errorProcessor.process(response, self.messageContainer);
                    }
                ).always(function () {
                    fullScreenLoader.stopLoader();
                });
            },
            showType: function () {
                return window.checkoutConfig.payment.braspag.isTestEnvironment == '1' && !cardView.isDebitCardViewEnabled();
            },

        });
    }
);
