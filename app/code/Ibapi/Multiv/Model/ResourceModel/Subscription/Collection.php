<?php
namespace Ibapi\Multiv\Model\ResourceModel\Subscription;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Ibapi\Multiv\Model\Subscription','Ibapi\Multiv\Model\ResourceModel\Subscription');
    }
    
}
