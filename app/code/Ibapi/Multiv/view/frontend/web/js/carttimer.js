define([
    "jquery",
    'ko',
    'uiComponent',
    'Ibapi_Multiv/js/carttimer2',
    'Ibapi_Multiv/js/date_fns',
    
      'Magento_Customer/js/customer-data',
      'Magento_Ui/js/modal/modal',

      'Magento_Catalog/js/price-utils',

      'mage/mage',

      'mage/translate',
      'Ibapi_Multiv/js/jquery.cookie',
      'Ibapi_Multiv/js/jquery.blockUI'


], function ($,ko,Component,carttimer,dateFns,customerData,modal,pU) {
	var intv=0;
	var self;
$(function(){
	

		$('input[data-cart-item-id^="res-"]').attr('disabled',true)
			

	
});
	
	function update(){
		this.cart = customerData.get('cart');
		
		var ct=customerData.get('carttimer');
		this.carttimer(ct);
//		console.log('carttimer',ct());
	//	console.log('cartx',this.cart());
		
		if(!this.cart||typeof this.cart=='undefined'){
			this.cart=[];
		}
		
		$.each(this.cart().items,function(i,item){
			
		///	console.log('item',item);
		});
		
	}
    var module = {
    		timeleft:ko.computed(function(){
    			return carttimer.showTimer()
    		}),
    default:{
    			timeleft:ko.observable(0)
    		},
    		
    		initialize:function(){
    			this._super();
//    			console.log('carttimer1',carttimer.showTimer());
    			
    		}
    		
    };
    return Component.extend(module);
            	
	

});