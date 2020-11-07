<?php
namespace Netresearch\OPS\Controller\Adminhtml\Alias\GenerateHash;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Adminhtml\Alias\GenerateHash
 */
class Interceptor extends \Netresearch\OPS\Controller\Adminhtml\Alias\GenerateHash implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Netresearch\OPS\Helper\Data $oPSHelper, \Magento\Quote\Model\QuoteFactory $quoteQuoteFactory, \Netresearch\OPS\Helper\Alias $oPSAliasHelper, \Netresearch\OPS\Model\Config $oPSConfig, \Netresearch\OPS\Helper\Payment $oPSPaymentHelper, \Magento\Backend\Model\Session\Quote $backendSessionQuote, \Magento\Framework\Json\EncoderInterface $jsonEncoder)
    {
        $this->___init();
        parent::__construct($context, $oPSHelper, $quoteQuoteFactory, $oPSAliasHelper, $oPSConfig, $oPSPaymentHelper, $backendSessionQuote, $jsonEncoder);
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
