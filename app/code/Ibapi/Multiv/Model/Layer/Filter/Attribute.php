<?php
namespace  Ibapi\Multiv\Model\Layer\Filter;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\Data\ProductInterface;

class Attribute extends  \Magento\Catalog\Model\Layer\Filter\Attribute{
   protected $_helper; 
   
   /**
    * @param ItemFactory $filterItemFactory
    * @param \Magento\Store\Model\StoreManagerInterface $storeManager
    * @param \Magento\Catalog\Model\Layer $layer
    * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
    * @param \Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory $filterAttributeFactory
    * @param \Magento\Framework\Stdlib\StringUtils $string
    * @param \Magento\Framework\Filter\StripTags $tagFilter
    * @param array $data
    */
   public function __construct(
       \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
       \Magento\Store\Model\StoreManagerInterface $storeManager,
       \Magento\Catalog\Model\Layer $layer,
       \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
       AttributeFactory $filterAttributeFactory,
       \Magento\Framework\Stdlib\StringUtils $string,
       \Magento\Framework\Filter\StripTags $tagFilter,
       \Ibapi\Multiv\Helper\Data $helper,
       array $data = []
       ) {
       parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $filterAttributeFactory, $string, $tagFilter,$data);
       $this->_resource=$filterAttributeFactory->create();
       $this->_helper=$helper;
   }
   

    
    
    /**
     * Apply filter to collection
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        list($sd,$d,)=$this->_helper->getSearchCookies();
     
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $brand=$color=$size=$length=false;
        switch($this->_requestVar){
            case 'brand':
                $brand=true;
                $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Brand::class);
                break;
            case 'color':
                $color=true;
                $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Color::class);
                break;
            case 'length':
                $length=true;
                $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Length::class);
                break;
                
                
                
        }
        
        
        $opts= $obj->getAllOptions();
        array_shift($opts);
        
        $this->setData('options',$opts);

        
        
        
        
        
        $filter = $request->getParam($this->_requestVar);
        if (is_array($filter)) {
            return $this;
        }
        ///$text = $this->getOptionText($filter);
        if($filter)
            $text=explode(',',$filter);
            else{
                $text=[];
            }
            $this->setValue($text);
            
            if ($filter && count($text)) {
                $this->_items=$this->getItems();
                $this->_getResource()->applyFilterToCollection($this, $text);
                $this->getLayer()->getState()->addFilter($this->_createItem($text, $filter));
            }
            return $this;
       
        
        
        
        
        
    }
    
    protected function getOptionText($optionId)
    {
        return $optionId;
      //  return $this->getAttributeModel()->getFrontend()->getOption($optionId);
    }
    
    protected function _initItems()
    {
        $data  = $this->_getItemsData();
        
        $items = array();
        foreach ($data as $itemData) {
            $item = $this->_createItem(
                (string) $itemData['label'],
                $itemData['value'],
                $itemData['count']
                );
            //            $item->setOptionId($itemData['option_id']);
            $items[] = $item;
        }
        $this->_items = $items;
        $ct=count($this->_items);
        return $this;
    }
    
    /**
     * Get data array for building attribute filter items
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return array
     */
    protected function _getItemsData()
    {
        $attribute = $this->getAttributeModel();
        $this->_requestVar = $attribute->getAttributeCode();
        $cls=get_class($attribute);
        $options = $this->getData('options');
        
        $optionsCount = $this->_getResource()->getCount($this);
        
        foreach ($options as $option) {
            if (is_array($option['value'])) {
                continue;
            }
            if ($option['value']) {
                // Check filter type
                if (!empty($optionsCount[$option['value']])) {
                    
                    $opl=(string)$option['label'];
                    $opv=(string)$option['value'];
                    $c=$optionsCount[$option['value']];
                    file_put_contents('itemdata.txt', "adding $opl. $opv,c $c \n",FILE_APPEND);
                    $this->itemDataBuilder->addItemData(
                        $opl,
                        $option['value'],
                        $optionsCount[$option['value']]
                        );
                }
                
            }
        }
        return $this->itemDataBuilder->build();
    }
    
 
}    
