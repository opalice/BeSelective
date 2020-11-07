<?php
namespace Ibapi\Multiv\Block;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Productform extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    private  $product;
    private $helper;
    protected $_categoryHelper;
    
    
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
        ProductRepositoryInterface $productRepo,
        \Ibapi\Multiv\Helper\Data $helper,
        \Magento\Catalog\Helper\Category $categoryHelper,
        $data){
        parent::__construct($context,$data);
        $this->_categoryHelper=$categoryHelper;
        
        $this->helper=$helper;
        $uid=$this->helper->getCustomerId();
        $pid=$this->getRequest()->getParam('pid',0);
        if($pid&&$uid){
            $this->product=$productRepo->getById($pid,true,null,true);
            if($this->product->getData('uid')!==$uid){
///                $this->product=false;
            }
        }
        
    }
    
    function getFormUrl(){
        return $this->_urlBuilder->getRouteUrl('multiv/index/form');
        
    }
    public function genTree(){
        $cids=[3];
        if($this->product)
        $cids=$this->product->getCategoryIds();
        return        $this->helper->getCatTree($cids);
        
    }
    
    
    function getFileUrl(){
        
    }
    function getProductData(){
        $stores=$this->helper->getStoreManager()->getStores();
        $sts=[];
        foreach($stores as $store){
            $sts[]= [
                
                'id'=>$store->getId(),'name'=>$store->getName(),'vars'=>['description'=>'','short_description'=>'','name'=>'']];
        }
        $data=['rent8'=>'','rent4'=>'',
            'price'=>'','size'=>'','color'=>'','status'=>'','brand'=>'','length'=>
            '','weight'=>'','deposit'=>''
        ];
        
       
        
        $keys=array_keys($data);
        $newsts=[];
        
        
        
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Size::class);
                $sopts=$obj->getAllOptions();
        
                $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Brand::class);
                $bopts=$obj->getAllOptions();
                
                $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Color::class);
                $copts=$obj->getAllOptions();
                
                $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Length::class);
                $lopts=$obj->getAllOptions();
                
        
        
        
        if($this->product){
            
            foreach($keys as $key){
                $data[$key]=$this->product->getData($key);
            }
            
            $cids=$this->product->getCategoryIds();
            
            $data['pid']=$this->product->getId();
            $i=0;
            
            foreach($sts as $storeid=>$store){
                $prd=$this->product->setStoreId($store['id']);
                $stid=$store['id'];
                $vars=$store['vars'];
                foreach($vars as $var=>$val){
                    $val=$prd->getResource()->getAttributeRawValue($this->product->getId(),$var,$store['id']);
                    
                    $newsts[$i]['vars'][$var]=$val;
                }
                $newsts[$i]['id']=$store['id'];
                $newsts[$i]['name']=$store['name'];
                
                $i++;
            
            }
        
        }
        
        if(count($newsts)){
            $sts=$newsts;
        }else{
        }
        
        
        
        return json_encode([ 
            'msg'=>__('Product Saved'),
            'errmsg'=>__('Product Save error'),
            'title'=>__('Success'),
            'errtitle'=>__('Fail'),
            'cats'=>$this->genTree()
            ,
            
            
            'stores'=>$sts,'sizeopts'=>$sopts,'coloropts'=>$copts,'brandopts'=>$bopts,
            'lenopts'=>$lopts,
            'data'=>$data,'url'=>$this->_urlBuilder->getRouteUrl('multiv/index/form')]);
    }
    function getProductImages(){
        $images=[];
        if($this->product){
            
            $images=$this->product->getMediaGalleryEntries();
        }
        foreach($images as $k=>$im){
            $url=$this->helper->getImgUrl($im->getFile());
            $images[$k]=['url'=>$url,'path'=>$im->getFile(),'label'=>$im->getLabel(),'type'=>$im->getMediaType(),'id'=>$im->getId(),'name'=>$im->getLabel()];
        }
        
        return json_encode(['images'=>$images,'url'=>$this->_urlBuilder->getRouteUrl('multiv/index/deli')]);
    }
    
    
    function getConfig(){
        return json_encode([
            'url'=>$this->_urlBuilder->getRouteUrl('multiv/index/image')
            
            
        ]
            );
        
        
    }
}
