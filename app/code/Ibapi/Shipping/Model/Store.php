<?php 
namespace Ibapi\Shipping\Model;

class Store
{
    
    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
        ) {
        /*
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['store'] = [
                'component' =>   'Ibapi_Shipping/js/storepick',
                'template'=>'Ibapi_Shipping/storepick',
                'config' => [
                    'customScope' => 'shippingAddress',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/checkbox',
                    'options' => [],
                    'id' => 'store-pick'
                ],
                'dataScope' => 'shippingAddress.storepick',
                'label' => 'Store Pick',
                'provider' => 'checkoutProvider',
                'visible' => true,
                'validation' => [],
                'sortOrder' => 200,
                'id' => 'store-pick'
            ];
            */
            return $jsLayout;
    }
}