define(
    [
    	
        'Ibapi_Multiv/js/view/checkout/summary/discount2'
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