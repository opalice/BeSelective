<?php 
namespace Ibapi\Multiv\Helper;

use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\SubType;
use Ibapi\Multiv\Model\Type\DepType;
use Magento\Customer\Model\CustomerFactory;

class Discount {
    protected  $cfact;

    private $ss;

    public function getTiscount(\Magento\Quote\Model\Quote $quote){

        $tc=$quote->getTiscount();
        if($tc==0){
            ///$gt=$quote->getBaseSubtotal();
            ///$gtt=$quote->getBaseGrandTotal();
          //  $qb= $quote->getBaseGrandTotal()*(0.21/1.21);
//            $sh=$quote->getShippingAddress()?$quote->getShippingAddress()->getShippingAmount():0;
  //          $bt=$quote->getBaseSubtotal();
                $vis=$this->getViscount($quote);
                

                $qb=($quote->getBaseGrandTotal()-$vis)*0.21/1.21;

           /// file_put_contents('customtaxok.txt',"tc $tc  sh $sh bt $bt vip $vis gt $gt gtt $gtt  \n",FILE_APPEND);
            return $qb;
        }
       /// file_put_contents('customtaxok.txt',"tc $tc\n ",FILE_APPEND);

return $tc;
    }

    public  function  __construct(CustomerFactory $cfact){
        $this->cfact=$cfact;
    }
    
    public function getExtax($pr){
        $ss=$pr;

        $this->ss = $ss;
        return $this->ss;

    }
    public function getViscount(\Magento\Quote\Model\Quote $quote){
        
        $amt=$this->getDiscount($quote)*-1;
$tt=0;
        $customer=$this->cfact->create()->load($quote->getCustomerId());
        $pp=(float)$customer->getData('pointpay');
        foreach ($quote->getAllItems() as $item){
            
           /**@var $item \Magento\Quote\Model\Quote\Item */
            if(!in_array($item->getProductType(), [DepType::TYPE_CODE])&&strstr($item->getSku(),'res-')==false){
            $amt+=$item->getCustomPrice();//=$item->getRowTotal();

                file_put_contents('customprice.txt',"amt $amt item".$item->getSku()."\n",FILE_APPEND);
            }
      
            
            
            
        }
        return max(min($pp,$amt),0);
        
    }
    
    
    public function getDiscount(\Magento\Quote\Model\Quote $quote){
        $d=false;
        $cid=$quote->getCustomerId();
        if($quote->getCustomerGroupId()==5){
            $d=true;
        }
        $dis=0;
        $sub=false;
        $dix=0;
        $chitem='';
        $dd='';
        foreach ($quote->getAllItems() as $item){
            
            
            
            $product = $item->getProduct();
            
            
            $dix=(float)$product->getData('vip_discount');
            $dix1=$dix;
            
            
            
            
            /*@var $product \Magento\Catalog\Model\Product */
            
            
            if(strpos($product->getSku(),'res-')===0){
                
                try{
                    
                    
                    $vals=explode('-',$product->getSku());
                    
                    $dd=$vals[5];
                    
                    
                    
                }catch(\Exception $e){
                    
                    
                }
                
                
                
            }else if(in_array($product->getTypeId(),[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])){
                
                $chitem=$product;
            }
            
            
            
            
            
            else if($product->getTypeId()==SubType::TYPE_CODE){
                $d=true;
                
            }else{
                
                $dis+=(float)$product->getData('vip_discount');
                
            }
            
            
            if($dd&&$chitem){
                $dix=$dd==8?$chitem->getData('vip_discount8'):$chitem->getData('vip_discount');
                // $this->log("vipdiscount: $dd ".$dix);
                
                $dis+=$dix;
                $dd=0;
                $chitem=false;
            }
            
            
        }
        
        
        
        
        if($d){
            return $dis;
        }
        return 0;
        
        
        
        
    }
    
    
}