<?php
namespace Ibapi\Multiv\Model\ResourceModel\Thing;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Ibapi\Multiv\Model\Thing','Ibapi\Multiv\Model\ResourceModel\Thing');
    }
}
