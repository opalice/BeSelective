<?php
namespace Magento\Catalog\Helper\Product\Compare;

/**
 * Interceptor class for @see \Magento\Catalog\Helper\Product\Compare
 */
class Interceptor extends \Magento\Catalog\Helper\Product\Compare implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory, \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, \Magento\Customer\Model\Visitor $customerVisitor, \Magento\Customer\Model\Session $customerSession, \Magento\Catalog\Model\Session $catalogSession, \Magento\Framework\Data\Form\FormKey $formKey, \Magento\Wishlist\Helper\Data $wishlistHelper, \Magento\Framework\Data\Helper\PostHelper $postHelper)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $itemCollectionFactory, $catalogProductVisibility, $customerVisitor, $customerSession, $catalogSession, $formKey, $wishlistHelper, $postHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function getAddUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAddUrl');
        if (!$pluginInfo) {
            return parent::getAddUrl();
        } else {
            return $this->___callPlugins('getAddUrl', func_get_args(), $pluginInfo);
        }
    }
}
