/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'ko',
   
     'jquery',
     'Ibapi_Multiv/js/carttimer2'
], function ( Component, customerData,ko,$,carttimer) {
    'use strict';
    var sections=false;

    return Component.extend({
        cart:'',
        showTimer:function(){
        	return carttimer.showTimer();
        },
        /*
        initObservable:function(){
//        	this._super()
        	console.log('obser')
        	this.showTimer=ko.computed(function(){
        		console.log('tilemeft',carttimer.showTimer())
        			return carttimer.showTimer()
        	},this);
        	
        },
        */

        /**
         * @override
         */
        hasTemplate:function(){
        	
        	return true;
        },

        initialize: function () {
///            this._super();
///            this.showTimer(500)
        	

        	
            console.log('ctttx',carttimer.showTimer())
            return this._super();
        }
    });
});
