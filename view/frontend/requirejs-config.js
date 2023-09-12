/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
var config = {
	shim: {
		"Braspag_BraspagPagador/js/vendor/superdebitoLib": {
			export: 'superdebito',
			init: function () {
				return {
					superdebito: superdebito
				}
			}
		}
	},
	map: {
		"*": {
			'Magento_Checkout/js/action/place-order': 'Braspag_BraspagPagador/js/action/place-order',
			'Magento_Checkout/js/view/billing-address':'Braspag_BraspagPagador/js/view/billing-address',
		}
	}
}
