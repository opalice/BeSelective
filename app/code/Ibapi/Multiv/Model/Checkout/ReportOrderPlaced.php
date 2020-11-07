<?php
namespace Ibapi\Multiv\Model\Checkout;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Ibapi\Multiv\Model\RtableFactory;
use Ibapi\Multiv\Helper\Data;
use Magento\Eav\Model\Attribute\GroupRepository;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;




/**
 * Class ReportOrderPlaced
 */
class ReportOrderPlaced implements   \Magento\Framework\Event\ObserverInterface
{
    
    protected $rfact;
    protected $helper;
    protected $custRepo;
    protected $prodRepo;
    protected  $cfact;
    public function __construct(
        RtableFactory $rfact,
        Data $helper,
        CustomerRepositoryInterface $custRepo,
        ProductRepositoryInterface $prodRepo,
        CustomerFactory $cfact

        ) {
            $this->cfact=$cfact;
            $this->rfact=$rfact;
            $this->helper=$helper;
            $this->custRepo=$custRepo;
            $this->prodRepo=$prodRepo;
          
    }
    
    
    
    /**
     * resets the order status back to pending payment in case of direct debits nl with order id as merchant ref
     * @event sales_order_payment_place_end
     * @param \Magento\Framework\Event\Observer $observer
     */

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        
        
        
    
    
    $onh=false;
    try{
        
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        
        if(!is_object($order)){
            return $this;
        }
        
        $oid=$order->getId();
        $inc=$order->getIncrementId();
        
        
        
        /* @var $order \Magento\Sales\Model\Order */
        
        $st=\Magento\Sales\Model\Order::STATE_PROCESSING;
        
        $this->helper->log("\nplacedafterreceived order  state ".$order->getState()." status ".$order->getStatus()." checking $st ID".$order->getId()."\n");
        if($st!=$order->getStatus()){
            
            ///                $rt->unreserveByOid($order->getId());
            //         	$onh=true;
$this->helper->log("\no state status \n");
            return;
            
            }
        $popay=0;
        $pdis=0;
        $rt=$this->rfact->create();
        $this->helper->log("rt created\n");
        $items=[];
      ///  $quote=$order->getQuoteId();
        
        $cid=$quote->getCustomerId();
        $customer='';
        if($cid){
//        $customer=$order->getCustomer();
        $customer=$this->cfact->create()->load($cid);
        $pdis=(float)$quote->getBaseViscount();
        $ppdis=(float)$quote->getViscount();
  //      $order->setViscount($ppdis);
//        $order->setBaseViscount($pdis);
        $popay=$customer->getData('pointpay');
        $this->helper->log("pointpay: total $popay pdis $pdis");
        if($pdis>$popay){
                throw new \Exception(__("Insufficient balance")); 
        
        }
        
        }else{
            $this->helper->log("pointpay: nocid");
        }
        
      
        foreach($order->getAllItems() as $item){
            $parent='';
            $this->helper->log("rt created ".$item->getItemId()." id ".$item->getId());
            

            if(in_array($item->getProductType(),[AccessoryType::TYPE_CODE,ClothType::TYPE_CODE])){
                
                
                $items[$item->getQuoteItemId()]=$item->getItemId();
                
                
                $this->helper->log("itemid ".$item->getQuoteItemId()." orderitemid ".$item->getItemId()."\n");
                $parent=$item->getParentItem();
                if($parent){
                    
                }
            
            }
            
            
        }
        
        $ss=print_r($items,1);
        $this->helper->log("\nconfirming ".$order->getIncrementId()." items $ss \n");
        $tems=$rt->confirm($order->getIncrementId(),$items);//,$o,$this->helper->getCustomerId());
        $ss=print_r($tems,1);
        $this->helper->log("\nconfirming ".$order->getIncrementId()." items $ss cid $cid\n");
        if(!count($tems)){
            $this->helper->log("\nnot confi\n");
            
            $onh=true;
            $this->helper->log("\ncannot process order ".$order->getIncrementId());
        }else{
            
            
            if($parent){
                $pp=$this->prodRepo->getById($parent->getProductId());
                $this->helper->log("saverr:will delete parent ".$parent->getProductId()." psku  $psku\n");
                
                $psku=$pp->getSku();
                try{
                $this->helper->setSecure(true);
                $this->prodRepo->delete($pp);
                $this->helper->setSecure(false);
                $this->helper->log("saverr: deleteed parent $psku\n");
                
                }catch(\Exception $e){
                    $this->helper->log("saverr:error in deletig ".$parent->getProductId());
                    $this->helper->log("saverr: msg ".$e->getMessage());
                }
                
            }else{
                $this->helper->log("noparent parent \n");
                
            }
            $subid=$this->helper->getValue("rentals/procat/subid");
            
            
            foreach($order->getItems() as $item){
                if($item->getProductId()==$subid){
                    if($cid){
                        
                        $customer->setGroupId(5);
                        $customer->save();
//                        $this->custRepo->save($customer);
                        $this->helper->log("\ncustomer saved ");
                        break;
                    }
                    
                }
                
                
            }
            
            ///    $order->getCustomer()->getGroupId();
            
        }
        
        /* @var $item \Magento\Sales\Model\Order\Item */
        
        
        
        if($onh){
            $this->helper->log("holderr:saving order to hold");
            try{
                $order->hold();
                ///            		$order->getResource()->save($object);
                //            		$order->getResource()->save($order);
            }catch(\Exception $e){
                $this->helper->log("holderr:0 cannot hold order ".$order->getId()." error ".$e->getMessage());
            }
            
            
        }
        if($cid&&$customer&&!$onh&&$pdis<0){
            try{
                $amt=$popay+$pdis;
                $this->helper->log("pointpaysave:  amt $amt  cid $cid ");
                $customer->setData('pointpay',$amt);
                $customer->save();
                $this->helper->log("pointpaysave:saved pointpay $cid amt $amt popay $popay pdis $pdis ");
            }catch(\Exception $e){
                $this->helper->log("pointpayerr: ".$e->getMessage());
                
            }
            
            
      ///      $customer->setData('pointpay',$popay-$pdis);
   ///         $this->custRepo->save($customer);
///            $this->helper->log("pointpay:customer ded $pdis ");
        }else{
            try {
                $cc = $customer ? $customer->getId() : '';
                $this->helper->log("pointpayerr: no $cid  cust $cc onh $onh");
        }catch (\Exception $e){
                $this->helper->log("pointpayerr: ".$e->getMessage());
            }

            }
        
    }catch(\Exception $e){
        $this->helper->log("holderr:1".$e->getMessage());
        try{
            $order->hold();
            ///            		$order->getResource()->save($object);
            //            		$order->getResource()->save($order);
        }catch(\Exception $et){
            $this->helper->log("holderr:2".$et->getMessage());
            
            //   $this->helper->log("cannot hold order ".$order->getId()." error ".$e->getMessage());
        }
    }
    
    
    return $this;
    

    }
        
        
    
    
    
}
