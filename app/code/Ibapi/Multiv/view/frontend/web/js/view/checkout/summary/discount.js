/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/totals',
        'ko'
    ],
    function (Component, quote, priceUtils, totals,ko) {
        "use strict";
        console.log('totals',totals)
        console.log('qtotals',quote.getTotals())

        return Component.extend({
            defaults: {
            	getEnable:ko.computed(function(){
            		
            		return true;
            	}),
                isFullTaxSummaryDisplayed: window.checkoutConfig.isFullTaxSummaryDisplayed || false,
                template: 'Ibapi_Multiv/checkout/summary/discount'
            },
        	getEnable:ko.computed(function(){
        		
        		return true;
        	}),

            totals: quote.getTotals(),
            isTaxDisplayedInGrandTotal: window.checkoutConfig.includeTaxInGrandTotal || false,
            isDisplayed: function() {
                return this.isFullMode();
            },
            
            getValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = totals.getSegment('vipdiscount').value;
                }
                return this.getFormattedPrice(price);
            },
            getPureValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = totals.getSegment('vipdiscount').value;
                }
                return price;
            },
        
            getBaseValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = this.totals().base_vipdiscount;
                }
                return priceUtils.formatPrice(price, quote.getBasePriceFormat());
            }
        
        
        });
    }
);