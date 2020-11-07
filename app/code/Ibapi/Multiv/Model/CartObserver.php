<?php 
namespace  Ibapi\Multiv\Model;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;

class CartObserver implements ObserverInterface
{
    protected $_productRepository;
    protected $_cart;
    protected $helper;
    protected $_request;
    public function __construct(\Ibapi\Multiv\Helper\Data $helper,\Magento\Catalog\Model\ProductRepository $productRepository, \Magento\Checkout\Model\Cart $cart,\Magento\Framework\App\Request\Http $request){
        $this->_productRepository = $productRepository;
        $this->_cart = $cart;
        $this->helper=$helper;
        $this->_request=$request;
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
        $product=$observer->getEvent()->getData('product');
        $parent=$item->getParentItem();
        $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
       
        if(strpos($product->getSku(), 'res-')===0){
            
        }else{

            $this->helper->log("\n cartobserver not add\n");
            return;
        }
        
       
        if($this->helper->getCustomerGroup()==5){
            $this->helper->log("\n cartobserver already\n");
            return;
        }
        
        /*
        $type=$product->getTypeId();
        
        
        if($parent){
            $parent=1;
        }else{
            $parent='x';
        }
        */
        
        $this->helper->log("\nmultiv:in cartobserver type parent $parent\n");
 

 

        $sub=$this->_request->getParam('multiv_sub');
        
        if(!$sub){
            $this->helper->log("\ncartobserver : multiv:no sub\n");
            return;
        }
        
        $subid=$this->helper->getValue("rentals/procat/subid");
        if($subid){
        $params = array(
            'product' => $subid,
            'qty' => 1
        );
        $this->helper->log("cartobserver: $subid \n");
        $_product = $this->_productRepository->getById($subid);
        $this->_cart->addProduct($_product,$params);
        
///        $this->_cart->getCheckoutSession()->setLastAddedProductId($subid);
///        $this->_cart->save();
        $this->helper->log("\nmultiv:added cartobserver $subid \n");
        }
    }
}