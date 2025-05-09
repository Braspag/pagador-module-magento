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
        'Braspag_BraspagPagador/js/view/payment/method-renderer/creditcard/silentauthtoken',
        'jquery',
        'Magento_Checkout/js/action/place-order',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Braspag_BraspagPagador/js/action/redirect-after-placeorder',
        'Braspag_BraspagPagador/js/action/installments',
        'Magento_Checkout/js/action/redirect-on-success',
        'Magento_Checkout/js/model/quote',
        'ko',
        'Magento_Checkout/js/model/error-processor',
        'mage/validation',
        'mage/url',
        'Braspag_BraspagPagador/js/model/authentication3ds20',
        'Braspag_BraspagPagador/js/view/payment/auth3ds20/bpmpi-renderer',
        'Braspag_BraspagPagador/js/model/card.view',
        'Braspag_BraspagPagador/js/model/card',
        'Braspag_BraspagPagador/js/view/payment/method-renderer/creditcard/token',
        'uiRegistry',
    ],
    function (
        Component,
        $t,
        sopt,
        soptToken,
        $,
        placeOrderAction,
        fullScreenLoader,
        additionalValidators,
        RedirectAfterPlaceOrder,
        installments,
        redirectOnSuccessAction,
        quote,
        ko,
        errorProcessor,
        mageValidation,
        mageUrl,
        authentication3ds20,
        bpmpiRenderer,
        cardView,
        card,
        creditcardToken,
        uiRegistry
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Braspag_BraspagPagador/payment/creditcard',
                creditCardInstallments: '',
                creditCardsavecard: 0,
                creditCardExpDate: '',
                creditCardSoptPaymentToken: '',
                allInstallments: ko.observableArray([]),
                bpmpiInitControl: 0,
                bpmpiAuthFailureType: ko.observable(),
                bpmpiAuthCavv: ko.observable(),
                bpmpiAuthXid: ko.observable(),
                bpmpiAuthEci: ko.observable(),
                bpmpiAuthVersion: ko.observable(),
                bpmpiAuthReferenceId: ko.observable(),
                showCardElement: ko.observable(true),
                taxvatError: ko.observable(false),
                selectBrand:ko.observableArray([]),
                showBrandList: false,
                oldCardBrand : '',
                oldCardNumber: ''
            },

            initialize: function () {
                this._super();
                this.getCcInstallments();
                this.bpmpiPlaceOrderInit();
                card.init();
            },

            maskCvv: function (data, event) {
                var maxlength = 4;

                let creditCardType = $('.creditcard-type');

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

            clearCard : function () {
                var self = this;
                self.creditCardType(null);  
                self.creditCardNumber(null); 
                $('li.opc-progress-bar-item._complete').click(function () {
                    $('#braspag_pagador_creditcard_cc_number').val('');
                })
                $('#braspag_pagador_creditcard_cc_number').val('');

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
                        'creditCardExpYear',
                        'creditCardExpMonth',
                        'creditCardExpDate',
                        'creditCardTaxvat',
                        'creditCardVerificationNumber',
                        'creditCardInstallments',
                        'creditCardsavecard',
                        'creditCardSoptPaymentToken',
                        'creditCardToken',
                        'cardTokenValue',
                        'showBrandList',
                        'oldCardBrand',
                        'oldCardNumber'
                    ]);

                    $('input#braspag_pagador_creditcard_cc_number').val('');

                    this.creditCardType.subscribe(function () { 
                        let  value = self.creditCardAmount();

                        if(!Number.isInteger(value))
                        value = value.replace('.', '').replace(',', '.');

                        self.getCcInstallments(value);
                    

                    });

                    this.creditCardNumber.subscribe(function (value) { 
                
                        self.showBrandList(false);
                        
                        setTimeout(function(){ 
                             self.oldCardBrand( $('.creditcard-type').val()) 
                         }, 1000);

                         self.oldCardNumber(value);
                     });


                    this.selectBrand.subscribe(function (value) { 

                        $('.creditcard-type').val("Braspag-"+value.split('-')[1]);
                
                        if (self.oldCardBrand().split('-')[1] != undefined && self.oldCardBrand().split('-')[1] != value.split('-')[1] ) {
                           $('.card-wrapper .jp-card-container').hide();
                        }else if( self.oldCardBrand().split('-')[1] == value.split('-')[1]) {
                            $('.card-wrapper .jp-card-container').show();
                        }
                        

                    });

                    this.creditCardToken.subscribe(function (value) {
                        if (value == 'y_change_new') {
                         self.showCardElement(true)
                         self.cardTokenValue('');
                         setTimeout(() => {self.loadCreditCardForm()}, 2000);
                        } else {
                         self.showCardElement(false)
                         self.cardTokenValue(value);

                         $('.card-wrapper .jp-card-container').remove();
                         $('.card-wrapper').removeAttr('data-jp-card-initialized');
                        
                        }
                          
                     });

                return this;
            },

            getCode: function() {
                return 'braspag_pagador_creditcard';
            },

            isActive: function() {
                return true;
            },

            creditCardNumberType : function () {
                this.updateInstallments();
            },

            brandListOptions: function () { 
                return window.checkoutConfig.payment.ccform.braspag_pagador_creditcard.cc_types.split(",");
            },
            brandImage(brand) {
               return require.toUrl('Braspag_BraspagPagador/images/cc/' + brand.split('-')[1] + '.png');
            },
            loadCreditCardForm: function() {

                if (!cardView.isCreditCardViewEnabled())
                    return false;
                

                new Card({
                    form: document.querySelector('.braspag-card'),
                    container: '.card-wrapper',
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
                

               // this.getCcInstallments();
             
            },

            creditCardTypeCustom: function() {

                let showType = this.showType();
                let creditCardNumber = this.creditCardNumber();
                let creditCardType = $('.creditcard-type');

                if (!showType && creditCardNumber === undefined) {
                    return '';
                }

                if (!showType && creditCardType.length === 0 ) {
                    card.forceRegisterCreditCardType(creditCardNumber, creditCardType);

                    return creditCardType.val();
                }

                if (!showType) {
                    return creditCardType.val();
                }

                return this.creditCardType();
            },

            getData: function () {

                var data = {
                    'method': this.item.method,
                    'additional_data': {
                        'cc_cid': this.creditCardVerificationNumber (),
                        'cc_type': $('.creditcard-type').val(),
                        'cc_exp_year': this.creditCardExpYear(),
                        'cc_exp_month': this.creditCardExpMonth(),
                        'cc_number': this.creditCardNumber(),
                        'cc_owner': this.creditCardOwner(),
                        'cc_installments': this.creditCardInstallments(),
                        'cc_savecard': this.creditCardsavecard() ? 1 : 0,
                        'cc_soptpaymenttoken': this.creditCardSoptPaymentToken(),
                        'authentication_failure_type': this.bpmpiAuthFailureType(),
                        'authentication_cavv': this.bpmpiAuthCavv(),
                        'authentication_xid': this.bpmpiAuthXid(),
                        'authentication_eci': this.bpmpiAuthEci(),
                        'authentication_version': this.bpmpiAuthVersion(),
                        'authentication_reference_id': this.bpmpiAuthReferenceId(),
                        'cc_token': this.cardTokenValue(),
                        'cc_taxvat': this.creditCardTaxvat(),
                        'cc_installments_text' :$('select#braspag_pagador_creditcard_installments option:selected').text()
                    }
                };
                
                let towCardComponent = uiRegistry.get("two_card_braspag");
                
                if (towCardComponent != undefined) {
                    
                    data.additional_data.cc_amount_card2 = towCardComponent.creditCardAmount()
                    data.additional_data.cc_taxvat_card2 = towCardComponent.creditCardTaxvat()
                    data.additional_data.cc_exp_year_card2 = towCardComponent.creditCardExpYear()
                    data.additional_data.cc_exp_month_card2 = towCardComponent.creditCardExpMonth()
                    data.additional_data.cc_number_card2 = towCardComponent.creditCardNumber()
                    data.additional_data.cc_owner_card2 = towCardComponent.creditCardOwner()
                    data.additional_data.cc_installments_card2 = towCardComponent.creditCardInstallments()
                    data.additional_data.card_cc_token_card2 = towCardComponent.cardTokenValue()
                    data.additional_data.cc_installments_text_card2 = $('select#braspag_pagador_creditcard_two_card_installments option:selected').text()

                    if (towCardComponent.creditCardType() != undefined) {
                        data.additional_data.cc_type_card2 = $('.creditcard-type-two').val()
                    }
                }
              

                if (sopt.isActive(this.getCode()) && this.isSoptActive()) {
                    data = {
                        'method': this.item.method,
                        'additional_data': {
                            'cc_cid': this.creditCardVerificationNumber (),
                            'cc_type': $('.creditcard-type').val(),
                            'cc_owner': this.creditCardOwner(),
                            'cc_installments': this.creditCardInstallments(),
                            'cc_savecard': this.creditCardsavecard() ? 1 : 0,
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

                return data;
            },

            isInstallmentsActive: function () {
                return window.checkoutConfig.payment.ccform.installments.active[this.getCode()];
            },

        
            updateInstallments: function() {
                var self = this;
                let  towCardComponent = uiRegistry.get("two_card_braspag"), 
                amount = self.getGrandTotal(), 
                cardType = $('.creditcard-type').val(), 
                serviceUrl;

                if (towCardComponent ) {
                    if(towCardComponent.creditCardAmount())
                    amount -= towCardComponent.creditCardAmount().replace('.', '').replace(',' , '.');
                }
             
                self.loadCreditCardBrands();
            
                
                //if(cardType)  
                return installments(amount,cardType);

            },

            loadCreditCardBrands :function (){

               let self = this;

               let  cardType = $('.creditcard-type').val();

                if (cardType != undefined) {

                    self.showBrandList(true);
                    
                    let brandsObject =  this.brandListOptions();

                    for (let i = 0, len = brandsObject.length; i < len; i++) {
                        if (!brandsObject[i]) continue;
                      
                       if(cardType.split('-')[1] == brandsObject[i].split('-')[1]) {
                          self.selectBrand(brandsObject[i]);
                          break;
                       }
    
                    }

                }

                if(self.creditCardNumber() == undefined || self.creditCardNumber().length == 0)
                    self.showBrandList(false);

            },

            maskCPF: function() {
                $('#braspag_pagador_creditcard_cpf').mask('000.000.000-00');
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

            getGrandTotal: function () {
                let checkoutSumary = requirejs('Magento_Tax/js/view/checkout/summary/grand-total');
                return checkoutSumary().totals().grand_total;
             },


           getCcInstallments: function() {
                var self = this;

                fullScreenLoader.startLoader();
                $.when(
                    self.updateInstallments()
                ).done(function (transport) {
                    self.allInstallments.removeAll();

                    _.map(transport, function (value, key) {
                        self.allInstallments.push({
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

            isSaveCardActive: function() {
                return (window.isCustomerLoggedIn && window.checkoutConfig.payment.ccform.savecard.active[this.getCode()]);
            },

            getSaveCardHelpHtml: function () {
                return '<span>' + $t('Add To Braspag JustClick') + '</span>';
            },

            setCustomerTaxvat: function () {
                if(window.customerData.taxvat)
                 this.creditCardTaxvat(window.customerData.taxvat);
             },

            updateCreditCardSoptPaymentToken: function () {
                var self = this;

                fullScreenLoader.startLoader();

                $.when(
                    soptToken()
                ).done(function (transport) {

                    var options = {
                        holderName: self.creditCardOwner(),
                        rawNumber: self.creditCardNumber(),
                        expiration: self.creditCardExpDate(),
                        securityCode: self.creditCardVerificationNumber(),
                        code: self.getCode(),
                        authToken: transport,
                        successCallBack: function () {
                            fullScreenLoader.stopLoader();
                        },
                        failCallBack: function () {
                            fullScreenLoader.stopLoader();
                        }
                    };

                    self.creditCardSoptPaymentToken(sopt.getPaymentToken(options));

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

                            fullScreenLoader.startLoader();

                            if (orderId.length == 0) {
                                errorProcessor.process("O pagamento não pôde ser finalizado.", self.messageContainer);
                                fullScreenLoader.stopLoader();
                            } else {

                                fullScreenLoader.startLoader();
                                $.when(
                                    RedirectAfterPlaceOrder(orderId)
                                ).done(
                                    function (url) {
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

            getCcAvailableTokens: function () {
                let creditTokens = window.checkoutConfig.payment.ccform.tokens.list['braspag_pagador_creditcardtoken'];
                if (creditTokens ) {
                  //this.showCardElement(false);
                  return creditTokens;
                }

                 return false;
            },

            pad: function(num, size) {
                var s = "00" + num;
                return s.substr(s.length-size);
            },

            getPlaceOrderDeferredObject: function () {

                let creditCardNumber = this.creditCardNumber();
                let creditCardType = $('.creditcard-type');

              //  if (!this.cardTokenValue())
                //card.forceRegisterCreditCardType(creditCardNumber, creditCardType);

                var self = this;
                if (sopt.isActive(this.getCode()) && this.isSoptActive()) {
                    this.updateCreditCardExpData();
                    this.updateCreditCardSoptPaymentToken();
                } else {
                    $.when(
                        placeOrderAction(this.getData(), this.messageContainer)
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
                                        errorProcessor.process(response, self.messageContainer);
                                    }
                                ).always(function () {
                                    fullScreenLoader.stopLoader();
                                });
                            }
                        }
                    );
                }
            },

            isSoptActive: function () {
                return true;
            },

            isBpmpiEnabled: function() {
                return window.checkoutConfig.payment.ccform.bpmpi_authentication.active;
            },

            isBpmpiMasterCardNotifyOnlyEnabled: function() {
                return window.checkoutConfig.payment.ccform.bpmpi_authentication.mastercard_notify_only;
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
                        self.bpmpiCcType($('.creditcard-type').val());

                        self.getPlaceOrderDeferredObject();
                        fullScreenLoader.stopLoader();

                        return false;
                    });
                }

                return false;
            },

            bpmpiPopulateCreditcardData: function() {

                bpmpiRenderer.renderBpmpiData('bpmpi_paymentmethod', '', 'Credit');
                bpmpiRenderer.renderBpmpiData('bpmpi_auth', false, true);
                bpmpiRenderer.renderBpmpiData('bpmpi_cardnumber', false, this.creditCardNumber().replace(/\D/g,''));
                bpmpiRenderer.renderBpmpiData('bpmpi_billto_contactname', false, this.creditCardOwner());
                bpmpiRenderer.renderBpmpiData('bpmpi_cardexpirationmonth', false, this.creditCardExpMonth());
                bpmpiRenderer.renderBpmpiData('bpmpi_cardexpirationyear', false, this.creditCardExpYear());
                bpmpiRenderer.renderBpmpiData('bpmpi_installments', false, this.creditCardInstallments());
                bpmpiRenderer.renderBpmpiData('bpmpi_auth_notifyonly', false, this.isBpmpiMasterCardNotifyOnlyEnabled());

                return true;
            },

            bpmpiPopulateAdditionalData: function() {

                bpmpiRenderer.renderBpmpiData('bpmpi_mdd1', false, window.checkoutConfig.payment.ccform.bpmpi_authentication.mdd1);
                bpmpiRenderer.renderBpmpiData('bpmpi_mdd2', false, window.checkoutConfig.payment.ccform.bpmpi_authentication.mdd2);
                bpmpiRenderer.renderBpmpiData('bpmpi_mdd3', false, window.checkoutConfig.payment.ccform.bpmpi_authentication.mdd3);
                bpmpiRenderer.renderBpmpiData('bpmpi_mdd4', false, window.checkoutConfig.payment.ccform.bpmpi_authentication.mdd4);
                bpmpiRenderer.renderBpmpiData('bpmpi_mdd5', false, window.checkoutConfig.payment.ccform.bpmpi_authentication.mdd5);

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

                this.isPlaceOrderActionAllowed(false);

                fullScreenLoader.startLoader();

                if (!self.isBpmpiEnabled()) {
                    self.getPlaceOrderDeferredObject();
                    fullScreenLoader.stopLoader();
                    return true;
                }

                self.bpmpiPopulateCreditcardData();
                self.bpmpiPopulateAdditionalData();

                authentication3ds20.bpmpiAuthenticate()
                    .then(function (data){
                        return false;
                    }).catch(function(){
                        fullScreenLoader.stopLoader();
                        return false;
                    });

                return true;
            },

            /**
             * Get list of available credit card types
             * @returns {Object}
             */
            getCcAvailableTypes: function () {
                return window.checkoutConfig.payment.ccform.availableTypes[this.getCode()];
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
                return window.checkoutConfig.payment.ccform.months[this.getCode()];
            },

            /**
             * Get list of years
             * @returns {Object}
             */
            getCcYears: function () {
                return window.checkoutConfig.payment.ccform.years[this.getCode()];
            },

            /**
             * Check if current payment has verification
             * @returns {Boolean}
             */
            hasVerification: function () {
                return window.checkoutConfig.payment.ccform.hasVerification[this.getCode()];
            },

            /**
             * @deprecated
             * @returns {Boolean}
             */
            hasSsCardType: function () {
                return window.checkoutConfig.payment.ccform.hasSsCardType[this.getCode()];
            },

            /**
             * Get image url for CVV
             * @returns {String}
             */
            getCvvImageUrl: function () {
                return window.checkoutConfig.payment.ccform.cvvImageUrl[this.getCode()];
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
                return window.checkoutConfig.payment.ccform.ssStartYears[this.getCode()];
            },

            showType: function () {
               // return window.checkoutConfig.payment.braspag.isTestEnvironment == '1' && !cardView.isCreditCardViewEnabled();
               return false;
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

            getCcAvailableTokensValues: function() {

                let ccAvaliables = this.getCcAvailableTokens();
                let towCardComponent = uiRegistry.get("two_card_braspag");
            
                let tokensObj = {};

                if(towCardComponent != undefined) {
                    if (towCardComponent.cardTokenValue() != undefined ) {
                        _.map(ccAvaliables, function (value, key) {
                              if( key != towCardComponent.cardTokenValue()) {
                                tokensObj[key] = value;
                              }
                        });   
                        
                        return _.map(tokensObj,  function (value, key) {
                            return {
                              'token': key,
                              'alias': value
                          };
                      });

                    }
                }

                 _.map(ccAvaliables,  function (value, key) {
                    tokensObj[key] = value;
                });

               return _.map(tokensObj,  function (value, key) {
                      return {
                        'token': key,
                        'alias': value
                    };
                });



            },
        });
    }
);