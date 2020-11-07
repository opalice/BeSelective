<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Multiv\Model\Quote\Item;

use Magento\Quote\Model\Quote\Item\CartItemProcessorInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Framework\DataObject\Factory as DataObjectFactory;
use Ibapi\Multiv\Helper\Data;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;




class CartItemProcessor implements CartItemProcessorInterface
{
    /**
     * @var DataObjectFactory
     */
    private $objectFactory;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \Ibapi\Multiv\Model\Extension\ProductOptionFactory
     */
    private $rentalOptionFactory;

    /**
     * @var \Magento\Quote\Model\Quote\ProductOptionFactory
     */
    private $productOptionFactory;

    /**
     * @var \Magento\Quote\Api\Data\ProductOptionExtensionFactory
     */
    private $extensionFactory;

    private $helper;


    public function __construct(
        DataObjectFactory $objectFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Ibapi\Multiv\Model\Extension\ProductOptionFactory $rentalOptionFactory,
        \Magento\Quote\Model\Quote\ProductOptionFactory $productOptionFactory,
        \Magento\Quote\Api\Data\ProductOptionExtensionFactory $extensionFactory,
    	Data $helper
    ) {

    	$this->helper=$helper;
        $this->objectFactory = $objectFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->rentalOptionFactory = $rentalOptionFactory;
        $this->productOptionFactory = $productOptionFactory;
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToBuyRequest(CartItemInterface $cartItem)
    {
  //      file_put_contents('logs/cartitemprocessor1.txt', 'bestart process');
        
        if ($cartItem->getProductOption()
            && $cartItem->getProductOption()->getExtensionAttributes()->getRentalOption()
        ) {
            file_put_contents('logs/cartitemprocessor1.txt', 'start process');
            
            $options = $cartItem->getProductOption()->getExtensionAttributes()->getRentalOption()
                ->getRentalDates();

            if(!is_string($options)){
            	$options="print\n".print_r($options,1);
            }elseif(!$options){
            	$options='';
            }
//            file_put_contents('logs/cartitemprocessor1.txt', $options,FILE_APPEND);


            if (!empty($options)) {
                return $this->objectFactory->create([
                    'rental_option' => $options,
                ]);
            }
        }else{

///        	file_put_contents('cartitemprocessor1.txt','nodata');
        }
        file_put_contents('logs/cartitemprocessor1.txt', 'send process');
        
        return null;
    }

    /**
     * Process cart item product options
     *
     * @param CartItemInterface $cartItem
     * @return CartItemInterface
     */
    public function processOptions(CartItemInterface $cartItem)
    {

//        file_put_contents('logs/cartitemprocessor2.txt', 'start process');
        
    	if($cartItem->getProductType()!==AccessoryType::TYPE_CODE&&$cartItem->getProductType()!==ClothType::TYPE_CODE){
    		return $cartItem;
    	}


    	$option = $cartItem->getOptionByCode('rental_option');
    	

    	if(!$option) return $cartItem;


    	$info = $option->getValue();

    	/* @var  \Ibapi\Multiv\Model\Extension\ProductOption $option */

    	$option=      $this->rentalOptionFactory->create();
    	$option->setRentalDates($info);

    	$productOption=$cartItem->getProductOption();

    	$extensibleAttribute=false;

    	if(!$productOption) $productOption= $this->productOptionFactory->create();else{
    		$extensibleAttribute=$productOption->getExtensionAttributes();


    	}
    	if($extensibleAttribute){




    	}else{
    		$extensibleAttribute=$this->extensionFactory->create();
    	}



    	$extensibleAttribute->setRentalOption($option);
    	$productOption->setExtensionAttributes($extensibleAttribute);
    	$cartItem->setProductOption($productOption);

///

    	//file_put_contents('logs/cartitemprocessor2.txt', 'end process');
    	


    	return $cartItem;
    }
}
