/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Braspag_BraspagPagador/payment/pix',
                demonstrative: window.checkoutConfig.payment.braspag_pagador_pix.info.demonstrative
            },
            /**
             * Get payment method Logo.
             */
            getLogo: function () {
                return window.checkoutConfig.payment.logo[this.item.method];
            },
            /**
             * Display Title next to Logo
             */
            displayTitleLogo: function () {
                return window.checkoutConfig.payment.display_logo_title[this.item.method];
            }
        });
    }
);
