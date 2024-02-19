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
        'Magento_Checkout/js/view/payment/default',
        'mage/translate',
        'Braspag_BraspagPagador/js/view/payment/method-renderer/creditcard/silentorderpost',
        'jquery',
        'Magento_Checkout/js/model/full-screen-loader',
        'Braspag_BraspagPagador/js/action/installments',
        'ko',
        'mage/url',
        'Braspag_BraspagPagador/js/model/card.view',
        'Braspag_BraspagPagador/js/model/card',
        'Braspag_BraspagPagador/js/dist/jquery.mask.min',
        'Braspag_BraspagPagador/js/view/payment/method-renderer/creditcard/token',
        'Magento_Catalog/js/price-utils',
        'uiRegistry'
    ],
    function (
        Component,
        $t,
        sopt,
        $,
        fullScreenLoader,
        installments,
        ko,
        mageUrl,
        cardView,
        card,
        mask,
        creditcardToken,
        priceUtils,
        uiRegistry
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                creditCardInstallments: '',
                creditCardsavecard: 0,
                creditCardExpDate: '',
                creditCardSoptPaymentToken: '',
                allInstallments: ko.observableArray([]),
                isShowCard2: ko.observable(false),
                showCardElement: ko.observable(true),
                chooseCardTokenValue : '',
                cardNumberError: ko.observable(false),
                taxvatError: ko.observable(false),
                remaining: ko.observable(),
                creditCardToken:ko.observable(),
                grandTotal: window.checkoutConfig.quoteData.base_grand_total,
                triggerInputAmount : "input#braspag_pagador_creditcard_two_card_amount"
            },

            initialize: function () {
                this._super();
                this.getCcInstallments();
                card.init();
               // this.setCustomerTaxvat();
               // this.loadCreditCardForm();
                this.creditCardAmount(0);

            },


             maskCvv: function (data, event) {
                var maxlength = 4;

                let creditCardType = $('.creditcard-type-two');

                if (
                    creditCardType.val() === 'Cielo-Amex' ||
                    creditCardType.val() === 'CieloSitef-Amex'
                ){
                    maxlength = 4;
                }

                if (event.target.value.length >= maxlength) {
                    return false;
                }

                return true;
            },
            amountChange: function(e) {
                // event.stopImmediatePropagation();
 
                 var self = this;
                 let value = self.creditCardAmount();
                 let grandTotal = self.getGrandTotal() * 1;
             
                 
                 if(value.length <= 0 || value == 0 )
                   return this;
 
                 value =  value.replace('.', '').replace(',' , '.');
 
                 $(e.triggerInputAmount).val(
                     this.getItemPrice(value)
                 )
 
                 if(value < grandTotal) {
                     self.remaining(priceUtils.formatPrice( grandTotal - value) );
                     self.getCcInstallments( value);
                 
                 }else {
                     //let value = event.target.value.substr(0, event.target.value.length - 1);
                     self.creditCardAmount(null);
                     self.remaining(false);
                     self.allInstallments.removeAll();
                     uiRegistry.get("checkout.steps.billing-step.payment.payments-list.braspag_pagador_creditcard").allInstallments.removeAll();
                 }

                 $("#braspag_pagador_creditcard_two_card_amount").attr("maxlength", 20);
             },

             getGrandTotal: function () {
                let checkoutSumary = requirejs('Magento_Tax/js/view/checkout/summary/grand-total');
                return checkoutSumary().totals().grand_total;
             },

            validateForm: function (form) {
                return $(form).validation() && $(form).validation('isValid');
            },

            initObservable: function () {
             var self = this;
                this._super()
                    .observe([
                        'creditCardType',
                        'creditCardNumber',
                        'creditCardOwner',
                        'creditCardAmount',
                        'creditCardTaxvat',
                        'creditCardExpYear',
                        'creditCardExpMonth',
                        'creditCardExpDate',
                        'creditCardVerificationNumber',
                        'creditCardInstallments',
                        'creditCardsavecard',
                        'creditCardSoptPaymentToken',
                        'checkboxToggle',
                        'creditCardTokenOld',
                        'cardTokenValue',
                    ]);

                    this.checkboxToggle.subscribe(function (value) {
                        if(value){
                            self.isShowCard2(true);
                            let value = $(self.triggerInputAmount).val();
                            $(".cpf-card").show().addClass('required').find('input').addClass('required');
                            
                            if (value.length > 0)
                              self.getCcInstallments(value)

                            // if (self.showCardElement())
                            //   self.loadCreditCardForm()

                        } else {
                          self.clearAll();
                        }
                    });

                    this.creditCardToken.subscribe(function (value , event) {

                        if (self.isShowCard2()) {
                           
                            if (value == 'y_change_new') {
                                self.cardTokenValue('');
                                self.showCardElement(true);
                                setTimeout(() => {self.loadCreditCardForm()}, 2000);
                              } else {

                                // if ($('.card-wrapper-two-card .jp-card-container').length > 0 ) {
                                //     $('.card-wrapper-two-card .jp-card-container').remove();
                                //     $('.card-wrapper-two-card').removeAttr('data-jp-card-initialized');
                                // }
                              
                               self.cardTokenValue(value);
                               self.showCardElement(false);
                               let creditComponent = uiRegistry.get("checkout.steps.billing-step.payment.payments-list.braspag_pagador_creditcard");
                                if (creditComponent != undefined) {
                                   creditComponent.getCcAvailableTokensValues();
                                }
       
                              }
                        } 
                     
                         
                    });

                    this.creditCardType.subscribe(function () { 
                        let  value = self.creditCardAmount();

                        if(!Number.isInteger(value))
                        value = value.replace('.', '').replace(',', '.');

                        self.getCcInstallments(value)
                    });

                    ko.computed(function() {
                        return ko.toJSON(self.getGrandTotal());
                    }).subscribe(function() {
                       self.checkboxToggle(false);
                       self.creditCardToken('');
                       
                    });

                return this;
            },

            isSaveCardActive: function() {
                return (window.isCustomerLoggedIn && window.checkoutConfig.payment.ccform.savecard.active[this.getCode()]);
            },

            getCodeMethod: function() {
                return 'braspag_pagador_creditcard';
            },

            
            getCode: function() {
                return 'braspag_pagador_creditcard_two_card';
            },

            isActive: function() {
                return window.checkoutConfig.payment.ccform.tow_card.active.braspag_pagador_creditcard;
            },

            loadCreditCardForm: function() {

                if (!cardView.isCreditCardViewEnabled()) {
                    return false;
                }

                 new Card({
                        form: document.querySelector('.braspag_pagador_creditcard_two_card'),
                        container: '.card-wrapper-two-card',
                        formSelectors: {
                            numberInput: 'input[name="payment[cc_number_card2]',
                            expiryInput: 'input[name="payment[cc_exp_date_2]"]',
                            cvcInput: 'input[name="payment[cc_cid_card2]"]',
                            nameInput: 'input[name="payment[cc_owner_card2]"]'
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

            clearAll: function() {
                var self = this;

                if(self.checkboxToggle())
                self.checkboxToggle(false);
                
                self.allInstallments.removeAll();
                self.creditCardAmount(null);
                self.remaining(false);
                self.getCcInstallments(false);

                setTimeout(() => {self.isShowCard2(false)}, 3000);
                $(".cpf-card").hide().removeClass('required').find('input').removeClass('required');
            },

            creditCardTypeCustom: function() {

                let showType = this.showType();
                let creditCardNumber = this.creditCardNumber();
                let creditCardType = $('.creditcard-type-two');

                if (!showType && typeof creditCardNumber === undefined) {
                    return '';
                }

                if (!showType && creditCardType.length === 0) {
                    card.forceRegisterCreditCardType(creditCardNumber, creditCardType);

                    return creditCardType.val();
                }

                if (!showType) {
                    return creditCardType.val();
                }

                return this.creditCardType();
            },

            setCustomerTaxvat: function () {
                 if(window.customerData.taxvat)
                  this.creditCardTaxvat(window.customerData.taxvat);
            },

            maskValue: function(data, event){
                $('#braspag_pagador_creditcard_two_card_amount').mask('#.##0,00', {reverse: true});
            },

            maskCPF: function() {
                $('#braspag_pagador_creditcard_two_card_cpf').mask('000.000.000-00');
            },

            validCPF: function (data, event) {
                let self  = this;
                let cpf = event.target.value;

                if(cpf.length <= 0)
                return this;

                var cpf_filtrado = "", valor_1 = " ", valor_2 = " ", ch = "", i;
                var valido = false;
                for (i = 0; i < cpf.length; i++) {
                    ch = cpf.substring(i, i + 1);
                    if (ch >= "0" && ch <= "9") {
                        cpf_filtrado = cpf_filtrado.toString() + ch.toString()
                        valor_1 = valor_2;
                        valor_2 = ch;
                    }
                    if ((valor_1 != " ") && (!valido))
                        valido = !(valor_1 == valor_2);
                }
                if (!valido)
                    cpf_filtrado = "12345678912";
                if (cpf_filtrado.length < 11) {
                    for (i = 1; i <= (11 - cpf_filtrado.length); i++) {
                        cpf_filtrado = "0" + cpf_filtrado;
                    }
                }

                if ((cpf_filtrado.substring(9, 11) == self._checkCPF(cpf_filtrado.substring(0, 9))) && (cpf_filtrado.substring(11, 12) == "")) {
                   self.taxvatError(false);
                   return true;
                }
                
                $(event.target).val('');
                self.taxvatError(true);
                setTimeout(() => {self.taxvatError(false)}, 3000);
            },

            _checkCPF: function (vCPF) {
                var mControle = ""
                var mContIni = 2, mContFim = 10, mDigito = 0, i, j, mSoma, mControle1;
                for (j = 1; j <= 2; j++) {
                    mSoma = 0;
                    for (i = mContIni; i <= mContFim; i++)
                        mSoma = mSoma + (vCPF.substring((i - j - 1), (i - j)) * (mContFim + 1 + j - i));
                    if (j == 2)
                        mSoma = mSoma + (2 * mDigito);
                    mDigito = (mSoma * 10) % 11;
                    if (mDigito == 10)
                        mDigito = 0;
                    mControle1 = mControle;
                    mControle = mDigito;
                    mContIni = 3;
                    mContFim = 11;
                }
    
                return ((mControle1 * 10) + mControle);
            },

            autoSelectBrand: function (data, event) {
                var self = this;
                let number = event.target.value;

                if(number.length > 0 && number == $("input#braspag_pagador_creditcard_cc_number").val()){
                    self.cardNumberError(true);
                    $(event.target).val('');
                    setTimeout(() => {self.cardNumberError(false)}, 3000);
                    return;
                }

                if (parseInt(number.substr(0,6)) === 960382) {
                    $('#braspag_pagador_creditcard_tow_card_cc_type_Credsystem').prop("checked", true);
                    this.creditCardType('Credsystem-Credsystem');
                    return;
                }
                if (parseInt(number.substr(0,1)) === 4) {
                    $('#braspag_pagador_creditcard_tow_card_cc_type_Rede2-Visa').prop("checked", true);
                    this.creditCardType('Rede2-Visa');
                    return;
                }
                if (parseInt(number.substr(0,1)) === 5) {
                    $('#braspag_pagador_creditcard_tow_card_cc_type_Rede2-Master').prop("checked", true);
                    this.creditCardType('Rede2-Master');
                    return;
                }
                if (parseInt(number.substr(0,2)) === 38 || parseInt(number.substr(0,2)) === 60) {
                    $('#braspag_pagador_creditcard_tow_card_cc_type_Rede2-Hiper').prop("checked", true);
                    this.creditCardType('Rede2-Hiper');
                    return;
                }
                if (
                    parseInt(number.substr(0,2)) === 36 ||
                    parseInt(number.substr(0,3)) === 301 ||
                    parseInt(number.substr(0,3)) === 305
                ) {
                    $('#braspag_pagador_creditcard_tow_card_cc_type_Rede2-Diners').prop("checked", true);
                    this.creditCardType('Rede2-Diners');
                    return;
                }
                if (
                    parseInt(number.substr(0,6)) === 636368 ||
                    parseInt(number.substr(0,6)) === 636369 ||
                    parseInt(number.substr(0,6)) === 438935 ||
                    parseInt(number.substr(0,6)) === 504175 ||
                    parseInt(number.substr(0,6)) === 451416 ||
                    parseInt(number.substr(0,6)) === 636297 ||
                    parseInt(number.substr(0,4)) === 5067 ||
                    parseInt(number.substr(0,4)) === 4576 ||
                    parseInt(number.substr(0,4)) === 4011 ||
                    parseInt(number.substr(0,6)) === 506699
                ) {
                    $('#braspag_pagador_creditcard_tow_card_cc_type_Rede2-Elo').prop("checked", true);
                    this.creditCardType('Rede2-Elo');
                    return;
                }
            },

            isInstallmentsActive: function () {
                return window.checkoutConfig.payment.ccform.installments.active[this.getCodeMethod()];
            },

            getCcInstallments: function(amount = false) {
                var triggerFunction , self = this;

            //    if(!self.creditCardType())
            //     return this;

               if(amount)
                triggerFunction = installments(amount, self.creditCardType());
                
                let bindInstallments = self.allInstallments;
           
                //call update installmenst card 
                let creditComponent = uiRegistry.get("checkout.steps.billing-step.payment.payments-list.braspag_pagador_creditcard");
                if(creditComponent != undefined) {
                    creditComponent.getCcInstallments();
                }
           
             
                fullScreenLoader.startLoader();
                $.when(
                    triggerFunction
                ).done(function (transport) {
                   
                    bindInstallments.removeAll();
                    _.map(transport, function (value, key) {
                        bindInstallments.push({
                            'value': value.id,
                            'installments': value.label
                        });
                    });

                }).always(function () {
                    fullScreenLoader.stopLoader();
                });
            },

            getCcInstallmentsValues: function() {
                return _.map(this.getCcInstallments(), function (value, key) {
                    return {
                        'value': key,
                        'installments': value
                    };
                });
            },

            /**
             *  Return item price
             */
             getItemPrice:function(data){
                return priceUtils.formatPrice(data);
            },

            isSaveCardActive: function() {
                return (window.isCustomerLoggedIn && window.checkoutConfig.payment.ccform.savecard.active[this.getCodeMethod()]);
            },

            getSaveCardHelpHtml: function () {
                return '<span>' + $t('Add To Braspag JustClick') + '</span>';
            },

         
            updateCreditCardExpData: function () {

                let cardExpMonth = (this.creditCardExpMonth() != undefined ? this.pad(this.creditCardExpMonth(), 2) : '••');
                let cardExpYear = (this.creditCardExpYear() != undefined ? this.creditCardExpYear() : '••');
                let cardExpDate = cardExpMonth + '/' + cardExpYear;
                this.creditCardExpDate(cardExpDate);

                /**
                 * @TODO alterar o card expiry para text ao inves de select
                 */
                if (cardView.isCreditCardViewEnabled()) {
                    $('.card-wrapper .jp-card-expiry').empty().append(cardExpDate);
                }
            },

            pad: function(num, size) {
                var s = "00" + num;
                return s.substr(s.length-size);
            },

           
            isSoptActive: function () {
                return true;
            },

           
            /**
             * Get list of available credit card types
             * @returns {Object}
             */
            getCcAvailableTypes: function () {
                return window.checkoutConfig.payment.ccform.availableTypes[this.getCodeMethod()];
            },

            /**
             * Get payment icons
             * @param {String} type
             * @returns {Boolean}
             */
            getIcons: function (type) {
                return window.checkoutConfig.payment.ccform.icons.hasOwnProperty(type) ?
                    window.checkoutConfig.payment.ccform.icons[type]
                    : false;
            },

            /**
             * Get list of months
             * @returns {Object}
             */
            getCcMonths: function () {
                return window.checkoutConfig.payment.ccform.months[this.getCodeMethod()];
            },

            /**
             * Get list of years
             * @returns {Object}
             */
            getCcYears: function () {
                return window.checkoutConfig.payment.ccform.years[this.getCodeMethod()];
            },

            /**
             * Check if current payment has verification
             * @returns {Boolean}
             */
            hasVerification: function () {
                return window.checkoutConfig.payment.ccform.hasVerification[this.getCodeMethod()];
            },

            /**
             * @deprecated
             * @returns {Boolean}
             */
            hasSsCardType: function () {
                return window.checkoutConfig.payment.ccform.hasSsCardType[this.getCodeMethod()];
            },

            /**
             * Get image url for CVV
             * @returns {String}
             */
            getCvvImageUrl: function () {
                return window.checkoutConfig.payment.ccform.cvvImageUrl[this.getCodeMethod()];
            },

            /**
             * Get image for CVV
             * @returns {String}
             */
            getCvvImageHtml: function () {
                return '<img src="' + this.getCvvImageUrl() +
                    '" alt="' + $t('Card Verification Number Visual Reference') +
                    '" title="' + $t('Card Verification Number Visual Reference') +
                    '" />';
            },

            /**
             * @deprecated
             * @returns {Object}
             */
            getSsStartYears: function () {
                return window.checkoutConfig.payment.ccform.ssStartYears[this.getCodeMethod()];
            },

            showType: function () {
                return window.checkoutConfig.payment.braspag.isTestEnvironment == '1' && !cardView.isCreditCardViewEnabled();
            },

            /**
             * Get list of available credit card types values
             * @returns {Object}
             */
            getCcAvailableTypesValues: function () {

                return _.map(this.getCcAvailableTypes(), function (value, key) {
                    return {
                        'value': key,
                        'type': value
                    };
                });
            },

            /**
             * Get list of available month values
             * @returns {Object}
             */
            getCcMonthsValues: function () {
                return _.map(this.getCcMonths(), function (value, key) {
                    return {
                        'value': key,
                        'month': value
                    };
                });
            },

            /**
             * Get list of available year values
             * @returns {Object}
             */
            getCcYearsValues: function () {
                return _.map(this.getCcYears(), function (value, key) {
                    return {
                        'value': key,
                        'year': value
                    };
                });
            },

            /**
             * @deprecated
             * @returns {Object}
             */
            getSsStartYearsValues: function () {
                return _.map(this.getSsStartYears(), function (value, key) {
                    return {
                        'value': key,
                        'year': value
                    };
                });
            },

            /**
             * Is legend available to display
             * @returns {Boolean}
             */
            isShowLegend: function () {
                return false;
            },

            /**
             * Get available credit card type by code
             * @param {String} code
             * @returns {String}
             */
            getCcTypeTitleByCode: function (code) {
                var title = '',
                    keyValue = 'value',
                    keyType = 'type';

                _.each(this.getCcAvailableTypesValues(), function (value) {
                    if (value[keyValue] === code) {
                        title = value[keyType];
                    }
                });

                return title;
            },

            /**
             * Prepare credit card number to output
             * @param {String} number
             * @returns {String}
             */
            formatDisplayCcNumber: function (number) {
                return 'xxxx-' + number.substr(-4);
            },

            /**
             * Get credit card details
             * @returns {Array}
             */
            getInfo: function () {
                return [
                    {
                        'name': 'Credit Card Type', value: this.getCcTypeTitleByCode(this.creditCardType())
                    },
                    {
                        'name': 'Credit Card Number', value: this.formatDisplayCcNumber(this.creditCardNumber())
                    }
                ];
            },

            getCcAvailableTokens: function () {
                let creditTokens = window.checkoutConfig.payment.ccform.tokens.list['braspag_pagador_creditcardtoken'];
                if (creditTokens ) {
                 // this.showCardElement(false);
                  return creditTokens;
                }

                 return false;
            },

            getCcAvailableTokensValues: function() {
                return _.map(this.getCcAvailableTokens(), function (value, key) {
                    return {
                        'token': key,
                        'alias': value
                    };
                });
            },

        });
    }
);
