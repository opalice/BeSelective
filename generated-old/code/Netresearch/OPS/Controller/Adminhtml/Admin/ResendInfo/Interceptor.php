<?php
namespace Netresearch\OPS\Controller\Adminhtml\Admin\ResendInfo;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Adminhtml\Admin\ResendInfo
 */
class Interceptor extends \Netresearch\OPS\Controller\Adminhtml\Admin\ResendInfo implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Sales\Model\OrderFactory $salesOrderFactory, \Netresearch\OPS\Model\Payment\Features\PaymentEmailFactory $oPSPaymentFeaturesPaymentEmailFactory)
    {
        $this->___init();
        parent::__construct($context, $salesOrderFactory, $oPSPaymentFeaturesPaymentEmailFactory);
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
