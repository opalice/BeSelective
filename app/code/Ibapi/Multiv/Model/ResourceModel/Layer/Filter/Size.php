<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Multiv\Model\ResourceModel\Layer\Filter;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;

/**
 * Catalog Layer Attribute Filter Resource Model
 *
 * @api
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @since 100.0.2
 */
class Size extends \Magento\Catalog\Model\ResourceModel\Layer\Filter\Attribute
{
    /**
     * Initialize connection and define main table name
     *
     * @return void
     */
    protected $size2id;
    protected $filter;
    protected  $label;
    protected  $count;
    protected  $value;
    
    
    /**
     * Initialize connection and define main table name
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('catalog_product_entity_varchar', 'entity_id');
        $this->size2id=$this->_getConnection('core_read')->fetchOne("select attribute_id from ".$this->getTable('eav_attribute')." where attribute_code='size2'");
//        file_put_contents('size.txt', "size ".$this->size2id."  \n",FILE_APPEND);
    }
 
    /**
     * Apply attribute filter to product collection
     *
     * @param \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter
     * @param int $value
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return $this
     */
    public function applyFilterToCollection(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter, $value)
    {
   ///     file_put_contents('size.txt', "applyfilter ".$this->size2id."  \n");
        $collection = $filter->getLayer()->getProductCollection();
        $attribute = $filter->getAttributeModel();
        $connection = $this->getConnection();
        $tableAlias = $attribute->getAttributeCode() . '_idx';
        $tableAlias2 = 'size2_idx';
/*
        $productSize2AttributeId = $this->_objectManager
        
        ->create('Magento\Eav\Model\Config')
        
        ->getAttribute(\Magento\Catalog\Model\Product::ENTITY, \Magento\Catalog\Api\Data\ProductInterface::STATUS)
        
        ->getAttributeId();
*/        
        
        
        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
//            $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
        ];
            
            
            $collection->getSelect()->join(
                [$tableAlias=> $this->getTable('catalog_product_entity_varchar')],
                implode(' AND ', $conditions),
                []
                );
            
            $conditions = [
                "{$tableAlias2}.entity_id = e.entity_id",
                $connection->quoteInto("{$tableAlias2}.attribute_id = ?", $this->size2id),
  //              $connection->quoteInto("{$tableAlias2}.store_id = ?", $collection->getStoreId()),
                ];
        $collection->getSelect()->join(
            [$tableAlias2 => $this->getTable('catalog_product_entity_varchar')],
            implode(' AND ', $conditions),
            []
        );

        if(is_array($value)){
            $wconditions=  "\n( ". $connection->quoteInto("{$tableAlias}.value  in(?)",$value )." OR ".$connection->quoteInto("{$tableAlias2}.value  in(?)", $value)." )\n";
        }else{
            $wconditions= "\n( ".$connection->quoteInto("{$tableAlias}.value = ?", $value)." OR ".$connection->quoteInto("{$tableAlias2}.value = ?", $value)." \n)\n";
        }
        $collection->getSelect()->where($wconditions);

//       file_put_contents('sizeallx.txt', " ".$collection->getSelect()->assemble()."  \n");
        
        return $this;
    }

    /**
     * Retrieve array with products counts per attribute option
     *
     * @param \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return array
     */
    public function getCount(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
        
       /// file_put_contents('size.txt', "sizecount ".$this->size2id." \n",FILE_APPEND);
        // clone select from collection with filters
        $select = clone $filter->getLayer()->getProductCollection()->getSelect();
        
        
        
        // reset columns, order and limitation conditions
        $select->reset(\Magento\Framework\DB\Select::COLUMNS);
        $select->reset(\Magento\Framework\DB\Select::ORDER);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);
        
        
        
        $connection = $this->getConnection();
        $attribute = $filter->getAttributeModel();
        $tableAlias = sprintf('%s_idx', $attribute->getAttributeCode());
        
        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
        ];
        $select2=clone $select;
        
        $select->join(
            [$tableAlias => $this->getTable('catalog_product_entity_varchar')],
            join(' AND ', $conditions),
            ['value', 'count' => new \Zend_Db_Expr("COUNT({$tableAlias}.entity_id)")]
        )->group(
            "{$tableAlias}.value"
        );
        

        
        
   ///     file_put_contents('sizec.txt', " ".$select->assemble()." \n###\n",FILE_APPEND);
        
        $p1= $connection->fetchPairs($select);
        $tableAlias2='size2_idx';

        $conditions = [
            "{$tableAlias2}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias2}.attribute_id = ?", $this->size2id),
            ];
        
        $select2->join(
            [$tableAlias2 => $this->getTable('catalog_product_entity_varchar')],
            join(' AND ', $conditions),
            ['value', 'count' => new \Zend_Db_Expr("COUNT({$tableAlias2}.entity_id)")]
            )->group(
                "{$tableAlias2}.value"
            );
            
///            file_put_contents('sizec.txt', " ".$select2->assemble()." \n",FILE_APPEND);
      
            
            $p2= $connection->fetchPairs($select2);
            
            foreach ($p1 as $l=>$v){
                if(isset($p2[$l])){
                    $p1[$l]=(int)$v+(int)$p2[$l];
                }
                
            }
            
            foreach ($p2 as $l=>$v){
                if(!isset($p1[$l])){
                    $p1[$l]=(int)$p2[$l];
                }
                
            }
            
            return $p1;
            
            
    }
}
