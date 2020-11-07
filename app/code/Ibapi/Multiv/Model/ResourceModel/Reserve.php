<?php 
namespace Ibapi\Multiv\Model\ResourceModel;
class Reserve extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('multiv_res','id');
    }
    
    
    
}
