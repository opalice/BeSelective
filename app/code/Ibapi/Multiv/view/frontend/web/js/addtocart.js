	define([
	    'jquery',
	    'mage/translate',
	    'Magento_Customer/js/model/customer',
	    'mage/utils/wrapper',
	    'jquery/ui'

	],
function($,$t,customer,wrapper){
    'use strict';
    console.log('widgetinit',$.mage.catalogAddToCart);

    	$.widget('multiv.catalogAddToCart',$.mage.catalogAddToCart,{
    		activate:function(){
    			this._super();

    	    	var newsub=wrapper.wrap(this.submitForm,function(t,form){
    	    		var cd=false;
    	    		    		var cd=customer.isLoggedIn();
    	    		if(cd==false||typeof cd=='undefined'){
 
    	    			
    	    			
    	    			return '';
    	    		}else{

    	    		}
    	    		setTimeout(function(){
    	    			
    	    		},1000);
    	    		return t(form);
    	    	});
    	    	this.submitForm=newsub;

    			
    		}
    		
    		
    	});
	return $.multiv.catalogAddToCart;
    
            	
            	

        
    
});