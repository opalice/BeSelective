<?php
namespace Ibapi\Multiv\Controller\Index\Reserve;

/**
 * Interceptor class for @see \Ibapi\Multiv\Controller\Index\Reserve
 */
class Interceptor extends \Ibapi\Multiv\Controller\Index\Reserve implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\ResultFactory $resultPageFactory, \Magento\Catalog\Model\ProductRepository $pi, \Ibapi\Multiv\Helper\Data $helper)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $pi, $helper);
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
