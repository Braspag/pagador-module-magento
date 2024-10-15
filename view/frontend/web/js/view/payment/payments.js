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
                type: 'braspag_pagador_boleto',
                component: 'Braspag_BraspagPagador/js/view/payment/method-renderer/boleto'
            },
            {
                type: 'braspag_pagador_creditcard',
                component: 'Braspag_BraspagPagador/js/view/payment/method-renderer/creditcard'
            },
            {
                type: 'braspag_pagador_debitcard',
                component: 'Braspag_BraspagPagador/js/view/payment/method-renderer/debitcard'
            },
            {
                type: 'braspag_pagador_pix',
                component: 'Braspag_BraspagPagador/js/view/payment/method-renderer/pix'
            },
            {
                type: 'braspag_pagador_voucher',
                component: 'Braspag_BraspagPagador/js/view/payment/method-renderer/voucher'
            }
        );
        return Component.extend({});
    }
);
