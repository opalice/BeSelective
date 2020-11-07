<?php
namespace Nwdthemes\Revslider\Controller\Adminhtml\Revslider\Slide;

/**
 * Interceptor class for @see \Nwdthemes\Revslider\Controller\Adminhtml\Revslider\Slide
 */
class Interceptor extends \Nwdthemes\Revslider\Controller\Adminhtml\Revslider\Slide implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $resultPageFactory);
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
