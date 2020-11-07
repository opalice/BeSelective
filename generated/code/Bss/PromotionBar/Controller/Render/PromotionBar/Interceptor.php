<?php
namespace Bss\PromotionBar\Controller\Render\PromotionBar;

/**
 * Interceptor class for @see \Bss\PromotionBar\Controller\Render\PromotionBar
 */
class Interceptor extends \Bss\PromotionBar\Controller\Render\PromotionBar implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Bss\PromotionBar\Helper\Data $helper, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Framework\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($resultRawFactory, $helper, $layoutFactory, $jsonHelper, $context);
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
