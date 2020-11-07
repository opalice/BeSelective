<?php
namespace Ibapi\Multiv\Model\ResourceModel\Rtable;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Ibapi\Multiv\Model\Rtable','Ibapi\Multiv\Model\ResourceModel\Rtable');
    }
    
}
