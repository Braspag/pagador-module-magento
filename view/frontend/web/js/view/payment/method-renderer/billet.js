/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
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
                template: 'Webjump_BraspagPagador/payment/billet',
                demonstrative: window.checkoutConfig.payment.braspag_pagador_billet.info.demonstrative
            }
        });
    }
);