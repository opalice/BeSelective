<?php
namespace Netresearch\OPS\Controller\Customer\Aliases;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Customer\Aliases
 */
class Interceptor extends \Netresearch\OPS\Controller\Customer\Aliases implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Netresearch\OPS\Model\AliasFactory $oPSAliasFactory, \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $oPSAliasFactory, $pageFactory);
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
