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
    	"Webjump_BraspagPagador/js/vendor/superdebitoLib"
    ],
    function(superdebitoLib) {
        'use strict';

        return {
			
        	isActive: function (code) {
        		return window.checkoutConfig.payment.dcform.superdebito.active[code];
        	},

            getMerchantId: function () {
                return window.checkoutConfig.payment.braspag.merchantId;
            },

        	start: function (options) {
                options.merchantId = this.getMerchantId();
                options.log = true;

        		superdebitoLib.superdebito(options);
        	}

        };
    }
);
