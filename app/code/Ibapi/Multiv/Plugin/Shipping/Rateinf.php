<?php
namespace  Ibapi\Multiv\Plugin\Shipping;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Checkout\Model\Cart;

class Rateinf {
    private $helper;
    private $cart;
    
    public function __construct(\Ibapi\Multiv\Helper\Data $helper,Cart $cart){
        $this->helper=$helper;
        $this->cart=$cart;
    }
    public function beforeCollectRates(\Magento\Quote\Model\Quote\Address\RateCollectorInterface $r, RateRequest $req){
        
        $defs=$this->helper->getValue('rentals/procat/subid');
        foreach($this->cart->getItems() as $item){
            $this->helper->log("collecing rate : ".$item->getProduct()->getId()." defs $defs ");
            if($item->getProduct()->getId()==$defs){
                $req->setData('vip',1);
                $this->helper->log("\nmultiv: vip1 ");
                
                return [$req];
            }
        }
        
        if($this->helper->isVip()){

                $req->setData('vip',1);
        }else{
            $req->getData('vip',0);
        }
        
        return [$req];
    }
    
    
}