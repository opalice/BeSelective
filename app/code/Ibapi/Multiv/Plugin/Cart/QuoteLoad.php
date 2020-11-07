<?php 
namespace  Ibapi\Multiv\Plugin\Cart;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\QuoteRepository\LoadHandler;

class QuoteLoad {
    var $rt;
    var $q;
    public function __construct(\Ibapi\Multiv\Model\ReserveFactory $rtfact,\Ibapi\Multiv\Model\Extension\QuoteOption $q ){
        
        $this->rt=$rtfact->create();
        $this->q=$q;
    }
    
    public function afterLoad(LoadHandler $load,CartInterface $quote){
        if(!$quote->getIsActive()){
            return $quote;
        }
        
        $cartExtension = $quote->getExtensionAttributes();
        
        /**@var $rentaldata \Ibapi\Multiv\Api\Data\OptionInterface */
           $qid=$quote->getId();
           
       $rt='';
       
       if($quote->getExtensionAttributes()==null){
           return $quote;
       }
       
            $quote->getExtensionAttributes()->setRentalData($this->q);
       
           if($qid){

             $rt=   $this->rt->getQid($qid);
             
           }
   
           $quote->getExtensionAttributes()->getRentalData()->setRentalDates($rt);
           
      //  $rentaldata->setRentalDates($rt);
        
    //    $cartExtension->setRentalData($rentaldata);
        
  //      $quote->setExtensionAttributes($cartExtension);
        
        return $quote;        
        
    }
    
}