<?php
namespace Netbaseteam\Faq\Controller\Adminhtml\Category\MassStatus;

/**
 * Interceptor class for @see \Netbaseteam\Faq\Controller\Adminhtml\Category\MassStatus
 */
class Interceptor extends \Netbaseteam\Faq\Controller\Adminhtml\Category\MassStatus implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Netbaseteam\Faq\Model\ResourceModel\Faqcategory\CollectionFactory $collectionFactory)
    {
        $this->___init();
        parent::__construct($context, $filter, $collectionFactory);
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
