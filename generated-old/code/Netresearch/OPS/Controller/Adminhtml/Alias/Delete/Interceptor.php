<?php
namespace Netresearch\OPS\Controller\Adminhtml\Alias\Delete;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Adminhtml\Alias\Delete
 */
class Interceptor extends \Netresearch\OPS\Controller\Adminhtml\Alias\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Netresearch\OPS\Model\AliasFactory $oPSAliasFactory)
    {
        $this->___init();
        parent::__construct($context, $oPSAliasFactory);
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
