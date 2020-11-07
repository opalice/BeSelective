<?php
namespace Netresearch\OPS\Controller\Alias\GenerateHash;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Alias\GenerateHash
 */
class Interceptor extends \Netresearch\OPS\Controller\Alias\GenerateHash implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Netresearch\OPS\Model\ConfigFactory $oPSConfigFactory, \Netresearch\OPS\Helper\Order $oPSOrderHelper, \Netresearch\OPS\Helper\Payment $oPSPaymentHelper, \Netresearch\OPS\Helper\Directlink $oPSDirectlinkHelper, \Netresearch\OPS\Helper\Data $oPSHelper, \Magento\Quote\Model\QuoteFactory $quoteQuoteFactory, \Netresearch\OPS\Helper\Alias $oPSAliasHelper, \Magento\Framework\Json\EncoderInterface $jsonEncoder)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $oPSConfigFactory, $oPSOrderHelper, $oPSPaymentHelper, $oPSDirectlinkHelper, $oPSHelper, $quoteQuoteFactory, $oPSAliasHelper, $jsonEncoder);
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
