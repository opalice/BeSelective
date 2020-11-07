<?php

use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;

class Copyquote
{
    
    public function aroundConvert(\Magento\Quote\Model\Quote\Item\ToOrderItem $subject, callable $proceed, $quoteItem, $data)
    {
        
        // get order item
        $orderItem = $proceed($quoteItem, $data);
        
        
        if( $orderItem->getProductType() ==AccessoryType::TYPE_CODE||$orderItem->getProductType() ==ClothType::TYPE_CODE ){
            if ($additionalOptionsQuote = $quoteItem->getOptionByCode('additional_options')) {
                //To do
                // - check to make sure element are not added twice
                // - $additionalOptionsQuote - may not be an array
                if($additionalOptionsOrder = $orderItem->getProductOptionByCode('additional_options')){
                    $additionalOptions = array_merge($additionalOptionsQuote, $additionalOptionsOrder);
                }
                else{
                    $additionalOptions = $additionalOptionsQuote;
                }
                if(count($additionalOptions) > 0){
                    $options = $orderItem->getProductOptions();
                    $options['additional_options'] = unserialize($additionalOptions->getValue());
                    $orderItem->setProductOptions($options);
                }
            }
        }
        
        return $orderItem;
    }
}