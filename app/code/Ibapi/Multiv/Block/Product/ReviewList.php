<?php 
namespace  Ibapi\Multiv\Block\Product;

class ReviewList extends  \Magento\Review\Block\Product\View\ListView{

/**
 * 
 * @param \Magento\Review\Model\Review $_review
 */    
    public function getReviewImg($_review){
        
        
        return  $this->_storeManager
        ->getStore()
        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'/review/rev'.$_review->getId().'_'.$_review->getCustomerId().'.jpg';
        
       
        
    }
    
}