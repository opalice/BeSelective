<?php 
namespace  Ibapi\Multiv\Model;

use Magento\Catalog\Model\Layer as L;

class Layer extends  L{
    
    
    public function aroundGetProductCollection()
    {
       $collection= parent::getProductCollection();
        
             if($this->getRequest()->getParam('brand'))
            $collection->addFieldToFilter('brand', $this->getRequest()->getParam('brand'));
           
        
    }
    
    public function apply(){
        parent::apply();
        file_put_contents('prepx.txt',' layer '.__FILE__." ".date('Y-m-d H:i:s')."\n",FILE_APPEND);
        
    }
    
}