<?php 
namespace  Ibapi\Multiv\Block\Product;

class ReviewRenderer extends  \Magento\Review\Block\Product\ReviewRenderer{
    
 
    /**
     * Get review summary html
     *
     * @param Product $product
     * @param string $templateType
     * @param bool $displayIfNoReviews
     *
     * @return string
     */
    public function getReviewsSummaryHtml(
        \Magento\Catalog\Model\Product $product,
        $templateType = self::DEFAULT_VIEW,
        $displayIfNoReviews = false
        ) {
            $displayIfNoReviews=false;
            if (!$product->getRatingSummary()) {
                $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
            }
            
            if (!$product->getRatingSummary() && !$displayIfNoReviews) {
                return '';
            }
            // pick template among available
            if (empty($this->_availableTemplates[$templateType])) {
                $templateType = self::DEFAULT_VIEW;
            }
            $this->setTemplate('Magento_Review::helper/summary.phtml');
            
            $this->setDisplayIfEmpty($displayIfNoReviews);
            
            $this->setProduct($product);
            
            return $this->toHtml();
    }
    
    
}