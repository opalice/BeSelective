<?php
namespace Ibapi\Regq\Controller\Index\Login;

/**
 * Interceptor class for @see \Ibapi\Regq\Controller\Index\Login
 */
class Interceptor extends \Ibapi\Regq\Controller\Index\Login implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $factory)
    {
        $this->___init();
        parent::__construct($context, $factory);
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
