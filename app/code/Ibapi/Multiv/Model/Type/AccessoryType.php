<?php
namespace  Ibapi\Multiv\Model\Type;

use ArgumentSequence\ParentClass;
use Magento\Framework\DataObject;
use Magento\Catalog\Model\Product;
class AccessoryType extends \Ibapi\Multiv\Model\Type\RentalType
{
    
    const TYPE_CODE= 'accessory';
    
    public function getCode(){
        return self::TYPE_CODE;
    }
    
    
    
    

    
}