<?php
namespace Bss\PromotionBar\Controller\Adminhtml\Bar\MassDeactive;

/**
 * Interceptor class for @see \Bss\PromotionBar\Controller\Adminhtml\Bar\MassDeactive
 */
class Interceptor extends \Bss\PromotionBar\Controller\Adminhtml\Bar\MassDeactive implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Ui\Component\MassAction\Filter $filter, \Bss\PromotionBar\Model\ResourceModel\Bar\CollectionFactory $collectionFactory, \Magento\Backend\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($filter, $collectionFactory, $context);
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
