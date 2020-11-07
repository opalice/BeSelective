<?php 
namespace  Ibapi\Multiv\Model\Layer\Filter;

use Magento\Catalog\Model\Layer\FilterList as Listx;

class FilterList extends  Listx{
    
 
 
    /**
     * Get Attribute Filter Class Name
     *
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
     * @return string
     */
    protected function getAttributeFilterClass(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute)
    {
        
        
        $filterClassName = $this->filterTypes[self::ATTRIBUTE_FILTER];
        
        
        if(in_array($attribute->getAttributeCode(),['brand','length','color'])){
            $filterClassName =Attribute::class;
            
            
        }
        else if ($attribute->getAttributeCode() == 'size') {
            $filterClassName = Size::class;
        }
        else if(in_array($attribute->getAttributeCode(),['rent4','rent8'])){
            $filterClassName =Rent::class;
            
            
        }else if ($attribute->getAttributeCode() == 'price') {
            $filterClassName = $this->filterTypes[self::DECIMAL_FILTER];
        } elseif ($attribute->getBackendType() == 'decimal') {
            $filterClassName = $this->filterTypes[self::DECIMAL_FILTER];
        }
        file_put_contents('filtl.txt', "code ".$attribute->getAttributeCode()." class $filterClassName \n",FILE_APPEND);
        
        return $filterClassName;
    }
    
    
}