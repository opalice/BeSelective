<?php
namespace Ibapi\Multiv\Controller\Index\Cart;

/**
 * Interceptor class for @see \Ibapi\Multiv\Controller\Index\Cart
 */
class Interceptor extends \Ibapi\Multiv\Controller\Index\Cart implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\ResultFactory $resultPageFactory, \Magento\Catalog\Model\ProductRepository $productRepository, \Magento\Checkout\Model\Cart $cart, \Ibapi\Multiv\Helper\Data $helper, \Magento\Framework\Data\Form\FormKey $formKey)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $productRepository, $cart, $helper, $formKey);
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
