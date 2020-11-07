<?php 
namespace  Ibapi\Multiv\Block\Product;

use Magento\Catalog\Pricing\Render;

class Renderprice extends  Render{
    
 
    /**
     * Produce and return block's html output
     *
     * @return string
     */
    protected function _toHtml()
    {
        /** @var  $priceRender  Render*/
        $priceRender = $this->getLayout()->getBlock('rental_price_block');
        $priceRender->setProduct($this->getProduct());
       
        return $priceRender->toHtml();
    }
    
    
}

