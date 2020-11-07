<?php
namespace Netbaseteam\Faq\Controller\Request\Index;

/**
 * Interceptor class for @see \Netbaseteam\Faq\Controller\Request\Index
 */
class Interceptor extends \Netbaseteam\Faq\Controller\Request\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Netbaseteam\Faq\Helper\Data $dataHelper, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\Registry $registry)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $dataHelper, $resultJsonFactory, $registry);
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
