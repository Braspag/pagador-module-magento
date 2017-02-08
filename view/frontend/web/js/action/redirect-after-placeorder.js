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
        'mage/storage',
        'Magento_Checkout/js/model/url-builder'
    ],
    function (storage, urlBuilder) {
        'use strict';

        return function (orderId) {
            var serviceUrl;


            serviceUrl = urlBuilder.createUrl('/braspag/redirect-after-placeorder/:orderId/link', {
                orderId: orderId
            });

            return storage.post(
                serviceUrl, false
            );
        };
    }
);
