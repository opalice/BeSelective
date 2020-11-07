define(
    [
    	
        'Ibapi_Multiv/js/view/checkout/summary/discount'
    ],
    function (Component) {
        'use strict';
        console.log('total disc')
        return Component.extend({
 
            /**
             * @override
             */
            isDisplayed: function () {
                return true;
            }
        });
    }
);