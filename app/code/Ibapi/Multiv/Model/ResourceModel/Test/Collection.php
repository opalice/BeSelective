<?php
namespace Ibapi\Multiv\Model\ResourceModel\Test;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Ibapi\Multiv\Model\Test','Ibapi\Multiv\Model\ResourceModel\Test');
    }
}
