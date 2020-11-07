<?php
namespace Netresearch\OPS\Model\Payment\OpenInvoice\OpenInvoiceAbstract;

/**
 * Interceptor class for @see \Netresearch\OPS\Model\Payment\OpenInvoice\OpenInvoiceAbstract
 */
class Interceptor extends \Netresearch\OPS\Model\Payment\OpenInvoice\OpenInvoiceAbstract implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Payment\Helper\Data $paymentData, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Payment\Model\Method\Logger $logger, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Sales\Model\OrderFactory $salesOrderFactory, \Magento\Framework\Stdlib\StringUtils $stringUtils, \Magento\Framework\App\Request\Http $request, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Message\ManagerInterface $messageManager, \Netresearch\OPS\Model\Backend\Operation\ParameterFactory $oPSBackendOperationParameterFactory, \Netresearch\OPS\Model\Config $oPSConfig, \Netresearch\OPS\Helper\Payment\Request $oPSPaymentRequestHelper, \Netresearch\OPS\Helper\Order $oPSOrderHelper, \Netresearch\OPS\Helper\Data $oPSHelper, \Netresearch\OPS\Helper\Payment $oPSPaymentHelper, \Netresearch\OPS\Helper\Order\Capture $oPSOrderCaptureHelper, \Netresearch\OPS\Model\Api\DirectLink $oPSApiDirectlink, \Netresearch\OPS\Helper\Directlink $oPSDirectlinkHelper, \Netresearch\OPS\Model\Status\UpdateFactory $oPSStatusUpdateFactory, \Netresearch\OPS\Helper\Order\Refund $oPSOrderRefundHelper, \Netresearch\OPS\Model\Response\Handler $oPSResponseHandler, \Netresearch\OPS\Model\Validator\Parameter\FactoryFactory $oPSValidatorParameterFactoryFactory, \Netresearch\OPS\Helper\Validation\Result $oPSValidationResultHelper, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $storeManager, $checkoutSession, $salesOrderFactory, $stringUtils, $request, $customerSession, $messageManager, $oPSBackendOperationParameterFactory, $oPSConfig, $oPSPaymentRequestHelper, $oPSOrderHelper, $oPSHelper, $oPSPaymentHelper, $oPSOrderCaptureHelper, $oPSApiDirectlink, $oPSDirectlinkHelper, $oPSStatusUpdateFactory, $oPSOrderRefundHelper, $oPSResponseHandler, $oPSValidatorParameterFactoryFactory, $oPSValidationResultHelper, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function denyPayment(\Magento\Payment\Model\InfoInterface $payment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'denyPayment');
        if (!$pluginInfo) {
            return parent::denyPayment($payment);
        } else {
            return $this->___callPlugins('denyPayment', func_get_args(), $pluginInfo);
        }
    }
}
