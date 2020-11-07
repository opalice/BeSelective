<?php 
namespace Ibapi\Multiv\Model\Attribute\Backend;

class Rent extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    
    
    /**
     * Before save method
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    public function beforeSave($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
       
        $nl=str_replace('rent','', $attrCode);
        file_put_contents('rent.txt', 'nl '.$nl." rt ".$object->getData('rent'));
        
        if ($object->hasData('rent')) {

            
            $object->setData($attrCode, $object->getData('rent')*$nl);

        }
        
        return $this;
    }
    
}