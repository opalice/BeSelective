<?php 
namespace Ibapi\Multiv\Model;


class CartAdd implements  \Magento\Framework\Event\ObserverInterface{
    
    
    
    
    
    
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $pid = $observer->getRequest()->getParam('product');
        $quote       = $this->_cart->getQuote();
        
        if(!empty($quote)) {
            $customAttribute = $quote->getData('custom_attribute');
            
            if(!empty($customAttribute)) {
                $controller = $observer->getControllerAction();
                $storeId     = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getId();
                $product    = $this->_productRepository->getById($addedItemId, false, $storeId);
                $observer->getRequest()->setParam('product', null);
                
                $this->_messageManager->addError(__('This product cannot be added to your cart.'));
                echo false;
                
                $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
                $this->redirect->redirect($controller->getResponse(), 'checkout/cart/index');
            }
        }  
    
    
    
    
    
}

}