<?php 
namespace  Ibapi\Multiv\Block\Product;


class Rentalprice extends \Magento\Framework\View\Element\Template{
    /**
     * 
     * @var \Magento\Catalog\Model\Product
     */
    var $_product;
    protected $_registry;
    protected $_code;
    protected  $helper;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Pricing\Helper\Data $helper,
        $data=[]){
            
            parent::__construct($context,$data);
           $nv=$registry->registry('multiv');
           $d=$nv['r']??4;
           
           $this->_code=$d!="8"?'rent4':'rent8';
        $this->helper=$helper;
    }
    public function formatPrice($amt){
      return  $this->helper->currency($amt,true,false);
    }
    
    
    public function setProduct(\Magento\Catalog\Model\Product $product){
        $this->_product=$product;
        return $this;
    }
    public function getRent(){
        
       $amt= $this->_product->getData($this->_code);
       return $amt;
       
    }
    public function getDeposit(){
        return $this->_product->getData('deposit');
    }
    public function getPrice(){
        return $this->_product->getFinalPrice(1);
    }
    
    
}