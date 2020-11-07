
define(['jquery',      'mage/storage',
  'Magento_Ui/js/model/messageList',    'Magento_Customer/js/customer-data'
],function ($,storage,globalMessageList,customerData) {
 'use strict';

 var mixin = {

	        defaults: {
	            template: 'Ibapi_Regq/authentication-popup'
	        },
	        
	       

	        
	       callback:null,
	       
	       regA: function (loginData, redirectUrl, isGlobal, messageContainer) {
	            messageContainer = messageContainer || globalMessageList;
	            var cb=this.callback;
	            var self=this;
	            console.log('registering');
	            return storage.post(
	                'regq/index/reg',
	                JSON.stringify(loginData),
	                isGlobal
	            ).done(function (response) {
	                console.log("regitered",response);
	            	self.isLoading(false);
	                
	                if (response.errors) {
	                	console.log('response',response.errors);
	                	messageContainer.addErrorMessage(response.errors);

	                } else {
	                	console.log('willcall',cb);
	                /////		                $(this.modalWindow).modal('closeModal');

	                	customerData.invalidate(['customer']);
	                	customerData.reload('customer').done(function(){
	                			console.log('callback');
	                			
	                		
	                	});

	                }
	            }).fail(function () {
///	                this.isLoading(false);
	            	self.isLoading(false);
	            	
	                messageContainer.addErrorMessage({
	                    'message': 'Could not register. Please try again later'
	                });
	            });
	        },

	       
	       loginA: function (loginData, redirectUrl, isGlobal, messageContainer) {
	            messageContainer = messageContainer || globalMessageList;
	            var cb=this.callback;
	            var self=this;
	            return storage.post(
	                'customer/ajax/login',
	                JSON.stringify(loginData),
	                isGlobal
	            ).done(function (response) {
	                console.log("logged",response,'callback',cb);

	            	self.isLoading(false);

	                if (response.errors) {
	                	console.log('error',response.errors);
	                
	                	messageContainer.addErrorMessage(response);
	                	
	                } else {
/////		                $(this.modalWindow).modal('closeModal');

	                	customerData.invalidate(['customer']);
	                	customerData.reload('customer').done(function(){
	                			console.log('callback');
	                		
	                	});

	                }
	            }).fail(function () {
	            	console.log('failedlogin');
	            	
	                self.isLoading(false);

	                messageContainer.addErrorMessage({
	                    'message': 'Could not authenticate. Please try again later'
	                });
	            });
	        },
	        
	        register:function(formUiElement,event){

	            var loginData = {},
                formElement = $(event.currentTarget),
                formDataArray = formElement.serializeArray();

            event.stopPropagation();
            formDataArray.forEach(function (entry) {
                loginData[entry.name] = entry.value;
            });

            if (formElement.validation() &&
                formElement.validation('isValid')
            ) {
            	if(loginData['password']!=loginData['cpassword']){
            		globalMessageList.addErrorMessage({
	                    'message': 'passwords not match'
	                });

	                return false;
            	}
                this.isLoading(true);
                console.log('registering',loginData);
                this.regA(loginData);
            }

            return false;

	        	
	        },
	        
	        /**
	         * Provide login action
	         *
	         * @return {Boolean}
	         */
	        login: function (formUiElement, event) {
	            var loginData = {},
	                formElement = $(event.currentTarget),
	                formDataArray = formElement.serializeArray();

	            event.stopPropagation();
	            formDataArray.forEach(function (entry) {
	                loginData[entry.name] = entry.value;
	            });

	            if (formElement.validation() &&
	                formElement.validation('isValid')
	            ) {
	                this.isLoading(true);
	                this.loginA(loginData);
	            }

	            return false;
	        }
	        
	        
 
	        
	        ,
 
 
	 

	        showModal: function (x) {

	        	this.callback=authenticationPopup.cb;
	        	console.log('authcall',authenticationPopup);
	        	
	            if (this.modalWindow) {
	                $(this.modalWindow).modal('openModal');
	            } else {
	                alert({
	                    content: $t('Guest checkout is disabled.')
	                });
	            }
	        }
		 
 };

 return function (target) { 
     return target.extend(mixin);
 };
});

