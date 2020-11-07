<?php
namespace Ibapi\Multiv\Model;
use Ibapi\Multiv\Api\Data\SubscriptionInterface;

class Subscription extends \Magento\Framework\Model\AbstractModel implements  SubscriptionInterface,\Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'multiv_sub';
    
    protected function _construct()
    {
        $this->_init('Ibapi\Multiv\Model\ResourceModel\Subscription');
    }
    public function getIdentities()
    {
        
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    
}