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
        'Magento_Checkout/js/model/url-builder',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (storage, urlBuilder, errorProcessor, fullScreenLoader) {
        'use strict';

        return function (orderId) {
            fullScreenLoader.startLoader();
            var serviceUrl;

            serviceUrl = urlBuilder.createUrl('/braspag/redirect-after-placeorder/:orderId/link', {
                orderId: orderId
            });

            return storage.post(
                serviceUrl, false
            ).done(
                function (url) {
                    window.location.replace(url);
                }
            ).fail(
                function (response) {
                    errorProcessor.process(response, messageContainer);
                    fullScreenLoader.stopLoader();
                }
            );
        };
    }
);
