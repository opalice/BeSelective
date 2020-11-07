<?php
namespace Ibapi\Multiv\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\App\Action\Action;
use Zend\Log\Processor\RequestId;
use Magento\Framework\App\RequestInterface;

class Image extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $uploader;
    protected  $helper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultPageFactory,
        \Ibapi\Multiv\Helper\Data $data,
        \Ibapi\Multiv\Model\ImageUploader $uploader)
    {
        $this->resultPageFactory = $resultPageFactory;        
        $this->uploader=$uploader;
        parent::__construct($context);
        $this->helper=$data;
    }
    
    public function dispatch(RequestInterface $request){
        
        $uid=$this->helper->getCustomerId();
        
        if(!$uid){
            file_put_contents('upload.txt', 'nouid');
            $this->_actionFlag->set('' ,Action::FLAG_NO_DISPATCH,1);
            $this->_actionFlag->set('', Action::FLAG_NO_DISPATCH_BLOCK_EVENT,1);
        }
        
        return  parent::dispatch($request);
    }
    
    public function execute() {
        $msg='';
        
        $bgImage = $this->getRequest()->getFiles();
         $files=$bgImage->files;
        $bgImage=$files[0];
        $bgImage=(array)$bgImage;
        
        $fileName = ($bgImage && array_key_exists('name', $bgImage)) ? $bgImage['name'] : null;
        
        $page= $this->resultPageFactory->create(ResultFactory::TYPE_JSON);
        $result=[];      
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        
        if ($bgImage && $fileName) {
            try {
                
                $result = $this->uploader->saveFileToTmpDir('files[0]');
                
                //$storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
                /*
                 * $_objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
$storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface'); 
$currentStore = $storeManager->getStore();
$mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);


                 */
//                $model->setBgImage($result['file']);
                $msg=$result;
                $msg['id']='';
                $msg['label']='new';

                return        $page->setData(['ok'=>1,'r'=>$msg,'re'=>$bgImage]);
            } catch (\Exception $e) {
                if ($e->getCode() == 0) {
                    $msg=$e->getMessage();
                    $this->messageManager->addError($e->getMessage());
                }
            }
        }
        return        $page->setData(['ok'=>0,'error'=>__('Upload error'),'r'=>$msg,'re'=>'']);
        
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
