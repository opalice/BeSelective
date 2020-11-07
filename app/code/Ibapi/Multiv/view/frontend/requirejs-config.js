var config = {
	'config': {
	    'mixins': {
		'Magento_Checkout/js/view/shipping': {
		    'Ibapi_Multiv/js/view/checkout-mixin': true
		},
		'Magento_Checkout/js/view/payment': {
		    'Ibapi_Multiv/js/view/checkout-mixin': true
		},
		'Magento_Review/js/view/review':{
			'Ibapi_Multiv/js/view/review-mixin': true			
		}
	    }
	}
}