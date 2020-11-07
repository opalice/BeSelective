<?php
namespace  Ibapi\Multiv\Model\Type;

use ArgumentSequence\ParentClass;
use Magento\Framework\DataObject;
use Magento\Catalog\Model\Product;
class ClothType extends \Ibapi\Multiv\Model\Type\RentalType
{
    
    
    const TYPE_CODE= 'cloth';
    
    public function getCode(){
        return self::TYPE_CODE;
    }
    
    
    
    
    
    
    
    
    
    
    
}