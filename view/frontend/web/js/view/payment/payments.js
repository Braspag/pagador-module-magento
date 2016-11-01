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
                component: 'Webjump_BraspagPagador/js/view/payment/method-renderer/billet'
            },
            {
                type: 'braspag_pagador_creditcard',
                component: 'Webjump_BraspagPagador/js/view/payment/method-renderer/creditcard'
            }
        );
        return Component.extend({});
    }
);