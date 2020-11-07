<?php 
namespace Ibapi\Multiv\Model;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Ibapi\Multiv\Model\RtableFactory;
use Ibapi\Multiv\Helper\Data;
use Magento\Eav\Model\Attribute\GroupRepository;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;
use Magento\Customer\Api\CustomerRepositoryInterface;




class AdminOrderSave implements   ObserverInterface{
    
    protected $rfact;
    protected $helper;
    protected $custRepo;
    public function __construct(
        RtableFactory $rfact,
        Data $helper,
        CustomerRepositoryInterface $custRepo
        ) {
            $this->rfact=$rfact;
            $this->helper=$helper;
            $this->custRepo=$custRepo;
    }
    
    
public function execute(\Magento\Framework\Event\Observer $observer)
{
    
    file_put_contents('rpp.txt', 'startorder');
    $onh=false;
    try{
        
        
        /* @var $order \Magento\Sales\Model\Order */
        
        $order=$observer->getEvent()->getOrder();
        $st=\Magento\Sales\Model\Order::STATE_PROCESSING;
        
        $this->helper->log("\nplacedafterreceived order  state ".$order->getState()." status ".$order->getStatus()." checking $st ID".$order->getIncrementId()."\n");
        if($st!=$order->getState()||!$order->getIncrementId()){
            
            ///                $rt->unreserveByOid($order->getId());
            //         	$onh=true;
$this->helper->log("\no state status \n");
            return;
            
        }
        
        $rt=$this->rfact->create();
        $this->helper->log("rt created\n");
        $items=[];
        foreach($order->getItems() as $item){
            $this->helper->log("rt created ".$item->getItemId()." id ".$item->getId()."\n");
            
            
            if(in_array($item->getProductType(),[AccessoryType::TYPE_CODE,ClothType::TYPE_CODE])){
                $items[$item->getQuoteItemId()]=$item->getItemId();
                $this->helper->log("itemid ".$item->getQuoteItemId()." orderitemid ".$item->getItemId()."\n");
            }
            
            
        }
        
        $ss=print_r($items,1);
        $this->helper->log("\nconfirming ".$order->getIncrementId()." items $ss \n");
        $tems=$rt->confirm($order->getIncrementId(),$items);
        $ss=print_r($tems,1);
        $cid=$order->getCustomerId();
        
        $this->helper->log("\nconfirming ".$order->getIncrementId()." items $ss cid $cid\n");
        
        if(!count($tems)){
            $this->helper->log("\nnot confi\n");
            
            $onh=true;
            $this->helper->log("\ncannot process order ".$order->getIncrementId());
        }else{
            $subid=$this->helper->getValue("rentals/procat/subid");
            foreach($order->getItems() as $item){
                if($item->getProductId()==$subid){
                    if($cid){
                        
                        $customer=$this->custRepo->getById($cid);
                        $customer->setGroupId(5);
                        $this->custRepo->save($customer);
                        $this->helper->log("\ncustomer saved ");
                        break;
                    }
                    
                }
                
                
            }
            
            ///    $order->getCustomer()->getGroupId();
            
        }
        
        /* @var $item \Magento\Sales\Model\Order\Item */
        
        
        if($onh){
            $this->helper->log("saving order to hold");
            try{
                $order->hold();
                ///            		$order->getResource()->save($object);
                //            		$order->getResource()->save($order);
            }catch(\Exception $e){
                $this->helper->log("cannot hold order ".$order->getId()." error ".$e->getMessage());
            }
            
            
        }
    }catch(\Exception $e){
        $this->helper->log($e->getMessage());
        try{
            $order->hold();
            ///            		$order->getResource()->save($object);
            //            		$order->getResource()->save($order);
        }catch(\Exception $et){
            $this->helper->log($et->getMessage());
            
            //   $this->helper->log("cannot hold order ".$order->getId()." error ".$e->getMessage());
        }
    }
    
    
    return $this;
    

        
        
        
}

}