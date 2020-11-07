define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote'
], function ($, ko, Component, quote) {
    'use strict';

    return Component.extend({
    	data:'',
        defaults: {
            template: 'Ibapi_Shipping/storepick',
            
            imports:{
            	
            	data:'${ $.provider }:data'
            },
            
        },
        initialize:function(){
        	
        	 this._super();
        	 console.log('storepick',this.options,this);
        	 console.log('storepickdata',this.data,this.mydata);
        	 return this;
        },
        

        initObservable: function () {
        	
        	
            this.selectedMethod = ko.computed(function() {
                var method = quote.shippingMethod();
                var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;
                return selectedMethod;
            }, this);

            return this;
        },
    });
});