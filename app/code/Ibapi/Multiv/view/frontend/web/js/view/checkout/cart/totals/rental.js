define(
    [
    	'ko',
    	'jquery',
    	'uiComponent',
    	'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/quote',
        'domReady!'

    	
    ],
    function (ko,$,Component,pU,quote) {
        'use strict';
        return Component.extend({
        visible:ko.observable(false),	
        rent:0,
        depo:0,
        cloth:ko.observable(false),
        default:{
        		template:'Ibapi_Multiv/checkout/cart/totals/rental'
        	
        	},
        	hasCloth:function(){
        		return this.cloth();
        	},
        	getPress:function(){
        		
        		return pU.formatPrice(parseFloat("0.0"));        		
        	},
        	getDepo:function(){
        	return pU.formatPrice(this.depo);

        	},
        	getRent:function(){
        			//return this.rent;
        			return pU.formatPrice(this.rent);        		
        	},
        	
        	
        	initialize: function () {
                this._super();
                var x=$('.tblextrasku');
                if(x.length){
                	this.visible(true);
                }
                $('.tblextrasku').each(function(i,t){
                	console.log('data',$(t).data('r'),$(t).data('d'),$(t))
                	this.rent+=parseFloat( $(t).data('r'));
                	this.depo+=parseFloat($(t).data('d'));
                	if($(t).data('c')==1){
                		this.cloth(true);
                	}
                	
                }.bind(this));
                
                
                
                
                
                //   this.cart = customerData.get('cart');
            },
 
            /**
             * @override
             */
            isDisplayed: function () {
            	
            	
                return this.visible();
            }
        });
    }
);