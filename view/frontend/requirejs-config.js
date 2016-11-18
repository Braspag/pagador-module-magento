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