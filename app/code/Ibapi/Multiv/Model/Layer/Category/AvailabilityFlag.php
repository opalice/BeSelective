<?php 
namespace Ibapi\Multiv\Model\Layer\Category;
use Magento\Catalog\Model\Layer\Category\AvailabilityFlag as Flag;

class  AvailabilityFlag extends  Flag
{
    public function isEnabled($layer, array $filters = [])
    {
        return true;
    }
    
    /**
     * @param array $filters
     * @return bool
     */
    protected function canShowOptions($filters)
    {
        foreach ($filters as $filter) {
            if ($filter->getItemsCount()) {
                return true;
            }
        }
        
        return false;
    }
    
    
    
}
    