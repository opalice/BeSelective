<?php
namespace Ibapi\Multiv\Model\ResourceModel;
class Thing extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('ibapi_multiv_thing','ibapi_multiv_thing_id');
    }
}
