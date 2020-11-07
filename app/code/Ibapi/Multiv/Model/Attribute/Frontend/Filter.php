<?php 
namespace Ibapi\Multiv\Model\Attribute\Frontend;

class Filter extends \Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend
{
    public function getValue(\Magento\Framework\DataObject $object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        if(is_array($value)) $value=implode("", $value);
        return  $value;
    }
}