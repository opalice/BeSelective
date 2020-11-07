<?php
namespace Nwdthemes\Revslider\Controller\Adminhtml\Revslider\Settings;

/**
 * Interceptor class for @see \Nwdthemes\Revslider\Controller\Adminhtml\Revslider\Settings
 */
class Interceptor extends \Nwdthemes\Revslider\Controller\Adminhtml\Revslider\Settings implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Nwdthemes\Revslider\Model\Revslider\Framework\RevSliderLoadBalancer $revSliderLoadBalancer)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $revSliderLoadBalancer);
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
