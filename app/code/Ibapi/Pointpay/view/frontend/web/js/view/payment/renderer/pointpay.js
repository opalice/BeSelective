/* @api */
define([
	'ko',
    'Magento_Checkout/js/view/payment/default',
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'Magento_Catalog/js/price-utils',

    'mage/translate',

 
    'mage/validation'
], function (ko,Component, $,quote,customer,pU,$t) {
    'use strict';

    return Component.extend({
    	disableval:ko.observable(false),
        defaults: {
            template: 'Ibapi_Pointpay/payment/pointpay'
        },

        /** @inheritdoc */
        initObservable: function () {
            this._super()

            
            return this;
        },
        getPoint:function(){
        	var dt=customer.customerData;
        	var rt=0;
        	if(dt){
        		dt=customer.customerData.custom_attributes;
        	}else{

        	}
        	if(dt){
        		
        		rt=customer.customerData.custom_attributes.pointpay.value;
        	}else {
        	}
        	
        	
        	if(rt<quote.totals().grand_total){
       		this.disableval(true);
        	return $t('You have %1 credit. You can\'t buy using this.').replace('%1',pU.formatPrice(rt));

        	}else{
        		this.disableval(false);
            	return $t('You have %1 credit. You can buy using this.').replace('%1',pU.formatPrice(rt));

        	}
        	
        	
        	
        
        },
        getTitle:function(){
        	
        return	$t('Account credits.')
        },
        

        /**
         * @return {Object}
         */
        getData: function () {
            return {
                method: this.item.method,
          ///      'po_number': this.purchaseOrderNumber(),
                'additional_data': null
            };
        },

        /**
         * @return {jQuery}
         */
        /*
        validate: function () {
            var form = 'form[data-role=purchaseorder-form]';

            return $(form).validation() && $(form).validation('isValid');
        }*/
    });
});
