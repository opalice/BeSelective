<?php
namespace Ibapi\Multiv\Api;

use Ibapi\Multiv\Api\Data\RtableInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;

interface RtableRepositoryInterface 
{
    public function save(RtableInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(RtableInterface $page);

    public function deleteById($id);
}
