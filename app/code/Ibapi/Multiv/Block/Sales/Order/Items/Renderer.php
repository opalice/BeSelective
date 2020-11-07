<?php 
namespace  Ibapi\Multiv\Block\Sales\Order\Items;

class Renderer extends  \Magento\Bundle\Block\Sales\Order\Items\Renderer {
/**
 * @param \Magento\Sales\Model\Order\Item $item
 * {@inheritDoc}
 * @see \Magento\Bundle\Block\Sales\Order\Items\Renderer::getValueHtml()
 */ 
    public function getValueHtml($item)
    {
        
        if($item->getParentItem()&&strpos($item->getParentItem()->getSku(),'res-')!==0){
            return parent::getValueHtml($item);
        }
        if ($attributes = $this->getSelectionAttributes($item)) {
            return sprintf('%d', $attributes['qty']) . ' x ' . $this->escapeHtml($item->getName()) . " "
                . $this->getOrder()->formatPrice($item->getPrice());
        } else {
            return $this->escapeHtml($item->getName());
        }
    }
    
    /**
     * @param mixed $item
     * @return mixed|null
     */
    
}