<?php 
namespace  Ibapi\Pointpay\Model;
class Layout
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

        $pointpay['component']='Ibapi_Pointpay/js/payment/pointpay';
        $pointpay['methods']['ibapi_pointpay']['isBillingAddressRequired']=false;
        $pointpay['methods']['ibapi_pointpay']['config']=[
            'billing'=>1,
            'customer'=>2
            
        ]
        ;
        
        
        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['renders']['children']['pointpay']=$pointpay;
        
        

         
        
       return $jsLayout;
    }
}