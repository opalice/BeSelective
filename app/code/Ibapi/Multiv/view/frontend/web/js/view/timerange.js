define(
    [
        'ko',
        'jquery',
        'uiComponent',
        'underscore',

        'Magento_Checkout/js/model/quote',
//        'Magento_Customer/js/customer-data',
 //       'Magento_Customer/js/model/customer',
        'mage/url',
        'Magento_Checkout/js/model/step-navigator',
        'Ibapi_Multiv/js/date_fns',
        'Magento_Ui/js/modal/alert',
        'mage/translate',

        'Ibapi_Multiv/js/jquery.blockUI'



    ],
    function (
        ko,
        $,
        Component,
        _,
        quote,
///        customerData,
     //   storage,

        urlBuilder,
        stepNavigator,
        date_fns,
       
        alert,
        $t
    ) {
        'use strict';
        console.log('timerange step');
        
        return Component.extend({
            defaults: {
                template: 'Ibapi_Multiv/timerange'
            },
            st:ko.observable(''),
            et:ko.observable(''),
            
            //add here your logic to display step,
            isVisible: ko.observable(true),
            
            /**
			*
			* @returns {*}
			*/
            initialize: function () {
                this._super();
                // register your step
                
                var sh=false;
                var ti='';
               _.each(quote.getItems(),function(item){
            	 console.log('checking',item.sku)
            	   if(item.sku.indexOf('res-')==0){
            		   ti=item.sku;
            		   sh=true;
            	   }
            	   
               },this);
               
               this.isVisible(sh);
               if(!sh){
            	   return;
               } 
               var tis=ti.split('-')

               console.log('tii',tis[2],tis[3]-1,tis[4],tis[5])
               var ndt=new Date(parseInt(tis[2]),parseInt(tis[3])-1,parseInt(tis[4]));
               this.st(date_fns.format(ndt,'YYYY-MM-DD'));
               ndt=date_fns.addDays(ndt,parseInt(tis[5]-1))
               this.et(date_fns.format(ndt,'YYYY-MM-DD'));
               
                stepNavigator.registerStep(
                    //step code will be used as step content id in the component template
                    'step_timerange',
                    //step alias
                    null,
                    //step title value
                    'Time Range',
                    //observable property with logic when display step or hide step
                    this.isVisible,

                    _.bind(this.navigate, this),

                    /**
					* sort order value
					* 'sort order value' < 10: step displays before shipping step;
					* 10 < 'sort order value' < 20 : step displays between shipping and payment step
					* 'sort order value' > 20 : step displays after payment step
					*/
                    1
                );

                return this;
            },

            /**
			* The navigate() method is responsible for navigation between checkout step
			* during checkout. You can add custom logic, for example some conditions
			* for switching to your custom step
			*/
            navigate: function () {

            	/*
            	$.each(quote.getItems(),function(i,v){
            		
            		console.log('q',i,v);
            		
            		
            	});
            	*/
            	
///            	console.log('cart',customerData.get('cart')().items);
            },

            /**
			* @returns void
			*/
            navigateToNextStep: function () {
                
            	/////var cart=customerData.get('cart');
            	
///            	$.blockUI({message:$t('Saving Time preference.')})
            	
            	
            	var url= urlBuilder.build('multiv/index/cart');
            	stepNavigator.next();
            	
            		/*
            	$.ajax({
            		url:url,
            		data:{
            			st:$('#multiv_st').val(),
            			et: $('#multiv_et').val(),
            			range:1
            		}
            		
            	}).done(function(){

            		stepNavigator.next();
            		
            	}).fail(function(){
         		   alert({
       		        title: $t('Error'),
       		        content: $t('Error in updating timepreference.'),
       		        actions: {
       		            always: function(){}
       		        }
       		    });

            		
            	}).always(function(){
               	 
            		$.unblockUI()

            		
            	});
            	*/
            	
            }
        });
    }
);