<?php
namespace  Ibapi\Multiv\Model;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;

class Saleable implements ObserverInterface
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
        
        $sale=$observer->getEvent()->getData('salable');
///        $sale->setIsSalable(true);
        
        $product=$observer->getEvent()->getData('product');
    //    $this->helper->log("checking saleable ".$product->getSku()." T ".$product->getTypeId());
        if(strpos($product->getSku(),'res-')===0||$product->getId()==$this->helper->getValue("rentals/procat/subid")){
            $this->helper->log("salable: ".$product->getId()." sal :".$sale->getData('salable')."\n");
            
            $sale->setIsSalable(true);
            
            return $this;
        }
        
        if(!in_array($product->getTypeId(), [ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])){
            return $this;
        }
        
        $sale->setIsSalable(true);
        
        
//       $this->helper->log("\nmultiv:checked sale ".$product->getId()." sal :".$sale->getData('salable')."\n");

        $sale->setIsSalable(true);
        return $this;
        
    }
}