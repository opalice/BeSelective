<?php
namespace Ebizmarts\MailChimp\Controller\Adminhtml\Stores\Edit;

/**
 * Interceptor class for @see \Ebizmarts\MailChimp\Controller\Adminhtml\Stores\Edit
 */
class Interceptor extends \Ebizmarts\MailChimp\Controller\Adminhtml\Stores\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Ebizmarts\MailChimp\Model\MailChimpStoresFactory $storesFactory, \Ebizmarts\MailChimp\Helper\Data $helper)
    {
        $this->___init();
        parent::__construct($context, $registry, $resultPageFactory, $storesFactory, $helper);
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
