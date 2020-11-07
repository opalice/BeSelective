define(
    [
        'ko',
        'Magento_Checkout/js/model/quote'

    ], function (ko,quote) {
        'use strict';

        var mixin = {

            initialize: function () {
                this.visible = ko.observable(false);
                var sh=false;
                _.each(quote.getItems(),function(item){
             	   if(item.sku.indexOf('res-')==0){
             		   sh=true;
             	   }
             	   
                },this);
                
                this.visible(!sh);

                
                this._super();

                return this;
            }
        };

        return function (target) {
            return target.extend(mixin);
        };
    }
);