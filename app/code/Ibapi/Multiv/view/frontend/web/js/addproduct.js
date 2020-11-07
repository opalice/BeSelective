require([
    'jquery',
    'Magento_Customer/js/model/authentication-popup',

    ///    'Magento_Catalog/js/model/authentication-popup',
    'Magento_Customer/js/customer-data',
    'mage/url',
    'Magento_Ui/js/modal/alert',
    'mage/translate',

    'mage/mage',
    'Magento_Catalog/product/view/validation',
    'mage/cookies',
    'Ibapi_Multiv/js/jquery.blockUI',

    
    'catalogAddToCart'
], function ($,authenticationPopup, customer,mageurl,alert,$t) {
    ///'use strict';
    var mysub;
    var msg='Logged in. Adding rental product to cart.';
    var loggedin=false;
    try{
   console.log('opts',this);
        }catch(e){
        console.log('e',e);
    }
   function updateformkey(cb){
    	var key='';
    	$.ajax({
    		url:mageurl.build('multiv/index/cart'),
    		data:{'key':1},
    		type:'get',
    		dataType:'json'
    				
    	}).done(function(r){
    		
        	cb(r.key);
    		
    	}).fail(function(){
    		
    	});
    	
    }
    $(document).on('adddone',function(){
    	$.unblockUI();
    	
    });
    $(document).on('willadd',function(){
    	$.blockUI({message:$t(msg)});
    	
    });

    
    $('#product_addtocart_form').mage('validation', {
        radioCheckboxClosest: '.nested',
        submitHandler: function (form) {
            try{
            //console.log('options',form.rmsg.value);
            if(form.rmsg)
            msg=form.rmsg.value;

                }catch (e) {

                console.log(e);
            }
        	var cst=customer.get('customer');


            if(form.rmsg&&form.rmsg.value){

            }else {
                if (!$(form.rental_option).val()) {
                    $('.datedialog').trigger('click')
                    return;
                }
            }
            
            
            if (!cst().firstname) {
            console.log('not logged in');
            	
                authenticationPopup.showModal();
               mysub= cst.subscribe(function (updated) {
                	try{
                	
                if(updated.firstname){
                	console.log('disposing');
                	mysub.dispose();

                	$(authenticationPopup.modalWindow).modal('closeModal');

                	
                		updateformkey(function(key){
                	$(form).find('input[name="form_key"]').val(key);
                	
                	var widget = $(form).catalogAddToCart({
                        bindSubmit: false,
                        processStart: 'willadd',
                        processStop: 'adddone'
                    });
                    widget.catalogAddToCart('submitForm', $(form));

                		});
                    
                }
                	}catch(e){
                		
                	}
                
                }, this);

                console.log('nocust');
                return false;
            }
            //'ajax:addToCart'
				
            var widget = $(form).catalogAddToCart({
                bindSubmit: false
            });

          widget.catalogAddToCart('submitForm', $(form));

            return false;
        }
    });
});
