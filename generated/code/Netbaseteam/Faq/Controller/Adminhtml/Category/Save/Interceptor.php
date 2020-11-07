<?php
namespace Netbaseteam\Faq\Controller\Adminhtml\Category\Save;

/**
 * Interceptor class for @see \Netbaseteam\Faq\Controller\Adminhtml\Category\Save
 */
class Interceptor extends \Netbaseteam\Faq\Controller\Adminhtml\Category\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Netbaseteam\Faq\Controller\Adminhtml\Category\PostDataProcessor $dataProcessor, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime)
    {
        $this->___init();
        parent::__construct($context, $dataProcessor, $dateTime);
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
