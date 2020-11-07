<?php
namespace Magento\Quote\Model\Quote\Item\Repository;

/**
 * Interceptor class for @see \Magento\Quote\Model\Quote\Item\Repository
 */
class Interceptor extends \Magento\Quote\Model\Quote\Item\Repository implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Quote\Api\Data\CartItemInterfaceFactory $itemDataFactory, array $cartItemProcessors = array())
    {
        $this->___init();
        parent::__construct($quoteRepository, $productRepository, $itemDataFactory, $cartItemProcessors);
    }

    /**
     * {@inheritdoc}
     */
    public function getList($cartId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getList');
        if (!$pluginInfo) {
            return parent::getList($cartId);
        } else {
            return $this->___callPlugins('getList', func_get_args(), $pluginInfo);
        }
    }
}
