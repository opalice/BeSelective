<?php 
namespace  Ibapi\Pointpay\Model\Checkout;
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

        $pointpay['component']='Ibapi_Pointpay/js/view/payment/pointpay';
        $pointpay['methods']['pointpay']['isBillingAddressRequired']=false;
        $pointpay['methods']['pointpay']['config']=[
            'billing'=>1,
            'customer'=>2
            
        ]
        ;
        
        
        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['renders']['children']['pointpay']=$pointpay;
        
        

         
        
       return $jsLayout;
    }
}