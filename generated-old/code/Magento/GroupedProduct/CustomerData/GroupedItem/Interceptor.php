<?php
namespace Magento\GroupedProduct\CustomerData\GroupedItem;

/**
 * Interceptor class for @see \Magento\GroupedProduct\CustomerData\GroupedItem
 */
class Interceptor extends \Magento\GroupedProduct\CustomerData\GroupedItem implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Helper\Image $imageHelper, \Magento\Msrp\Helper\Data $msrpHelper, \Magento\Framework\UrlInterface $urlBuilder, \Magento\Catalog\Helper\Product\ConfigurationPool $configurationPool, \Magento\Checkout\Helper\Data $checkoutHelper, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->___init();
        parent::__construct($imageHelper, $msrpHelper, $urlBuilder, $configurationPool, $checkoutHelper, $scopeConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemData(\Magento\Quote\Model\Quote\Item $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemData');
        if (!$pluginInfo) {
            return parent::getItemData($item);
        } else {
            return $this->___callPlugins('getItemData', func_get_args(), $pluginInfo);
        }
    }
}
