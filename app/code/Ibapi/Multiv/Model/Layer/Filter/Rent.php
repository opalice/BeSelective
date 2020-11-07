<?php 
namespace Ibapi\Multiv\Model\Layer\Filter;
use Ibapi\Multiv\Model\Layer\Filter\Attribute;

class Rent extends  Attribute{

    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        list($sd,$d,)=$this->_helper->getSearchCookies();
    
     
        
        $v=$this->getRequestVar();
        
       $val= $request->getParam($v);
       if(!$val){
           return $this;
       }

       $this->setData('value',$val);
        $this->setData('used',1);
      
 
        list($min,$max)=explode(',', $val);
       $min=(float)$min;
       $max=(float)$max;
       
       $productCollection = $this->getLayer()->getProductCollection();
       
       if($min){
           $productCollection->addAttributeToFilter($this->_requestVar,['gteq'=>$min]);
       }
       
       if($max){
           $productCollection->addAttributeToFilter($this->_requestVar,['lteq'=>$max]);
       }
       
       $state=$this->_createItem($this->_requestVar,$val)->setVar($this->_requestVar);
       
       $this->getLayer()
       ->getState()
       ->addFilter($state
           );
       
       
       
       
    }
    
    protected function _getItemsData()
    {
        
        return [];
    }
    
    
    
}

