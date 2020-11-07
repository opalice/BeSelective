define([
    "jquery",
    'ko',
    'Ibapi_Multiv/js/date_fns',
    
      'Magento_Customer/js/customer-data',
      'Magento_Ui/js/modal/modal',

      'Magento_Catalog/js/price-utils',

      'mage/mage',

      'mage/translate',
      'Ibapi_Multiv/js/jquery.cookie',
      'Ibapi_Multiv/js/jquery.blockUI'


], function ($,ko,dateFns,customerData,modal,pU) {
	var intv=0;
	var self;
	var op=false;
	var inited=false;
	var timeleft=ko.observable(0)
	var sections=false;
	intv=setInterval(function(){

		
		customerData.reload('carttimer').done(function(x){
			var vk=x.carttimer.carttimer;
			if(!vk){
				return;
			}
			if(vk==0){
				clearInterval(intv);
				return;
			}
			if(vk==-1){
				location.reload();
				return;
			}
			mk=vk.split(':');
			if(parseInt(mk[1])<5&&!$('#multiv_extra').is(':visible')){
				$('[data-block="minicart"]').find('[data-role="dropdownDialog"]').dropdownDialog("open");

			}
			mk.shift()
			timeleft(mk.join(':'));
			
		});	
		
	},2000);

	
	
	
	function update(){
		this.cart = customerData.get('cart');
		
		var ct=customerData.get('carttimer');
		this.carttimer(ct);
	//	console.log('carttimer',ct());
		//console.log('cartx',this.cart());
		
		if(!this.cart||typeof this.cart=='undefined'){
			this.cart=[];
		}
		
		$.each(this.cart().items,function(i,item){
			
//			console.log('item',item);
		});
		
	}
	
	
    return {
    	
    	showTimer: timeleft
    	
    }
            	
	

});