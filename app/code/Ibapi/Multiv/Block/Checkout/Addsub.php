<?php
namespace  Ibapi\Multiv\Block\Checkout;
use Magento\Framework\View\Element;
use Magento\Framework\View\Element\Template;


class Addsub extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Ibapi\Multiv\Helper\Data
     */
    protected  $helper;
    protected $cart;

    public function __construct(Template\Context $context,\Ibapi\Multiv\Helper\Data $helper, \Magento\Checkout\Model\Cart $cart, array $data = [])
    {
        parent::__construct($context, $data);
        $this->helper=$helper;
        $this->cart=$cart;
    }

    public function canShow(){
        $pid=$this->helper->getValue('rentals/procat/subid');
            
        if( $this->helper->getCustomerGroup()==5) return false;

            if(in_array($pid,$this->cart->getProductIds())){
              return false;
            }
            
            return true;
    

        

    }

}
