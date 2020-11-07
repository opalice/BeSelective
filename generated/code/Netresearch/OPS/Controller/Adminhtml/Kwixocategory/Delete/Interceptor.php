<?php
namespace Netresearch\OPS\Controller\Adminhtml\Kwixocategory\Delete;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Adminhtml\Kwixocategory\Delete
 */
class Interceptor extends \Netresearch\OPS\Controller\Adminhtml\Kwixocategory\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Model\CategoryFactory $catalogCategoryFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Registry $registry, \Magento\Backend\Model\Auth\Session $backendAuthSession, \Magento\Framework\Json\EncoderInterface $jsonEncoder, \Netresearch\OPS\Helper\Kwixo $oPSKwixoHelper, \Netresearch\OPS\Model\Kwixo\Category\MappingFactory $oPSKwixoCategoryMappingFactory, \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->___init();
        parent::__construct($context, $catalogCategoryFactory, $storeManager, $registry, $backendAuthSession, $jsonEncoder, $oPSKwixoHelper, $oPSKwixoCategoryMappingFactory, $pageFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
