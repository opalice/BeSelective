<?php
namespace Netresearch\OPS\Controller\Adminhtml\Alias\Accept;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Adminhtml\Alias\Accept
 */
class Interceptor extends \Netresearch\OPS\Controller\Adminhtml\Alias\Accept implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Netresearch\OPS\Helper\Data $oPSHelper, \Magento\Quote\Model\QuoteFactory $quoteQuoteFactory, \Netresearch\OPS\Helper\Alias $oPSAliasHelper, \Netresearch\OPS\Model\Config $oPSConfig, \Netresearch\OPS\Helper\Payment $oPSPaymentHelper, \Magento\Backend\Model\Session\Quote $backendSessionQuote)
    {
        $this->___init();
        parent::__construct($context, $oPSHelper, $quoteQuoteFactory, $oPSAliasHelper, $oPSConfig, $oPSPaymentHelper, $backendSessionQuote);
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
