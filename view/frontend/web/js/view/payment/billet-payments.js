define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'braspag_pagador_billet',
                component: 'Webjump_BraspagPagador/js/view/payment/method-renderer/billet-method'
            }
        );
        return Component.extend({});
    }
);