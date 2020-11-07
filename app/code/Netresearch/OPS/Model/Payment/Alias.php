<?php 
namespace Netresearch\OPS\Model\Payment;

use Magento\Payment\Model\Method\ConfigInterface;
use Magento\Payment\Model\Method\TransparentInterface;

class Alias extends \Netresearch\OPS\Model\Payment\DirectLink implements TransparentInterface, ConfigInterface
{
    const CODE = 'ops_alias';
    
    
    
    
    
    
    
    /**
     * @var \Netresearch\OPS\Helper\Creditcard
     */
    protected $oPSCreditcardHelper;
    
    /**
     * @var \Netresearch\OPS\Model\Payment\Features\ZeroAmountAuthFactory
     */
    protected $oPSPaymentFeaturesZeroAmountAuthFactory;
    
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\OrderFactory $salesOrderFactory
     * @param \Magento\Framework\Stdlib\StringUtils $stringUtils
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Checkout\Model\Type\Onepage $checkoutTypeOnepage
     * @param \Netresearch\OPS\Model\Backend\Operation\ParameterFactory $oPSBackendOperationParameterFactory
     * @param \Netresearch\OPS\Model\Config $oPSConfig
     * @param \Netresearch\OPS\Helper\Payment\Request $oPSPaymentRequestHelper
     * @param \Netresearch\OPS\Helper\Order $oPSOrderHelper
     * @param \Netresearch\OPS\Helper\Data $oPSHelper
     * @param \Netresearch\OPS\Helper\Payment $oPSPaymentHelper
     * @param \Netresearch\OPS\Helper\Order\Capture $oPSOrderCaptureHelper
     * @param \Netresearch\OPS\Model\Api\DirectLink $oPSApiDirectlink
     * @param \Netresearch\OPS\Helper\Directlink $oPSDirectlinkHelper
     * @param \Netresearch\OPS\Model\Status\UpdateFactory $oPSStatusUpdateFactory
     * @param \Netresearch\OPS\Helper\Order\Refund $oPSOrderRefundHelper
     * @param \Netresearch\OPS\Model\Response\Handler $oPSResponseHandler
     * @param \Netresearch\OPS\Model\Validator\Parameter\FactoryFactory $oPSValidatorParameterFactoryFactory
     * @param \Netresearch\OPS\Helper\Validation\Result $oPSValidationResultHelper
     * @param \Netresearch\OPS\Helper\Quote $oPSQuoteHelper
     * @param \Netresearch\OPS\Helper\Alias $oPSAliasHelper
     * @param \Netresearch\OPS\Helper\Creditcard $oPSCreditcardHelper
     * @param Features\ZeroAmountAuthFactory $oPSPaymentFeaturesZeroAmountAuthFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $salesOrderFactory,
        \Magento\Framework\Stdlib\StringUtils $stringUtils,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Checkout\Model\Type\Onepage $checkoutTypeOnepage,
        \Netresearch\OPS\Model\Backend\Operation\ParameterFactory $oPSBackendOperationParameterFactory,
        \Netresearch\OPS\Model\Config $oPSConfig,
        \Netresearch\OPS\Helper\Payment\Request $oPSPaymentRequestHelper,
        \Netresearch\OPS\Helper\Order $oPSOrderHelper,
        \Netresearch\OPS\Helper\Data $oPSHelper,
        \Netresearch\OPS\Helper\Payment $oPSPaymentHelper,
        \Netresearch\OPS\Helper\Order\Capture $oPSOrderCaptureHelper,
        \Netresearch\OPS\Model\Api\DirectLink $oPSApiDirectlink,
        \Netresearch\OPS\Helper\Directlink $oPSDirectlinkHelper,
        \Netresearch\OPS\Model\Status\UpdateFactory $oPSStatusUpdateFactory,
        \Netresearch\OPS\Helper\Order\Refund $oPSOrderRefundHelper,
        \Netresearch\OPS\Model\Response\Handler $oPSResponseHandler,
        \Netresearch\OPS\Model\Validator\Parameter\FactoryFactory $oPSValidatorParameterFactoryFactory,
        \Netresearch\OPS\Helper\Validation\Result $oPSValidationResultHelper,
        \Netresearch\OPS\Helper\Quote $oPSQuoteHelper,
        \Netresearch\OPS\Helper\Alias $oPSAliasHelper,
        \Netresearch\OPS\Helper\Creditcard $oPSCreditcardHelper,
        \Netresearch\OPS\Model\Payment\Features\ZeroAmountAuthFactory $oPSPaymentFeaturesZeroAmountAuthFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
        ) {
            parent::__construct(
                $context,
                $registry,
                $extensionFactory,
                $customAttributeFactory,
                $paymentData,
                $scopeConfig,
                $logger,
                $storeManager,
                $checkoutSession,
                $salesOrderFactory,
                $stringUtils,
                $request,
                $customerSession,
                $messageManager,
                $checkoutTypeOnepage,
                $oPSBackendOperationParameterFactory,
                $oPSConfig,
                $oPSPaymentRequestHelper,
                $oPSOrderHelper,
                $oPSHelper,
                $oPSPaymentHelper,
                $oPSOrderCaptureHelper,
                $oPSApiDirectlink,
                $oPSDirectlinkHelper,
                $oPSStatusUpdateFactory,
                $oPSOrderRefundHelper,
                $oPSResponseHandler,
                $oPSValidatorParameterFactoryFactory,
                $oPSValidationResultHelper,
                $oPSQuoteHelper,
                $resource,
                $resourceCollection,
                $data
                );
            $this->oPSAliasHelper = $oPSAliasHelper;
            $this->oPSCreditcardHelper = $oPSCreditcardHelper;
    }
    
    
    
    public function canCapture(){
        return true;
    }
    
    public function getConfigPaymentAction()
    {
        return  \Magento\Payment\Model\Method\AbstractMethod::ACTION_ORDER;
    }
    
    /**
     * {@inheritdoc}
     */
    public function assignData(\Magento\Framework\DataObject $data)
    {
        parent::assignData($data);
        $additionalData = $data->getAdditionalData();
        $ccBrand = isset($additionalData['CC_BRAND']) ? $additionalData['CC_BRAND'] : null;
        $alias = isset($additionalData['alias']) ? $additionalData['alias'] : null;
        $cvc = isset($additionalData['cvc']) ? $additionalData['cvc'] : null;
        $infoInstance = $this->getInfoInstance();
        
        if ($ccBrand) {
            $infoInstance->setAdditionalInformation('CC_BRAND', $ccBrand);
        }
        if ($alias) {
            $infoInstance->setAdditionalInformation('alias', $alias);
        }
        if ($cvc) {
            $infoInstance->setAdditionalInformation('cvc', $cvc);
        }
        return $this;
    }
    /** ops payment code */
    public function getOpsCode($payment = null)
    {
        $opsBrand = $this->getOpsBrand($payment);
        if ('PostFinance card' == $opsBrand) {
            return 'PostFinance Card';
        }
        if ('UNEUROCOM' == $this->getOpsBrand($payment)) {
            return 'UNEUROCOM';
        }
        
        return 'CreditCard';
    }
    
    /**
     * @param null $payment
     *
     * @return array|mixed|null
     */
    public function getOpsBrand($payment = null)
    {
        
        return $payment->getAdditionalInformation('CC_BRAND');
    }
    
    
    /**
     * only some brands are supported to be integrated into onepage checkout
     *
     * @return array
     */
    public function getBrandsForAliasInterface()
    {
        return $this->oPSConfig->getInlinePaymentCcTypes('ops_cc');
    }
    
    /**
     * if cc brand supports ops alias interface
     *
     * @param $payment
     *
     * @return bool
     */
    public function hasBrandAliasInterfaceSupport($payment = null)
    {
        return in_array(
            $this->getOpsBrand($payment),
            $this->getBrandsForAliasInterface()
            );
    }


    /**
     * {@inheritDoc}
     * @see \Netresearch\OPS\Model\Payment\PaymentAbstract::getPaymentAction()
     */
    public function getPaymentAction()
    {
        return  \Magento\Payment\Model\Method\AbstractMethod::ACTION_ORDER;
    }

    /**
     * {@inheritDoc}
     * @see \Netresearch\OPS\Model\Payment\DirectLink::getRequestParamsHelper()
     */
    protected function getRequestParamsHelper()
    {
        
        if (null === $this->requestParamsHelper) {
            $this->requestParamsHelper = $this->oPSCreditcardHelper;
        }
        
        return $this->requestParamsHelper;
        
    
        
    }

    /**
     * {@inheritDoc}
     * @see \Netresearch\OPS\Model\Payment\DirectLink::handleAdminPayment()
     */
    protected function handleAdminPayment(\Magento\Quote\Model\Quote $quote)
    {
        // TODO Auto-generated method stub
        
    }
    
    protected function performPreDirectLinkCallActions(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Framework\DataObject $payment,
        $requestParams = []
        ) {
        $requestParams['OPERATION']='SAL';
        $requestParams['ECI']='9';
            
///            $requestParams['orderID']=$quote->getOrigOrderId();
            
        /*
            $this->oPSAliasHelper->cleanUpAdditionalInformation($payment, true);
            if (true === $this->oPSConfig->isAliasManagerEnabled($this->getCode())) {
                $this->validateAlias($quote, $payment);
            }
          */  
            return $this;
    }
    
    /**
     * {@inheritDoc}
     * @see \Magento\Payment\Model\Method\AbstractMethod::order()
     */
    public function order(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $order = $payment->getOrder();
        $state = \Magento\Sales\Model\Order::STATE_PROCESSING;
        $status = true;
        
        
        $order->setState($state)
        ->setStatus($status);
        
    }

    protected function performPostDirectLinkCallAction(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Sales\Model\Order $order
        ) {
            $this->oPSAliasHelper->setAliasActive($quote, $order);
            return $this;
    }


    

    /**
     * {@inheritDoc}
     * @see \Magento\Payment\Gateway\ConfigInterface::getValue()
     */
    public function getValue($field, $storeId = null)
    {
        return $this->getConfigData($field, $storeId);
    }

    /**
     * {@inheritDoc}
     * @see \Magento\Payment\Gateway\ConfigInterface::setMethodCode()
     */
    public function setMethodCode($methodCode)
    {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \Magento\Payment\Gateway\ConfigInterface::setPathPattern()
     */
    public function setPathPattern($pathPattern)
    {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \Magento\Payment\Model\Method\TransparentInterface::getConfigInterface()
     */
    public function getConfigInterface()
    {
        return $this;
       
    }
    
    
    public function isInitializeNeeded()
    {
     return false;
        
    }
    
    private function _getInitData($order, $requestParams = null){
        
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();
        if (null === $shippingAddress || false === $shippingAddress) {
            $shippingAddress = $billingAddress;
        }
        
        $payment = $order->getPayment()->getMethodInstance();
        $quote = $this->oPSOrderHelper->getQuote($order->getQuoteId());
        
        $formFields = [];
        $formFields['ECI']=9;
        $formFields['orderID']=$order->getRealOrderId();
        $formFields['ORIG'] = $this->oPSHelper->getModuleVersionString();
        $formFields['BRAND'] = $payment->getOpsBrand($order->getPayment());
        $formFields['CN'] = $billingAddress->getFirstname().' '.$billingAddress->getLastname();
        $formFields['COM'] = $this->_getOrderDescription($order);
        /*
        if ($this->getConfig()->canSubmitExtraParameter($order->getStoreId())) {
            $formFields['CN'] = $billingAddress->getFirstname().' '.$billingAddress->getLastname();
            $formFields['COM'] = $this->_getOrderDescription($order);
            $formFields['ADDMATCH'] = $this->oPSOrderHelper->checkIfAddressesAreSame($order);
            $ownerParams = $this->getRequestHelper()->getOwnerParams($billingAddress, $quote);
            $formFields['ECOM_BILLTO_POSTAL_POSTALCODE'] = $billingAddress->getPostcode();
            $formFields = array_merge($formFields, $ownerParams);
        }
        */
///            $formFields['CUID'] = $this->customerSession->getCustomerId();
        
        return $formFields;
    }
    
    public function getEncoding(){
        return 'UTF-8';
        
    }
    
    /**
     * Saves the payment model and runs the request to Ingenico ePayments webservice
     *
     * @param \Magento\Sales\Model\Order $order
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @throws \Exception
     */
    protected function confirmPayment(
        \Magento\Sales\Model\Order $order,
        \Magento\Quote\Model\Quote $quote,
        \Magento\Framework\DataObject $payment
        ) {
            $this->handleAdminPayment($quote);
            $requestParams = $this->getRequestParamsHelper()->getDirectLinkRequestParams($quote, $order, $payment);
            $requestParams['ECI']=9;
            $this->invokeRequestParamValidation($requestParams);
            $this->performPreDirectLinkCallActions($quote, $payment);
            
            //     $reqp=print_r($requestParams,1);
            //       file_put_contents('directlink.txt', "\nparams ".$reqp."\n",FILE_APPEND);
            $response = $this->getDirectLinkHelper()->performDirectLinkRequest(
                $quote,
                $requestParams,
                $quote->getStoreId()
                );
            if ($response) {
                $this->oPSResponseHandler->processResponse($response, $this, false);
                $this->performPostDirectLinkCallAction($quote, $order);
            } else {
                $this->getPaymentHelper()->handleUnknownStatus($order);
            }
    }
    
    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount){
        $order = $payment->getOrder();
        $quote = $this->getQuoteHelper()->getQuote();
        $this->confirmPayment($order, $quote, $payment);
        
    }
    
    /**
     * performs direct link request either for inline paymentsand direct sale mode
     * or the normal maintenance call (invoice)
     *
     * {@override}
     *
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     *
     * @return \Netresearch\OPS\Model\Payment\DirectLink
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        echo "capturing  $amount\n";
       
        $this->authorize($payment,$amount);
        
        echo "authorized";
        $orderId = $payment->getOrder()->getRealOrderId();
        $arrInfo = $this->oPSOrderCaptureHelper->prepareOperation($payment, $amount);
        $storeId = $payment->getOrder()->getStoreId();
        
        echo "arrinfo\n";
        print_r($arrInfo);
        
        
        if ($this->isRedirectNoticed($orderId)) {
            return $this;
        }
        try {
            $requestParams = $this->getBackendOperationParameterModel()->getParameterFor(
                self::OPS_CAPTURE_TRANSACTION_TYPE,
                $this,
                $payment,
                $amount,
                $arrInfo
                );
            echo "requestparam\n";
           $requestParams['orderID']=$orderId;
           $requestParams['ECI']=9;
            print_r($requestParams);
            
           
            
            
///            $requestParams = $this->transliterateParams($requestParams);
            $response = $this->oPSApiDirectlink->performRequest(
                $requestParams,
                $this->oPSConfig->getDirectLinkGatewayPath($storeId),
                $storeId
                );
            echo "response\n";
            
            print_r($response);
            $this->oPSResponseHandler->processResponse($response, $this, false);
            
            return $this;
        } catch (\Exception $e) {
            $this->oPSStatusUpdateFactory->create()->updateStatusFor($payment->getOrder());
            $this->oPSHelper->log("Exception in capture request:".$e->getMessage());
            throw $e;
        }
            
    }
    public function getTitle(){
        return 'Ops Alias';
    }
    
    public function getMethodDependendFormFields($order, $requestParams = null)
    {
        $formFields = $this->_getInitData($order,$requestParams);
     
        
        
        
        
        
        
        
        
        
        $alias = $order->getPayment()->getAdditionalInformation('alias') ?: '';
        
        $formFields['ALIAS'] = $alias;
        
            if ($alias) {
                $formFields['ALIASOPERATION'] = "BYPSP";
                $formFields['ECI'] = 9;
                $formFields['ALIASUSAGE'] = $this->getConfig()->getAliasUsageForExistingAlias(
                    'ops_cc',
                    $order->getStoreId()
                    );
            } else {
                $formFields['ALIASOPERATION'] = "BYPSP";
                $formFields['ALIASUSAGE'] = $this->getConfig()->getAliasUsageForNewAlias(
                    'ops_cc',
                    $order->getStoreId()
                    );
            }
        
        
        return $formFields;
    }
    /**
     * {@inheritDoc}
     * @see \Netresearch\OPS\Model\Payment\PaymentAbstract::isAvailable()
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {

        
        if ($this->_appState->getAreaCode() ===\Magento\Framework\App\Area::AREA_ADMINHTML){
            return true;
        }
        
        return false;
            
    
    
    }

    /**
     * {@inheritDoc}
     * @see \Magento\Payment\Model\Method\AbstractMethod::canCapturePartial()
     */
    public function canCapturePartial()
    {
        return  true;
    }


    
    
}