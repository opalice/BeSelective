<?php 
namespace Ibapi\Multiv\Model;


use Magento\Framework\Event\ObserverInterface;
use Magento\Review\Model\Review;

class AfterReview implements ObserverInterface{
    
    protected  $request;
    protected $imageUploader;
    protected $helper;
    protected $collectionFactory;
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Ibapi\Multiv\Model\ImageUploader $uploader,
        \Magento\Framework\Registry $registry,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory,
        \Ibapi\Multiv\Helper\Data $helper
        ) {
            $this->request = $request;
            $this->imageUploader=$uploader;
            $this->helper=$helper;
            $this->collectionFactory=$collectionFactory;
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**@var $item \Magento\Quote\Model\Quote\Item\AbstractItem */
        $name=$observer->getEvent()->getName();
        $item=$observer->getObject();
        //event review_save_after
        
        if($name=='review_delete_after'){
            $id=$item->getId();
            $file=$this->imageUploader->delUrl('rev'.$item->getId().'.jpg','review');
            
            return $this;
        }

        if($name=='review_controller_product_init_after'){
            
           
            $product=$observer->getEvent()->getProduct();
            $id=$product->getId();
            $controller=$observer->getEvent()->getControllerAction();

//            $this->helper->log('init review '.$controller->getRequest()->getActionName());
            
            if($controller->getRequest()->getActionName()!=='post'){
                return $this;
                
            }
            /**@var $revi \Magento\Review\Model\ResourceModel\Review  */

                /**@var $rc \Magento\Review\Model\ResourceModel\Review\Collection */
           $rc=$this->collectionFactory->create();

           /*
           $revi= $rc->addCustomerFilter($this->helper->getCustomerId())->addStoreFilter(
               $this->helper->getStoreManager()->getStore()->getId()
                )->addStatusFilter(Review::STATUS_PENDING)->addEntityFilter(
                        'product',
                        $product->getId()
                        )->getLastItem();
            
                        if($revi&&$revi->getData('review_id')){
                    
                        
                            die('Pending review #'.$revi->getData('review_id'));
                        
                        }
                       
                       
            */
                        
                        

            
            
        return $this;    
        }
        if($name=='review_save_after'){
        
            $id=$item->getId();
        
        $file=$this->request->getParam('reviewimg','');

        if($file=='x'){
            
          $this->imageUploader->delUrl('rev'.$id.'_'.$this->helper->getCustomerId().'.jpg','review');
            return;
        }
        
        if($file){
        $s=$this->imageUploader->getTPath($file);
        if(!$s){
            return;
        }
        $dest=$this->imageUploader->copyFileR($file, $id."_".$this->helper->getCustomerId());
     
        $this->imageUploader->delUrl($file,'new');
        $newf=$id."_".$this->helper->getCustomerId();
            
        
        }else{
        }
        
        
        return $this;
        }
        return $this;
        
    }
    
    
    
    
    
    
}