<?php
namespace Bss\PromotionBar\Controller\Adminhtml\Bar\Delete;

/**
 * Interceptor class for @see \Bss\PromotionBar\Controller\Adminhtml\Bar\Delete
 */
class Interceptor extends \Bss\PromotionBar\Controller\Adminhtml\Bar\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Bss\PromotionBar\Model\BarFactory $barFactory, \Magento\Framework\Registry $coreRegistry, \Magento\Backend\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($barFactory, $coreRegistry, $context);
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
