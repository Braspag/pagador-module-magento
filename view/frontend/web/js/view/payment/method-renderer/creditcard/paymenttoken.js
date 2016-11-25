/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
define(
    [],
    function() {
        'use strict';
        var paymentToken = '';
        return {

            setPaymentToken: function(token) {
                paymentToken = token;
            },

            getPaymentToken: function() {
                return paymentToken;
            }

        };
    }
);
