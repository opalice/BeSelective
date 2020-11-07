<?php 
namespace  Ibapi\Multiv\Model\CustomerData;
use Ibapi\Multiv\Model\Type\ClothType;

use Ibapi\Multiv\Model\Type\AccessoryType;

class AroundItem {
    
    protected $_storeMgr;
    protected $_imghelper;    
    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeMgr,\Magento\Catalog\Helper\Image $img){
        $this->_storeMgr=$storeMgr;
        $this->_imghelper=$img;
    }
    
    public function aroundGetItemData($subject, $proceed, $item)
    
    {
        
        $product=$item->getProduct();
        
        $result = $proceed($item);
        $img='';
        $d=0;
        $opts=['sd'=>'','ed'=>''];
        if(strpos($product->getSku(), 'res-')===0){
            $sku=$product->getSku();
           $vals=explode('-',$product->getSku());
           $dd=$vals[5];
            
            foreach( $item->getChildren() as $item){
                if(in_array($item->getProduct()->getTypeId(),[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])){
                    /**@var $product \Magento\Catalog\Model\Product */
                    $img=$item->getProduct()->getImage();
                    $pid=$item->getProduct()->getId();
                        
                    
                    $imgobj= $this->_imghelper->init($item->getProduct(),'product_page_image_small', []);
//                    $img=$this->_storeMgr->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) .'catalog/product/'. $imgobj->getu;
                    $d= $dd==8?(float)$item->getProduct()->getData('vip_discount8'):  (float)$item->getProduct()->getData('vip_discount');
                    /*
                    $option = $item->getOptionByCode('rental_option');
                    
                    
                    if(!$option) return $cartItem;
                    
                    
                    $info = $option->getValue();
                    list($y,$m,$d,$dd)=explode('-', $info);
                    $opts['sd']="$y-$m-$d";
                    $opts['ed']=date('Y-m-d',strtotime($opts['sd'].' + '.$dd.' days'));
                    */
                    
                    break;
                    
                }
            }
            
            if($img){
                $result=  \array_merge($result,['product_image'=>['src'=>$imgobj->getUrl(),'width'=>$imgobj->getWidth(),'height'=>$imgobj->getHeight(),'alt'=>__('Thumbnail')],'vip_discount'=>$d]);
            }else{
                $result=  \array_merge($result,['vip_discount'=>$d]);
                
            }
            
            
        }else{
            
            $result=  \array_merge($result,['vip_discount'=>$product->getData('vip_discount'),'ed'=>'']);
            
        }
        
        
        return $result;
        
    }
    
    
}