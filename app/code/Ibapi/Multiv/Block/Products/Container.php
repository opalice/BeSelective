<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Multiv\Block\Products;

/**
 * Sales order history extra container block
 *
 * @api
 * @since 100.1.1
 */
class Container extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Api\Data\ProductInterface
     */
    private $product;

    /**
     * Set order
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return $this
     * @since 100.1.1
     */
    public function setProduct(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Get order
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    private function getProduct()
    {
        return $this->product;
    }

    /**
     * Here we set an order for children during retrieving their HTML
     *
     * @param string $alias
     * @param bool $useCache
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @since 100.1.1
     */
    public function getChildHtml($alias = '', $useCache = false)
    {
        $layout = $this->getLayout();
        if ($layout) {
            $name = $this->getNameInLayout();
            foreach ($layout->getChildBlocks($name) as $child) {
                $child->setOrder($this->getOrder());
            }
        }
        return parent::getChildHtml($alias, $useCache);
    }
}
