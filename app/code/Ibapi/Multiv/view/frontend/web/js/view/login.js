/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/form',
    'mage/storage',
    'Magento_Ui/js/model/messageList',

    'Magento_Customer/js/customer-data',
    'Ibapi_Multiv/js/login',
    'mage/translate',
    'mage/url',
    'Magento_Ui/js/modal/alert',
    'mage/validation'
], function ($, ko, Component, storage, globalMessageList ,customerData, authenticationPopup, $t, url, alert) {
    'use strict';
    var self;
    
    function loginAction(loginData){
       var messageContainer = globalMessageList;
       var isGlobal=true;
       
    	storage.post(                'multiv/index/login',
                JSON.stringify(loginData),
                isGlobal
).done(function(response){
	
    customerData.invalidate(['customer']);
    
    if(!response.errors&&response.id){
    	
    	self.closeModal();

    	authenticationPopup.fn(self.isLoading);
    }else{
	
    
    self.isLoading(false);
    }
	
}).fail(function(){
	
    self.isLoading(false);
    messageContainer.addErrorMessage({
        'message': 'Could not Register'
    });

    
});

    	
    }

    function regAction(regData){
        var messageContainer = globalMessageList;
        var isGlobal=true;
        
     	storage.post(                'multiv/index/reg',
                 JSON.stringify(regData),
                 isGlobal
 ).done(function(response){
 	console.log('response',response);
 	
     customerData.invalidate(['customer']);

 	
     self.isLoading(false);
 	
 }).fail(function(){
 	
     self.isLoading(false);

 });

     	
     }

    
    return Component.extend({
        registerUrl: window.authenticationPopup.customerRegisterUrl,
        forgotPasswordUrl: window.authenticationPopup.customerForgotPasswordUrl,
        autocomplete: window.authenticationPopup.autocomplete,
        modalWindow: null,
        isLoading: ko.observable(false),

        defaults: {
            template: 'Ibapi_Multiv/login'
        },

        /**
         * Init
         */
        initialize: function () {
            self = this;

            this._super();
        },

        /** Init popup login window */
        setModalElement: function (element) {
            if (authenticationPopup.modalWindow == null) {
                authenticationPopup.createPopUp(element);
            }
        },

        /** Is login form enabled for current customer */
        isActive: function () {
            var customer = customerData.get('customer');

            return customer() == false; //eslint-disable-line eqeqeq
        },

        /** Show login popup window */
        showModal: function () {
            if (this.modalWindow) {
                $(this.modalWindow).modal('openModal');
            } else {
                alert({
                    content: $t('Guest checkout is disabled.')
                });
            }
        },
        closeModal: function () {
            if (this.modalWindow) {
                $(this.modalWindow).modal('closeModal');
            } else {
            	authenticationPopup.closeModal();
            	console.log('not closeds')
            }
        },
        
        
        reg: function (formUiElement, event) {
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
                
                
                regAction(loginData);
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
                loginAction(loginData);
            }

            return false;
        }
    });
});
