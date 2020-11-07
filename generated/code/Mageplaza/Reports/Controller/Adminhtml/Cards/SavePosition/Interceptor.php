<?php
namespace Mageplaza\Reports\Controller\Adminhtml\Cards\SavePosition;

/**
 * Interceptor class for @see \Mageplaza\Reports\Controller\Adminhtml\Cards\SavePosition
 */
class Interceptor extends \Mageplaza\Reports\Controller\Adminhtml\Cards\SavePosition implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Backend\Model\Auth\Session $authSession, \Mageplaza\Reports\Model\CardsManageFactory $cardsManageFactory)
    {
        $this->___init();
        parent::__construct($context, $authSession, $cardsManageFactory);
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
