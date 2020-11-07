<?php
namespace  Ibapi\Multiv\Model\Layer\Filter;
use Magento\CatalogSearch\Model\Layer\Filter\Category as CoreCategory;
use Magento\Framework\App\ObjectManager;

use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Zend\Db\Sql\Join;

class Date extends  CoreCategory{
    ///protected   $_requestVar = 'av';
    
    protected  $helper;
    
    /**
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Framework\Escaper $escaper
     * @param CategoryManagerFactory $categoryManager
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Escaper $escaper,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory $categoryDataProviderFactory,
        \Ibapi\Multiv\Helper\Data $helper,
        array $data = []
        ) {
            parent::__construct(
                $filterItemFactory,
                $storeManager,
                $layer,
                $itemDataBuilder,
                $escaper,
                $categoryDataProviderFactory,
                $data
                );
            $this->helper=$helper;
    }
    
    
    /**
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return $this
     */
    function validate(&$date, $format = 'Y-m-d')
    {
        try{
        list($y,$m,$d)=explode('-',$date);
        $m=(int)$m;
        $d=(int)$d;
        if($m<10){
            $m='0'.$m;
        }
        if($d<10){
            $d='0'.$d;
        }
        $date="$y-$m-$d";
        
        
        $d = \DateTime::createFromFormat($format, $date);
        return [$d && $d->format($format) == $date ,$d->getTimestamp()];
        }catch(\Exception $e){
            return [false,false];
        }
    }
    
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        
        

        
        parent::apply($request);

       list($sd,$d,)=$this->helper->getSearchCookies();
       
        
       

         
                
        
        list($f,$d1)=$this->validate($sd);
        if(!$f){
           $sd='';
            $ed='';
        }else{
        $xd=$d-1;
        $ed=date('Y-m-d',strtotime("+ $xd day",$d1));
        }
        
        
        
        
        ///$request->getCookie($name, $default);
        
        /*
        if (!$this->validate($filter)) {
            return $this;
        }
        SELECT `e`.*, 
`cat_index`.`position` AS `cat_index_position`, 
`stock_status_index`.`stock_status` AS `is_salable`, 

`price_index`.`price`, `price_index`.`tax_class_id`, `price_index`.`final_price`, IF(price_index.tier_price IS NOT NULL, 
LEAST(price_index.min_price, price_index.tier_price), price_index.min_price) AS `minimal_price`, `price_index`.`min_price`, 
`price_index`.`max_price`, `price_index`.`tier_price`, 

`at_wash`.`value` AS `wash` 

FROM `catalog_product_entity` AS `e` 
INNER JOIN `catalog_category_product_index` AS `cat_index` ON cat_index.product_id=e.entity_id AND cat_index.store_id=1 AND cat_index.visibility IN(2, 4) AND cat_index.category_id='8' 
INNER JOIN `cataloginventory_stock_status` AS `stock_status_index` ON e.entity_id = stock_status_index.product_id AND stock_status_index.website_id = 0 AND stock_status_index.stock_id = 1 
INNER JOIN `catalog_product_index_price` AS `price_index` ON price_index.entity_id = e.entity_id AND price_index.website_id = '1' AND price_index.customer_group_id = 0 
INNER JOIN `catalog_product_entity_int` AS `at_wash` ON (`at_wash`.`entity_id` = `e`.`entity_id`) AND (`at_wash`.`attribute_id` = '143') AND (`at_wash`.`store_id` = 0) 

 WHERE (stock_status_index.stock_status = 1) AND (at_wash.value > 1) ORDER BY `e`.`entity_id` DESC
        
        
SELECT `e`.*, `cat_index`.`position` AS `cat_index_position`, `stock_status_index`.`stock_status` AS `is_salable`, `price_index`.`price`, `price_index`.`tax_class_id`, `price_index`.`final_price`, IF(price_index.tier_price IS NOT NULL, LEAST(price_index.min_price, price_index.tier_price), price_index.min_price) AS `minimal_price`, `price_index`.`min_price`, `price_index`.`max_price`, `price_index`.`tier_price`, `at_wash`.`value` AS `wash`, `mrent`.* FROM `catalog_product_entity` AS `e` INNER JOIN `catalog_category_product_index` AS `cat_index` ON cat_index.product_id=e.entity_id AND cat_index.store_id=1 AND cat_index.visibility IN(2, 4) AND cat_index.category_id='8' INNER JOIN `cataloginventory_stock_status` AS `stock_status_index` ON e.entity_id = stock_status_index.product_id AND stock_status_index.website_id = 0 AND stock_status_index.stock_id = 1 INNER JOIN `catalog_product_index_price` AS `price_index` ON price_index.entity_id = e.entity_id AND price_index.website_id = '1' AND price_index.customer_group_id = 0 INNER JOIN `catalog_product_entity_int` AS `at_wash` ON (`at_wash`.`entity_id` = `e`.`entity_id`) AND (`at_wash`.`attribute_id` = '143') AND (`at_wash`.`store_id` = 0) INNER JOIN `multiv_rtable` AS `mrent` 
ON mrent.pid=e.entity_id and mrent.ed >='2018-04-20' and mrent.sd<='2018-04-17'  s
WHERE (stock_status_index.stock_status = 1) AND (at_wash.value > 1) ORDER BY `e`.`entity_id` DESC        
        */
       
       
        
        
        $cat= $this->_catalogLayer->getCurrentCategory()->getId();
        $productCollection = $this->getLayer()->getProductCollection();
////        $productCollection->addFieldToSelect('color')->addFieldToSelect('brand')->addFieldToSelect('size')->addFieldToSelect('length')->addFieldToSelect('rent8')->addFieldToSelect('rent4');
///        $productCollection->addAttributeToSelect(['rent','deposit'],Join::JOIN_INNER);
//        $productCollection->addAttributeToFilter('rent',['<',10000]);
        
        
        $cats=explode(',',$this->helper->getValue("rentals/procat/acats"));
        $cats=array_filter($cats,function($c,$v){
            if($c){
                return true;
            }
            return false;
        },ARRAY_FILTER_USE_BOTH);
       
        
        
