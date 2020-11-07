<?php
namespace  Ibapi\Multiv\Model;

use Magento\CatalogInventory\Model\Quote\Item\QuantityValidator;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\DepType;
use Magento\CatalogInventory\Observer\QuantityValidatorObserver;
use Ibapi\Multiv\Model\Type\SubType;

class QtyVld extends  QuantityValidatorObserver{
    /**
     * {@inheritDoc}
     * @see \Magento\CatalogInventory\Model\Quote\Item\QuantityValidator::validate()
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        
        /* @var $quoteItem \Magento\Quote\Model\Quote\Item */
        $quoteItem = $observer->getEvent()->getItem();
        
        if (!$quoteItem ||
            !$quoteItem->getProductId() ||
            !$quoteItem->getQuote() ||
            $quoteItem->getQuote()->getIsSuperMode()
            ) {
                return;
            }
            
        
        if(in_array($quoteItem->getProductType(),[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE,DepType::TYPE_CODE,SubType::TYPE_CODE])){
            
            return;
        }
        
        
        
        $sku=$quoteItem->getProduct()->getSku();
        if(strpos($sku, 'res-')===0){
            
            return;
        }
        
        
        
        return parent::execute($observer);
    }

    
 
    
}