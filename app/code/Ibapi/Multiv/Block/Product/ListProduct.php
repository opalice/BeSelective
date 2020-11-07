<?php 
namespace  Ibapi\Multiv\Block\Product;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class ListProduct extends  \Magento\Catalog\Block\Product\ListProduct{
    protected  $helper;
    protected $d;
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Ibapi\Multiv\Helper\Data $helper,
        
        array $data = []
        
        
        
        )
    {
        
        
        $this->helper=$helper;
        
        
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper,$data);
        list($sd,$d,)=$this->helper->setSearchCookies($this->getRequest()->getParams());
        
        $this->d=$d;
        
    }
    
    
    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getProductPrice(\Magento\Catalog\Model\Product $product)
    {
        
        if(in_array($product->getTypeId(), [ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])){
            $product->setData('salable',false);
            
            $block= $this->getLayout()->getBlock('rental_price_block');$block->setProduct($product);
return            $block->toHtml();
            
        }
        return parent::getProductPrice($product);
        
        
    }
    
    
}


