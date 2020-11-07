define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'multiv_pointpay',
                component: 'Ibapi_Pointpay/js/view/payment/renderer/pointpay'
            }
        );
        return Component.extend({});
    }
);