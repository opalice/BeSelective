<?php
namespace Ibapi\Multiv\Api;

use Ibapi\Multiv\Api\Data\SubscriptionInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;

interface SubscriptionRepositoryInterface 
{
    public function save(SubscriptionInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(SubscriptionInterface $page);

    public function deleteById($id);
}
