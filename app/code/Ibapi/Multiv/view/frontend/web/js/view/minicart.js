/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'jquery',
    'ko',
    'Ibapi_Multiv/js/date_fns',
    'underscore',
    'mage/url',
    
    /*
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/totals',
*/

    'sidebar',
    'mage/translate'
], function (Component, customerData, $, ko,dateFns, _,url) {
    'use strict';
	var thissku='';

    var sidebarInitialized = false,intv=0,
        addToCartCalls = 0,disabled=[],
        miniCart;


    miniCart = $('[data-block=\'minicart\']');

    /**
     * @return {Boolean}
     */
    function initSidebar() {

    	console.log('initedsidebar');
        if (miniCart.data('mageSidebar')) {
            miniCart.sidebar('update');
        }

        if (!$('[data-role=product-item]').length) {
            return false;
        }
        miniCart.trigger('contentUpdated');

        if (sidebarInitialized) {
            return false;
        }
        sidebarInitialized = true;
        miniCart.sidebar({
            'targetElement': 'div.block.block-minicart',
            'url': {
                'checkout': window.checkout.checkoutUrl,
                'update': window.checkout.updateItemQtyUrl,
                'remove': window.checkout.removeItemUrl,
                'loginUrl': window.checkout.customerLoginUrl,
                'isRedirectRequired': window.checkout.isRedirectRequired
            },
            'button': {
                'checkout': '#top-cart-btn-checkout',
                'remove': '#mini-cart a.action.delete',
                'close': '#btn-minicart-close'
            },
            'showcart': {
                'parent': 'span.counter',
                'qty': 'span.counter-number',
                'label': 'span.counter-label'
            },
            'minicart': {
                'list': '#mini-cart',
                'content': '#minicart-content-wrapper',
                'qty': 'div.items-total',
                'subtotal': 'div.subtotal span.price',
                'maxItemsVisible': window.checkout.minicartMaxItemsVisible
            },
            'item': {
                'qty': ':input.cart-item-qty',
                'button': ':button.update-cart-item'
            },
            'confirmMessage': $.mage.__('Are you sure you would like to remove this item from the shopping cart?')
        });
    }

    miniCart.on('dropdowndialogopen', function () {
        initSidebar();
    });

    return Component.extend({
        shoppingCartUrl: window.checkout.shoppingCartUrl,
        maxItemsToDisplay: window.checkout.maxItemsToDisplay,
        cart: {},
        timeleft:ko.observable(''),
        startDate:ko.observable(''),
        endDate:ko.observable(''),
        duration:ko.observable(''),
        hasPurchased:ko.observable(false),

        /**
         * @override
         */
        initialize: function () {
            var self = this,
                cartData = customerData.get('cart');

            this.update(cartData());
            cartData.subscribe(function (updatedCart) {
                addToCartCalls--;
                this.isLoading(addToCartCalls > 0);
                sidebarInitialized = false;
                this.update(updatedCart);
                initSidebar();
            }, this);
            $('[data-block="minicart"]').on('contentLoading', function () {
                addToCartCalls++;
                self.isLoading(true);
            });


            if (cartData()['website_id'] !== window.checkout.websiteId) {
                customerData.reload(['cart'], false);
            }

            return this._super();
        },
        isLoading: ko.observable(false),
        initSidebar: initSidebar,

        hasSub:function(){

        	return false;
        },
        premSub:function(){
			var self=this;
		     self.isLoading(true);

			$.ajax({url: url.build('multiv/index/cart')}).done(function(x){

        customerData.reload(['cart']).done(function(){
          location.reload();

        });


			}).fail(function(){
				console.log('failed to load');
			}).always(function(){

					     self.isLoading(false);

			});

        },
        getTimeLeft:function(){
        	return this.timeleft();

        },
        getDiscount:function(){

        	return 100;
        },

        /**
         * Close mini shopping cart.
         */
        closeMinicart: function () {
            $('[data-block="minicart"]').find('[data-role="dropdownDialog"]').dropdownDialog('close');
        },

        /**
         * @return {Boolean}
         */
        closeSidebar: function () {
            var minicart = $('[data-block="minicart"]');

            minicart.on('click', '[data-action="close"]', function (event) {
                event.stopPropagation();
                minicart.find('[data-role="dropdownDialog"]').dropdownDialog('close');
            });

            return true;
        },

        /**
         * @param {String} productType
         * @return {*|String}
         */
        getItemRenderer: function (productType) {
            return this.itemRenderer[productType] || 'defaultRenderer';
        },

        /**
         * Update mini shopping cart content.
         *
         * @param {Object} updatedCart
         * @returns void
         */
        update: function (updatedCart) {
            disabled=[]
            var self=this;

            var discs=0;

            self.hasPurchased(false)
            
            this.cart['prodiscount']=ko.observable(0);
        	_.each(updatedCart, function (value, key) {
        		
        		if (!this.cart.hasOwnProperty(key)) {
                    this.cart[key] = ko.observable();
                }
                if(key=='items'){
                	var self=this;
                	_.each(value,function(v,k){
                		console.log('sku',v['product_sku']);
                		if(v['product_sku']=='subscription'){

                		$('#subbox').hide();
						console.log('hid');
                		}
                		var vd=parseFloat(v['vip_discount'])
                		discs+=isNaN(vd)?0:vd;
                		console.log('got discount',vd,discs);

                		if(typeof v['product_sku']!=='undefined'&&v['product_sku'].startsWith('res-')){
//                    		disabled.push(v['item_id']);
							thissku=v;
                    		var dt=v['product_sku'].split('-')
                    		var dtx=[parseInt(dt[2]),parseInt(dt[3]),parseInt(dt[4])]
                    		var it=parseInt(dt[5])
                    		
                    		
                    		self.checkDisabled(v['item_id']);
                    		self.startDate(dt[2]+'-'+dt[3]+'-'+dt[4]);
                    		
                    		console.log(new Date(dtx[0],dtx[1]-1,dtx[2],0,0,0),dtx )
               		        self.endDate(dateFns.format(dateFns.addDays(new Date(dtx[0],dtx[1]-1,dtx[2]),it-1),'YYYY-MM-DD'));
               		        self.hasPurchased(true)
               		        self.duration(it);


                    	}

                	});

                }

                this.cart[key](value);
            }, this);
        	this.cart['prodiscount'](discs);


        },

        /**
         * Get cart param by name.
         * @param {String} name
         * @returns {*}
         */
        getCartParam: function (name) {
            if (!_.isUndefined(name)) {
                if (!this.cart.hasOwnProperty(name)) {
                    this.cart[name] = ko.observable();
                }
            }

            return this.cart[name]();
        },

        /**
         * Returns array of cart items, limited by 'maxItemsToDisplay' setting
         * @returns []
         */
        getCartItems: function () {
            var items = this.getCartParam('items') || [];

            items = items.slice(parseInt(-this.maxItemsToDisplay, 10));


            return items;
        },

        checkDisabled:function(v){
        if(intv>0){
        	return;
        }
        	$('#cart-item-'+v+'-qty').hide();//attr('disabled',true)
        	$('#update-cart-item'+v).hide();//attr('disabled',true)

        {

        	var self=this;

        	intv=setInterval(function(){



        		customerData.reload('carttimer').done(function(x){
        			if(!x.carttimer.carttimer){
		//        		self.timeleft('')
        	///			clearInterval(intv)
        		//		intv=0;
        				return;	
        			}
        			var vk=x.carttimer.carttimer;

        			if(vk==0){
        				self.timeleft('')
        				clearInterval(intv)
        				intv=0;
        				return;
        			}
        			if(vk==-1){
        				location.reload()
        				return;
        			}
        			console.log('vks',vk);
        			
        			var mk=vk.split(':')
        			if(parseInt(mk[1])<5&&!$('#multiv_extra').is(':visible')&&window.location.href.indexOf('checkout')==-1){
        				$('[data-block="minicart"]').find('[data-role="dropdownDialog"]').dropdownDialog("open");
        			}
        			mk.shift()
       				self.timeleft(mk.join(':'))
       				console.log('update timeleft')
       			//	startDate:ko.observable(x.carttimer[1]);
       		      //  endDate:ko.observable(x.carttimer[2]);
       		       // duration:ko.observable(x.carttimer[3]);
       				


        		});

        	},5000);

        }
        //u	pdate-cart-item-61
        //cart-item-61-qty
        },

        /**
         * Returns count of cart line items
         * @returns {Number}
         */
        getCartLineItemsCount: function () {
            var items = this.getCartParam('items') || [];

            return parseInt(items.length, 10);
        }
    });
});
