<?php
namespace Ibapi\Multiv\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Deli extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $uploader;
    protected  $helper;
    protected $repo;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultPageFactory,
        \Ibapi\Multiv\Helper\Data $helper,
        ProductRepositoryInterface $productRepo,
        \Ibapi\Multiv\Model\ImageUploader $uploader)
    {
        $this->resultPageFactory = $resultPageFactory;        
        $this->uploader=$uploader;
        parent::__construct($context);
        $this->repo=$productRepo;
        $this->helper=$helper;
    }
    public function execute() {
        $msg='';
        
        $file = $this->getRequest()->getParams();
        if(isset($file['id'])&&isset($file['pid'])){
            $pid=$file['pid'];
            
            $product=$this->repo->getById($pid,true,null,true);
            $imgs = $product->getMediaGalleryEntries();
            
            
            foreach ($imgs as $key => $entry) {
                if($key== $id){
                    unset($imgs[$key]);
                }
                
            }
            $product->setMediaGalleryEntries($imgs);
            $this->repo->save($product);
            
            
        }
        
         if(isset($file['url'])&&$this->uploader->isNewFile($file['url'])){
                             $new=true;
                             $name=$file['name'];
         }else{
             $page= $this->resultPageFactory->create(ResultFactory::TYPE_JSON);
             
             return        $page->setData(['ok'=>0 ,'msg'=>__('Invalid'),'file'=>$file]);
             
         }
         $del=$this->uploader->delUrl($name,$new);
        
        
        $page= $this->resultPageFactory->create(ResultFactory::TYPE_JSON);
        
        return        $page->setData(['ok'=>$del ,'msg'=>$del?__('Delete'):__('Cannot delete'),'file'=>$file]);
        
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
