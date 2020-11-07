<?php
namespace  Ibapi\Multiv\Controller\Index;

class Productlist extends  \Magento\Framework\App\Action\Action{
    
    
    public function execute(){
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        //$resultPage = $this->resultPageFactory->create();

        $this->_view->loadLayout();
        $this->_view->renderLayout();
        
        /*
        $resultPage->getConfig()->getTitle()->set(__('My Products'));
        
       $block = $resultPage->getLayout()->getBlock('customer.account.link.back');
        if ($block) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        return $resultPage;
        */
        
    }
    
    
    
    
}