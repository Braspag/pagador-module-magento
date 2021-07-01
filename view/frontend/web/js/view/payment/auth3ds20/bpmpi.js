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
        'uiElement',
        'jquery',
        'Webjump_BraspagPagador/js/action/auth3ds20/token',
        'mage/translate',
        'Magento_Checkout/js/model/quote',
        'mage/validation',
        'mage/url',
        'ko',
        'Magento_Checkout/js/model/full-screen-loader',
        'Webjump_BraspagPagador/js/model/authentication3ds20',
        'Webjump_BraspagPagador/js/view/payment/auth3ds20/bpmpi-renderer'
    ],
    function (
        Component,
        $,
        authToken,
        $t,
        quote,
        mageValidation,
        mageUrl,
        ko,
        fullScreenLoader,
        authentication3ds20,
        bpmpiRenderer
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Webjump_BraspagPagador/payment/auth3ds20/bpmpi',
                bpmpiAuthToken: ko.observable(),
                bpmpiLoadControl: 0
            },

            initialize: function () {
                this._super();
            },

            bpmpiAuthLoad: function () {
                var self = this;
                self.processBpmpiData();

                authentication3ds20.bpmpiLoad();
            },

            getBpmpiAuthToken: function() {
                var self = this;

                if (self.bpmpiLoadControl > 0) {
                    return false;
                }
                self.bpmpiLoadControl = 1;

                if (!authentication3ds20.isBpmpiEnabled()) {
                    self.bpmpiAuthLoad();
                    return false;
                }

                fullScreenLoader.startLoader();
                $.when(
                    authToken()
                ).done(function (transport) {
                    _.map(transport, function (value, key) {
                        if (typeof value != "undefined") {
                            self.bpmpiAuthToken(value.token);
                        }
                    });

                    if (self.bpmpiAuthToken() == '') {
                        authentication3ds20.disableBpmpi();
                        return false;
                    }

                    self.bpmpiAuthLoad();

                }).always(function () {
                    fullScreenLoader.stopLoader();
                });
            },

            processBpmpiData: function() {
                var self = this;
                let bpmpiAuth = false;
                let bpmpiMasterCardNotifyOnly = false;

                if (authentication3ds20.isBpmpiEnabled()) {
                    bpmpiAuth = true;
                }

                if (authentication3ds20.isBpmpiMasterCardNotifyOnlyEnabled()) {
                    bpmpiMasterCardNotifyOnly = true;
                }

                bpmpiRenderer.renderBpmpiData('bpmpi_auth', false, bpmpiAuth);
                bpmpiRenderer.renderBpmpiData('bpmpi_auth_notifyonly', false, bpmpiMasterCardNotifyOnly);
                bpmpiRenderer.renderBpmpiData('bpmpi_totalamount', false, (quote.totals().grand_total*100));
                bpmpiRenderer.renderBpmpiData('bpmpi_currency', false, quote.totals().quote_currency_code);
                bpmpiRenderer.renderBpmpiData('bpmpi_ordernumber', false, quote.getQuoteId());
                bpmpiRenderer.renderBpmpiData('bpmpi_transaction_mode', false, 'S');
                bpmpiRenderer.renderBpmpiData('bpmpi_merchant_url', false, mageUrl.build('/'));

                var billAddressId = null;
                $.each(window.checkoutConfig.customerData.addresses, function(k, i){

                    if (i.default_billing) {

                        bpmpiRenderer.renderBpmpiData('bpmpi_billto_phonenumber', false, i.telephone);
                        bpmpiRenderer.renderBpmpiData('bpmpi_billto_customerid', false, window.checkoutConfig.customerData.taxvat);
                        bpmpiRenderer.renderBpmpiData('bpmpi_billto_email', false, window.checkoutConfig.customerData.email);
                        bpmpiRenderer.renderBpmpiData('bpmpi_billto_street1', false, i.street[0]+", "+i.street[1]);
                        bpmpiRenderer.renderBpmpiData('bpmpi_billto_street2', false, i.street[2]);
                        bpmpiRenderer.renderBpmpiData('bpmpi_billto_city', false, i.city);
                        bpmpiRenderer.renderBpmpiData('bpmpi_billto_state', false, i.region.region_code);
                        bpmpiRenderer.renderBpmpiData('bpmpi_billto_zipcode', false, i.postcode.replace(/[^a-zA-Z 0-9]+/g, ''));
                        bpmpiRenderer.renderBpmpiData('bpmpi_billto_country', false, i.country_id);
                        billAddressId = i.id;
                    }

                    if (i.default_shipping) {
                        bpmpiRenderer.renderBpmpiData('bpmpi_shipto_sameasbillto', false, (billAddressId == i.id ? true : false));
                        bpmpiRenderer.renderBpmpiData('bpmpi_shipto_addressee', false, i.firstname);
                        bpmpiRenderer.renderBpmpiData('bpmpi_shipto_phonenumber', false, i.telephone);
                        bpmpiRenderer.renderBpmpiData('bpmpi_shipto_email', false, window.checkoutConfig.customerData.email);
                        bpmpiRenderer.renderBpmpiData('bpmpi_shipto_street1', false, i.street[0]+", "+i.street[1]);
                        bpmpiRenderer.renderBpmpiData('bpmpi_shipto_street2', false, i.street[2]);
                        bpmpiRenderer.renderBpmpiData('bpmpi_shipto_city', false, i.city);
                        bpmpiRenderer.renderBpmpiData('bpmpi_shipto_state', false, i.region.region_code);
                        bpmpiRenderer.renderBpmpiData('bpmpi_shipto_zipcode', false, i.postcode);
                        bpmpiRenderer.renderBpmpiData('bpmpi_shipto_country', false, i.country_id);
                    }
                })

                var bpmpiDataCartItems = $('#bpmpi_data_cart');

                $.each(window.checkoutConfig.quoteItemData, function(k, i){
                    bpmpiRenderer.createInputHiddenElement(bpmpiDataCartItems, 'bpmpi_cart_description[]', 'bpmpi_cart_'+k+'_description', i.description);
                    bpmpiRenderer.createInputHiddenElement(bpmpiDataCartItems, 'bpmpi_cart_name[]', 'bpmpi_cart_'+k+'_name', i.name);
                    bpmpiRenderer.createInputHiddenElement(bpmpiDataCartItems, 'bpmpi_cart_sku[]', 'bpmpi_cart_'+k+'_sku', i.sku);
                    bpmpiRenderer.createInputHiddenElement(bpmpiDataCartItems, 'bpmpi_cart_quantity[]', 'bpmpi_cart_'+k+'_quantity', i.qty);
                    bpmpiRenderer.createInputHiddenElement(bpmpiDataCartItems, 'bpmpi_cart_unitprice[]', 'bpmpi_cart_'+k+'_unitprice', i.price*100);
                });

                bpmpiRenderer.renderBpmpiData('bpmpi_useraccount_guest', false, !window.checkoutConfig.isCustomerLoggedIn);
                bpmpiRenderer.renderBpmpiData('bpmpi_useraccount_createddate', false, window.checkoutConfig.customerData.created_at);
                bpmpiRenderer.renderBpmpiData('bpmpi_useraccount_changeddate', false, window.checkoutConfig.customerData.updated_at);
                bpmpiRenderer.renderBpmpiData('bpmpi_useraccount_authenticationmethod', false, '02');
                bpmpiRenderer.renderBpmpiData('bpmpi_useraccount_authenticationprotocol', false, 'HTTP');

                bpmpiRenderer.renderBpmpiData('bpmpi_device_ipaddress', false, window.checkoutConfig.quoteData.remote_ip);
            }

        });
    }
);
