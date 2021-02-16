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
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';
        
        return Component.extend({
            defaults: {
                template: 'Webjump_BraspagPagador/payment/boleto',
                demonstrative: window.checkoutConfig.payment.braspag_pagador_boleto.info.demonstrative
            }
        });
    }
);