<?php
namespace  Ibapi\Multiv\Model;


use \Ibapi\Multiv\Helper\Data;
use \Magento\Quote\Model\QuoteRepository;
use \Ibapi\Multiv\Model\Type\AccessoryType;
use \Ibapi\Multiv\Model\Type\ClothType;
use \Magento\Framework\Event\ObserverInterface;
use \Ibapi\Multiv\Model\RtableFactory;
use \Magento\Framework\Exception\LocalizedException;
use \Magento\Sales\Api\Data\OrderInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Ibapi\Multiv\Model\Type\SubType;


class CartRemoveObserver implements ObserverInterface
{
    protected $helper;
    private  $rfact;
    private $pri;
    
    public function __construct(\Ibapi\Multiv\Helper\Data $helper,RtableFactory $rfact,ProductRepositoryInterface $pri){
        $this->rfact=$rfact;
        $this->helper=$helper;
        $this->pri=$pri;
    }
    /*
     *     \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Request\Http $request        
    ) {
        $this->quote = $checkoutSession->getQuote();
        $this->request = $request;
     */
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**@var $item \Magento\Quote\Model\Quote\Item\AbstractItem */
        $item=$observer->getEvent()->getData('quote_item');
//        $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
        $product=$item->getProduct();
        
        
        $type=$product->getTypeId();
        
        $this->helper->log("cartremove: $type\n");
        
        
        if(!in_array($product->getTypeId(),['bundle'])){
        return  $this;
        }
        $id=$item->getQuote()->getCustomerId();
        $f=false;
        $items=$item->getChildren();
        $parent=$item->getProduct();
        $psku=$parent->getSku();
        $parid=$item->getProduct()->getId();
        
        
        $qid=$item->getQuote()->getId();
        $deld=true;
        foreach($items as $item){
            $product=$item->getProduct();
            $type=$product->getTypeId();
            $this->helper->log("cartremove:cartremove 2 $type\n");
            
            
            
        if(!in_array($product->getTypeId(),[AccessoryType::TYPE_CODE,ClothType::TYPE_CODE])){
        
            continue;
        
        }
        $f=true;
        break;
        
        }
        if(!$f){
            return $this;
        }
        
        
        $pid=$item->getProduct()->getId();
        $product=$this->pri->getById($pid);
        
        $v=$item->getOptionByCode('rental_option');
        $val=$v->getValue();
        if(!is_string($val)){
            $val=print_r($val,1);
        }
        list($y,$m,$d,$dd)=explode('-',$val);
        $dt="$y-$m-$d";
//        mktime(0,0,0,$m,$d,$y);
        list($sd,$ed)=$this->helper->getDatePair($dt, $dd);
        $ws=$product->getData('wash');
         $this->helper->log("cartremove: will remove cart for customer $id pid $pid  qid $qid value: $val sku $psku wash $ws");
        if($id){
            $this->helper->log("removing");
            $rt=$this->rfact->create();
            $rt->unreserveByCid($pid,$sd,$ed,$qid,$ws);
            $this->helper->log("cartremove:removed");
        }
        if($parid){
            $this->helper->log("cartremove:will delee product bundle sku $psku:".$parid);
            try{
                $pp = $this->pri->getById($parid);
                /**@var $pp \Magento\Catalog\Model\Product */
                $pp->setIsDeleteable(true);
                $this->helper->setSecure(true);
                $this->pri->delete($pp);
                $this->helper->setSecure(false);
                
//                $pp->isDeleteable()
////                $this->pri->delete($pp);
            }catch(\Exception $e){
                $this->helper->log("cartremove:no product ".$parid." msg ".$e->getMessage());
                
            }
            $this->helper->log("cartremove:deleted product bundle ".$parid);
        }else{
            $this->helper->log("cartremove:didn't delete parent");
        }
        $this->helper->log("cartremove:unreserved");
        return $this;
    }
}