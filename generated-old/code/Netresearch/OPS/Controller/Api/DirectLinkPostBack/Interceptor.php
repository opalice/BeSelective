<?php
namespace Netresearch\OPS\Controller\Api\DirectLinkPostBack;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Api\DirectLinkPostBack
 */
class Interceptor extends \Netresearch\OPS\Controller\Api\DirectLinkPostBack implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Netresearch\OPS\Model\ConfigFactory $oPSConfigFactory, \Netresearch\OPS\Helper\Order $oPSOrderHelper, \Netresearch\OPS\Helper\Payment $oPSPaymentHelper, \Netresearch\OPS\Helper\Directlink $oPSDirectlinkHelper, \Netresearch\OPS\Helper\Data $oPSHelper, \Magento\Store\Model\StoreManagerInterface $storeManager, \Netresearch\OPS\Helper\Api $oPSApiHelper, \Psr\Log\LoggerInterface $opsIncomingLogger)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $oPSConfigFactory, $oPSOrderHelper, $oPSPaymentHelper, $oPSDirectlinkHelper, $oPSHelper, $storeManager, $oPSApiHelper, $opsIncomingLogger);
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
