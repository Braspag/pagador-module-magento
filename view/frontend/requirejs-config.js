/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
var config = {
    shim:{
        'Webjump_BraspagPagador/js/vendor/silentorderpost' : {
        	exports:'bpSop_silentOrderPost',
        	init: function () {
        		return {
        			bpSop_silentOrderPost: bpSop_silentOrderPost
        		}
        	}
        }
    }
};