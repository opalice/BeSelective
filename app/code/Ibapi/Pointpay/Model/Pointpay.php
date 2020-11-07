<?php
namespace  Ibapi\Pointpay\Model;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Customer\Model\CustomerFactory;
use Magento\Quote\Api\Data\CartInterface;

use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentMethodInterface;
use Magento\Sales\Model\Order\Payment;
use Magento\Quote\Model\Quote;


class Pointpay extends  AbstractMethod{

private $customer;
private $customerFactory;    
/**
     * 
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param CustomerFactory $customerFactory
     * @param array $data
     */
   /*
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        CustomerFactory $customerFactory,
        array $data = []
        ) {
            
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger,$resource,$data);
        $this->customerFactory=$customerFactory;
    }
    */
    protected $_code='multiv_pointpay';
    
    /**
     * Bank Transfer payment block paths
     *
     * @var string
     */
    protected $_formBlockType = \Magento\OfflinePayments\Block\Form\Banktransfer::class;
    
    /**
     * Instructions block path
     *
     * @var string
     */
//    protected $_infoBlockType = \Magento\Payment\Block\Info\Instructions::class;
    
    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;
    
    
    public  function  validate(){
    
        /**
         * to validate payment method is allowed for billing country or not
         */
        $paymentInfo = $this->getInfoInstance();
        if ($paymentInfo instanceof Payment) {
            
          $order=  $paymentInfo->getOrder();
          if(!$order||$paymentInfo->getOrder()->getCustomerIsGuest()){
      
              throw new \Magento\Framework\Exception\LocalizedException(
                  __('Cannot be used as guest.')
                  );
              
          }
          $cid=$order->getCustomerId();
        $amt=$order->getGrandTotal();
       $order=$order->getCustomer();
        
        } else {
        
            $quote=$paymentInfo->getQuote();
            /** @var $quote  Quote */
         
           
            if(!$quote||$quote->getCustomerIsGuest()){

                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Cannot be used as guest2.')
                    );
                
            }
            $amt=$quote->getGrandTotal();
            $cid=$quote->getCustomerId();
            $customer=$quote->getCustomer();

        }
        if (!$cid) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('You can\'t use the payment type you selected .')
                );
        }
//        $customer=$this->customerFactory->create()->load($cid);
        $pp=$customer->getData('pointpay');
        if($pp<$amt){
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Insufficient balance .')
                );
            
        }
        $this->customer=$customer;
        
        return $this;
        
    
    }
    
 
    
    public function isInitializeNeeded(){
        return false;
    }
    public function  getConfigPaymentAction(){
        
        return self::ACTION_ORDER;
    }
    public function  isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null){
        
        if($quote==null||$quote->getCustomerIsGuest()){
            return false;
        }
        
        return parent::isAvailable($quote);
        
    }
    
    public function  order( \Magento\Payment\Model\InfoInterface $payment, $amount){
        
        if(!$this->customer){
            throw new LocalizedException(__('Invalid entry'));
        }
        
        
        $customer->setData('pointpay',$pp-$amount)->save();
    
    
        $payment->setSkipOrderProcessing(false);
    }
    
}