        if(in_array($cat,$cats)&&$sd){
            

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            /** @var $eavModel \Magento\Catalog\Model\ResourceModel\Eav\Attribute */
            $eavModel = $objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute');
            $entityTypeId=\Magento\Catalog\Model\Product::ENTITY;
            
            $attr=$eavModel->loadByCode($entityTypeId, 'wash');
                      
            $addcode=$attr->getAttributeCode();
            
            $attrid=$attr->getAttributeId();
            
            $productCollection->getSelect()->joinLeft(
                    array('at_'.$addcode => 'catalog_product_entity_int'),
                    sprintf(' `at_%s`.entity_id=`e`.entity_id
                            AND `at_%s`.store_id  = 0 AND
                        `at_%s`.attribute_id=%d  ',
                        $addcode,$addcode,$addcode,$attrid)
                )->joinInner(['mrent'=>'multiv_rtable'], "mrent.pid=e.entity_id and mrent.ed >=adddate('$ed', interval IF(`at_{$addcode}`.value is not NULL, `at_{$addcode}`.value ,0)  day) and mrent.sd<='$sd'",'mrent.*');
        
        //    $productCollection->getSelect()->group($spec);            
                 
        }

       /// $productCollection->load(1);
        
        $cats=explode(',',$this->helper->getValue("rentals/procat/ccats"));
        $cats=array_filter($cats,function($c,$v){
            if($c){
                return true;
            }
            return false;
        },ARRAY_FILTER_USE_BOTH);
            
            
        
            /*
            
            if($brand){
            if(count($brand)>1)
                $productCollection->addFieldToFilter('brand',['in'=>$brand]);
                else{
                    $productCollection->addFieldToFilter('brand',$brand);
                    
                }
            }
            */

                      
        
/*
 SELECT `e`.*, `cat_index`.`position` AS `cat_index_position`, 
`price_index`.`price`, `price_index`.`tax_class_id`, `price_index`.`final_price`, 
IF(price_index.tier_price IS NOT NULL, 
LEAST(price_index.min_price, price_index.tier_price), price_index.min_price) AS `minimal_price`, `price_index`.`min_price`, `price_index`.`max_price`, `price_index`.`tier_price`, `mrent`.* FROM `catalog_product_entity` AS `e` 
INNER JOIN `catalog_category_product_index` AS `cat_index` ON cat_index.product_id=e.entity_id AND cat_index.store_id=10 
AND cat_index.visibility IN(2, 4) 
AND cat_index.category_id='47' 
INNER JOIN `catalog_product_index_price` AS `price_index` ON price_index.entity_id = e.entity_id 
AND price_index.website_id = '1' AND price_index.customer_group_id = 0 
INNER JOIN `multiv_rtable` AS `mrent` ON mrent.pid=e.entity_id and mrent.ed <='2018-06-16' and mrent.sd>='2018-06-13' 
ORDER BY `e`.`entity_id` DESC


  SELECT `e`.*, `cat_index`.`position` AS `cat_index_position` FROM `catalog_product_entity` AS `e`

INNER JOIN `catalog_category_product_index` AS `cat_index` ON cat_index.product_id=e.entity_id AND cat_index.store_id=10 

AND cat_index.visibility IN(2, 4) 
AND cat_index.category_id='47' 

and

INNER JOIN `multiv_rtable` AS `mrent` ON mrent.pid=e.entity_id and mrent.ed <='2018-06-16' and mrent.sd>='2018-06-13' 
ORDER BY `e`.`entity_id` DESC

 
 
  
        $productCollection->addFieldToFilter('group_discount', ['from' => $filter, 'to' => $filter + 9.9999]);
        $this->getLayer()->getState()->addFilter(
            $this->_createItem($this->getDiscountLabel($filter), $filter)
            );
        */
        return $this;
    }
    
    /*
    protected function _getItemsData()
    {
        
     return parent::_getItemsData();
    }*/
    
}