<?php 
namespace  Ibapi\Multiv\Block\Checkout\Cart\Item;

use Magento\Bundle\Block\Checkout\Cart\Item\Renderer as Parentr;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Magento\Bundle\Helper\Catalog\Product\Configuration;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Message\InterpretationStrategyInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Directory\Model\PriceCurrency;
class Renderer extends Parentr{
    
    public function getOptionList(){
        return [];
    }
    
    public function getProductForThumbnail()
    {
        foreach($this->getItem()->getChildren() as $it){
        
            if(in_array($it->getProduct()->getTypeId(), [AccessoryType::TYPE_CODE,ClothType::TYPE_CODE])){
                
            return   $it->getProduct();
            }
        }
    
    }
    
    public function getProductAdditionalInformationBlock(){
        if(strpos( $this->getItem()->getSku(),'res-')===0){
    
            $lck=        $this->_layout->createBlock('Ibapi\Multiv\Block\Checkout\Cart\Item\AdditionalItemInfo');
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $n=$objectManager->get(PriceCurrency::class);
            $lck->setPi($n);
            $lck->setTemplate('Ibapi_Multiv::cart/item/info.phtml');
            return $lck;
        }
        else{
            return parent::getProductAdditionalInformationBlock();
        }
        
    }
    
    
    
}