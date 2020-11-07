<?php
namespace Ibapi\Multiv\Model\ResourceModel;
class Test extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('ibapi_multiv_test','ibapi_multiv_test_id');
    }
}
