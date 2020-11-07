<?php
namespace Bss\PromotionBar\Controller\Adminhtml\Bar\Save;

/**
 * Interceptor class for @see \Bss\PromotionBar\Controller\Adminhtml\Bar\Save
 */
class Interceptor extends \Bss\PromotionBar\Controller\Adminhtml\Bar\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Bss\PromotionBar\Model\BarFactory $barFactory, \Magento\Framework\Registry $registry, \Magento\Backend\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($barFactory, $registry, $context);
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
