<?php
namespace Netresearch\OPS\Controller\Payment\Placeform3dsecure;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Payment\Placeform3dsecure
 */
class Interceptor extends \Netresearch\OPS\Controller\Payment\Placeform3dsecure implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Customer\Model\Session $customerSession, \Magento\Sales\Model\OrderFactory $salesOrderFactory, \Magento\Quote\Model\QuoteFactory $quoteQuoteFactory, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Framework\Session\Generic $frameworkGeneric, \Magento\Framework\View\Result\PageFactory $pageFactory, \Netresearch\OPS\Model\ConfigFactory $oPSConfigFactory, \Netresearch\OPS\Helper\Order $oPSOrderHelper, \Netresearch\OPS\Helper\Payment $oPSPaymentHelper, \Netresearch\OPS\Helper\Directlink $oPSDirectlinkHelper, \Netresearch\OPS\Helper\Data $oPSHelper, \Netresearch\OPS\Helper\Alias $oPSAliasHelper, \Netresearch\OPS\Helper\DirectDebit $oPSDirectDebitHelper, \Netresearch\OPS\Helper\Payment\Request $oPSPaymentRequestHelper, \Netresearch\OPS\Model\Validator\Parameter\FactoryFactory $oPSValidatorParameterFactoryFactory, \Netresearch\OPS\Helper\Validation\Result $oPSValidationResultHelper)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $customerSession, $salesOrderFactory, $quoteQuoteFactory, $quoteRepository, $frameworkGeneric, $pageFactory, $oPSConfigFactory, $oPSOrderHelper, $oPSPaymentHelper, $oPSDirectlinkHelper, $oPSHelper, $oPSAliasHelper, $oPSDirectDebitHelper, $oPSPaymentRequestHelper, $oPSValidatorParameterFactoryFactory, $oPSValidationResultHelper);
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
