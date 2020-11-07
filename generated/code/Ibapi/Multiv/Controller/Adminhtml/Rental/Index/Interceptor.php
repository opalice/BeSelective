<?php
namespace Ibapi\Multiv\Controller\Adminhtml\Rental\Index;

/**
 * Interceptor class for @see \Ibapi\Multiv\Controller\Adminhtml\Rental\Index
 */
class Interceptor extends \Ibapi\Multiv\Controller\Adminhtml\Rental\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Ibapi\Multiv\Helper\Data $helper, \Magento\Framework\Controller\ResultFactory $resultPageFactory, \Magento\Catalog\Api\ProductRepositoryInterface $pi)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $helper, $resultPageFactory, $pi);
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
