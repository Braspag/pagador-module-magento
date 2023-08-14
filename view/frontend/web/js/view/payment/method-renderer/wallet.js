define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Braspag_BraspagPagador/payment/wallet'
            },
            getMailingAddress: function () {
                return window.checkoutConfig.payment.checkmo.mailingAddress;
            },
            getInstructions: function () {
                return window.checkoutConfig.payment.instructions[this.item.method];
            },
        });
    }
);

// /*
//  * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
//  *
//  * SPDX-License-Identifier: Apache-2.0
//  */
// /*browser:true*/
// /*global define*/
// define(
//     [
//         'Magento_Checkout/js/view/payment/default'
//     ],
//     function (Component) {
//         'use strict';

//         return Component.extend({
//             defaults: {
//                 template: 'Braspag_BraspagPagador/payment/wallet'
//                 //demonstrative: window.checkoutConfig.payment.braspag_pagador_wallet.info.demonstrative
//             },

//             initialize: function () {
//                 this._super();
//                // this.bpmpiPlaceOrderInit();
//             },

//         });

        
//     }
// );


