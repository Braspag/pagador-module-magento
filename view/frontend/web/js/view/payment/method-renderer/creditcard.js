/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Payment/js/view/payment/cc-form',
    ],
    function (Component) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Webjump_BraspagPagador/payment/creditcard',
                creditCardInstallments: ''
            },

            getCode: function() {
                return 'braspag_pagador_creditcard';
            },

            isActive: function() {
                return true;
            },

            getCcInstallments: function() {
                return window.checkoutConfig.payment.ccform.installments[this.getCode()];
            },

            getCcInstallmentsValues: function() {
                return _.map(this.getCcInstallments(), function (value, key) {
                    return {
                        'value': key,
                        'installments': value
                    };
                });
            }

        });
    }
);
