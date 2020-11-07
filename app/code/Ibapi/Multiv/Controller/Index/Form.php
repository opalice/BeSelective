<?php
namespace Ibapi\Multiv\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Ramsey\Uuid\Codec\GuidStringCodec;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;

class Form extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected  $uploader;
    protected  $repo;
    protected  $helper;
    protected  $factory;
    protected  $entryFactory;
    protected $catmgmt;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultPageFactory,
        CategoryLinkManagementInterface $catmgmt,
        \Ibapi\Multiv\Helper\Data $helper,
        ProductRepositoryInterface $repo,
        ProductFactory $factory,
        \Ibapi\Multiv\Model\ImageUploader $uploader
        )
    {
        $this->resultPageFactory = $resultPageFactory;        
        parent::__construct($context);
        $this->uploader=$uploader;
        $this->repo=$repo;
       $this->helper=$helper;
       $this->factory=$factory;
       $this->entryFactory=$this->_objectManager->create(\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterfaceFactory::class);
        $this->catmgmt=$catmgmt;
        
    }
    public function dispatch(RequestInterface $request){
        
        $uid=$this->helper->getCustomerId();

        if(!$uid){
        $this->_actionFlag->set('', Action::FLAG_NO_DISPATCH,1);
        $this->_actionFlag->set('', Action::FLAG_NO_DISPATCH_BLOCK_EVENT,1);
        }
        
       return  parent::dispatch($request);
    }

    protected function addImagesToProductGallery(ProductInterface $product, $entries, $files)
    {
        
        $data = [];
        $first=true;
        foreach ($files as $key => $file) {
            $entry = $entries[$key];
            $imageData = file_get_contents($file);
            $base64EncodedData = base64_encode($imageData);
            $imageContent=$base64EncodedData;
            
            if (!$imageContent) {
                unset($entries[$key]);
                continue;
            }
            
            $fileInfo = pathinfo($file);
 ///           $fileData = $this->remover->resolveFilePrefix($file['file']);
    ///        $filename = $fileData['prefix'] . $fileInfo['basename'];
            /**@var $product \Magento\Catalog\Model\Product */
            $imageType = exif_imagetype($file);
            
            try {
                if ($first) {
                    $first=false;
                     $product->addImageToMediaGallery($file, [ 'small_image', 'thumbnail'], true, false);
                    // $product->setThumbnail($file);
                     //$product->setImage($file);
                     //$product->setSmallImage($file);
                     
                } else {
                    $product->addImageToMediaGallery($file, null, true, false);
                }
                /**@var $ic \Magento\Framework\Api\Data\ImageContentInterface */
                $ic=$this->_objectManager->create(\Magento\Framework\Api\Data\ImageContentInterface::class);
                $ic->setBase64EncodedData($base64EncodedData);
                $ic->setName(basename($file));
                
                $mimeType = image_type_to_mime_type($imageType);
                
                $ic->setType($mimeType);
                
                $entry->setPosition($key);
                $entry->setFile(basename($file));
                $entry->setContent($ic);
                
                $entries[$key] = $entry;
                
            } catch (\Error $ex) {
                print_r($ex->getTraceAsString());
                    throw $ex;
            }
        }
        
        $product->setMediaGalleryEntries($entries);
        
    }
    
    protected function createMediaImagesEntries($images)
    {
        
        $entries = [];
        
        foreach ($images as $key => $value) {
            $fileTitle = $value['title'] ?? null;
            /** @var \Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterfaceFactory; $mediaEntry */
            $mediaEntry = $this->entryFactory->create();
            
            $mediaEntry->setMediaType('image');
            $mediaEntry->setLabel($fileTitle);
            
            $entries[] = $mediaEntry;
        }
        
        return $entries;
    }
    
    public function execute() {
        $req=$this->getRequest()->getParams();
        
        $uid=$this->helper->getCustomerId();
        
        if(!$uid){
            $page= $this->resultPageFactory->create(ResultFactory::TYPE_JSON);
            $this->messageManager->addErrorMessage(__("Invalid access"));
            
            $page->setHttpResponseCode(\Magento\Framework\Webapi\Exception::HTTP_FORBIDDEN);
            $page->setData(['error_message' => __('No access')]);
            return $page;
        }
        
///        $name=$req['name'];
        $wt=isset($req['weight'])?$req['weight']:0;
        $pid=isset($req['pid'])?$req['pid']:0;
        
        $type=$req['type'];
        
        
        $price=(float)$req['price'];
        $color=$req['color'];
        $rent4=$req['rent4'];
        $rent8=$req['rent8'];
        $len=$req['length'];
        $type=$req['type'];
        
        $brand=$req['brand'];
        $size=$req['size'];
        $depo=(float)$req['deposit'];
        
        $status=0;//isset($req['status'])?(int)$req['status']:0;
        $images=$req['images']??[];
        $sites=$this->helper->getStoreManager()->getWebsites();
        $ids=[];
        foreach($sites as $s){
            $ids[]=$s->getId();
        }
        
        
        $rentalset=4;
        $stores=$req['stores'];
        $name1='';
        $stvars=[];
        $defstid=$this->helper->getStoreManager()->getDefaultStoreView()->getId();
        
        foreach($stores as $st){
            
            $id=$st['id'];
            if($id==$defstid){
                $name1=$st['vars']['name'];
                break;
            }
        }
        
        
        if($pid){

            $product=$this->repo->getById($pid,true,0,true);
            if(!$product||!$product->getId()||$product->getData('uid')!=$this->helper->getCustomerId()){
                $page= $this->resultPageFactory->create(ResultFactory::TYPE_JSON);
                $this->messageManager->addErrorMessage(__("Invalid access"));
                
                $page->setHttpResponseCode(\Magento\Framework\Webapi\Exception::HTTP_FORBIDDEN);
                $page->setData(['error_message' => __('No access')]);
                return $page;
            }
         
            
        }else{
        $product=$this->factory->create();
        $sku='c_'.$this->helper->getCustomerId().'_'.uniqid();
        $product->setSku($sku); // Set your sku here
        $rentalset=$product->getDefaultAttributeSetId();
        $product->setData('uid',$uid);
        //$product->setName($name); // Name of Product
        $product->setAttributeSetId($rentalset);
        
        $product->setTypeId($type=='cloth'?ClothType::TYPE_CODE:AccessoryType::TYPE_CODE);
        
        }
        
        if(!$pid){
        $product->setData('vip_discount',0);
        $product->setData('wash',4);
        $product->setData('deposit',0);
        
        
        }
        
        $cids=$req['cats']??[];
        
        
        $product->setStatus(0); // Status on product enabled/ disabled 1/0
        $product->setWeight($wt); // weight of product
        $product->setVisibility(Visibility::VISIBILITY_BOTH); // visibilty of product (catalog / search / catalog, search / Not visible individually)
        $product->setCustomAttribute('tax_class_id', 0);
        $product->setTypeId($type); // type of product (simple/virtual/downloadable/configurable)
        $product->setPrice($price); // price of product
        $product->setData('rent4',$rent4); // price of product
        $product->setData('rent8',$rent8); // price of product
        $product->setData('brand',$brand); // price of product
        $product->setData('size',$size); // price of product
        $product->setData('color',$color); // price of product
        $product->setData('length',$len); // price of product
        $product->setData('deposit',$depo); // price of product
        
        $product->setData('price_view',0);
        
        
        //$product->setData('color',$color); // price of product
        
        $objectManager=$this->_objectManager;
        /** @var \Magento\CatalogInventory\Api\Data\StockItemInterface $stockItem */
        $stockItem = $objectManager->create(\Magento\CatalogInventory\Api\Data\StockItemInterface::class);
        $stockItem->setUseConfigManageStock(false);
        $stockItem->setManageStock(false);
        $stockItem->setIsInStock(true);
        $stockItem->setQty(1);
        
        /** @var \Magento\Catalog\Api\Data\ProductExtensionInterface $productExtension */
        $productExtension =        $product->getExtensionAttributes();
        ///$objectManager->create(\Magento\Catalog\Api\Data\ProductExtensionInterface::class);
        $productExtension->setStockItem($stockItem);
        
        $product->setWebsiteIds($ids)->setStoreId(0)->setName($name1);
        $product->setExtensionAttributes($productExtension);
        
        /**@var $product \Magento\Catalog\Model\Product */

        $i=0;
        $flags = ['image','thumbnail','small_image'];
        $files=[];
        $labels=[];
        $gfiles=[];
        $fim=false;
    ///$product->setMediaGalleryEntries(null);
   
        $entries = [];
        $i=0;
       foreach($images as $image) {
           if(isset($image['id'])&&$image['id']){
               continue;
           }
            $s=$this->uploader->getTPath($image['name']);
            if(!$s){
                continue;
            }
            $s=$this->uploader->getFullPath($s);
            $files[$i]=$s;
            $mediaEntry = $this->entryFactory->create();
            $mediaEntry->setMediaType('image');
            $mediaEntry->setLabel($image['label']);
            $entries[$i++] = $mediaEntry;
            
        
            
        }
        
        if(count($entries))
        $this->addImagesToProductGallery($product, $entries, $files);
        $product=$this->repo->save($product,false);
        $pid=$id=$product->getId();  
       $retain=[];
        $oldcids=$product->getCategoryIds();
        
//        if(count($cids)){
  ///        $this->catmgmt->assignProductToCategories($product->getSku(), $cids);    
    //    }
        $product->setCategoryIds($cids);
        
        foreach($stores as $store){
            $stid=$store['id'];
            
            if($stid==$defstid){
                
                foreach ($store['vars'] as $code=>$value){
                    $product->addAttributeUpdate($code, $value, 0);
                }
                
                $product->save();
                
                
            }
            foreach ($store['vars'] as $code=>$value){
                $product->addAttributeUpdate($code, $value, $stid);
                
            }
            $product->save();
            
            
            
            
            
         //   $product=$this->repo->save($product);
            
        }
        $this->messageManager->addSuccessMessage(__('Product Saved.'));
        $page= $this->resultPageFactory->create(ResultFactory::TYPE_JSON);
        
        return        $page->setData(['ok'=>$pid,'files'=>$files,'v'=>3]);
        
    }
    
    public function execute1()
    {
        
        /*
         * 
         */
    	
        $page= $this->resultPageFactory->create(ResultFactory::TYPE_JSON);
        
return        $page->setData(['ok'=>1]);
    }
}
