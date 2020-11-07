<?php
/**
 * @author    Frank Clark
 */
namespace Ibapi\MUltiv\Model;

use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class CreateSubscription
{
    
    private $productFactory;
    private $cartManagementInterface;
    private $cartRepositoryInterface;
    private $orderService;
    
    private $order;
    private $quoteManagement;
    private $quote;
    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        CartManagementInterface $cartInterface,
        CartRepositoryInterface  $cartRepo,
        \Magento\Quote\Model\QuoteFactory $quote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Sales\Model\Service\OrderService $orderService
    ) {
        $this->_storeManager = $storeManager;
        $this->cartManagementInterface=$cartInterface;
        $this->cartRepositoryInterface=$cartRepo;
        $this->orderService = $orderService;
        $this->productFactory=$productFactory;
        $this->quoteManagement=$quoteManagement;
        $this->quote=$quote;
///        $this->order=$order;
    }
    /**
     * Create Order On Your Store
     *
     * @param array $orderData
     * @return array
     *
     */
    public function createOrder($orderData) {
        //init the store id and website id @todo pass from array
       /*
        $store = $this->_storeManager->getStore();
        $websiteId = $orderData['site'];
        //init the customer
        $customer=$this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($orderData['email']);// load customet by email address
        //check the customer
        if(!$customer->getEntityId()){
            return false;
        }
        */
        //init the quote
        
////        $cart_id = $this->cartManagementInterface->createEmptyCart();
        $quote =$this->quote->create(); ////$this->cartRepositoryInterface->get($cart_id);
        echo "quote created\n";

        $quote->setStore($orderData['store']);
        
        // if you have already buyer id then you can load customer directly
        $quote->assignCustomer($orderData['customer']); //Assign quote to customer
        $quote->setCurrency();
        
        
        $this->cartRepositoryInterface->save($quote);
       
        
        
        
        
        
        //add items in quote
        $product=$this->productFactory->create()->load($orderData['product_id']);
///        $product->setPrice(22);
         $price=$orderData['price'];
         $buyInfo = array(
             'qty' => 1,
         );
         $quote->addProduct(
                $product,
               1
             )->setOriginalCustomPrice($price)
             ->setCustomPrice($price)
             ->setIsSuperMode(true)
             ->save();;
       
       
         $quote->getBillingAddress()->addData($orderData['billing_address']);
         $quote->getBillingAddress()->setCustomerAddressId($orderData['address_id']);
       
///        $quote->setBillingAddress($orderData['billing_address']);
        
        /*
        $quote->getShippingAddress()->addData($orderData['billing_address']);
        // Collect Rates and Set Shipping & Payment Method

        $shippingAddress = $quote->getShippingAddress();
        //@todo set in order data
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate'); //shipping method
        */
            
        $quote->setPaymentMethod('ops_alias'); //payment method
        //@todo insert a variable to affect the invetory
        $quote->setInventoryProcessed(false);
        // Set sales order payment
        $quote->getPayment()->importData($orderData['pay']);
/*
        $quoteSplit->getPayment()->importData(
            array(
                'method' => $paymentMethod1,
                'cc_type' => $paymentCardDetails['cc_type'],
                'cc_number' => $paymentCardDetails['cc_number'],
                'cc_exp_year' => $paymentCardDetails['cc_exp_year'],
                'cc_exp_month' => $paymentCardDetails['cc_exp_month'],
                'cc_cid' => $paymentCardDetails['cc_cid']
            )
            );
        
        */
        //$quote->save();
        $this->cartRepositoryInterface->save($quote);
        // Collect total and saeve
////        $quote->collectTotals();
        $quote->save();
        
        /**@var $quote \Magento\Quote\Model\Quote */
        $cls=get_class($quote);
        echo "quote ".$quote->getId()." $cls \n";
        
        foreach($quote->getAllItems() as $item){
            
            foreach($quote->getAllItems() as $item){
                /**@var $item \Magento\Quote\Model\Quote\Item */
                $itid=$item->getId();
                echo "price ".$item->getPrice()." id $itid \n";
                $item->setPrice($price);
                $item->setOriginalPrice($price);
                $item->setBasePrice($price);
                $item->setOrigalBasePrice($price);
                $item->setRowTotal($price);
                $item->setBaseRowTotal($price);
                
                $item->save();
                
            }
            
            
            
            
        }
        $quote->setTotalsCollectedFlag(false);
        $quote->collectTotals();
        
        
        $quote->setSubtotal($price);
        $quote->setBaseSubtotal($price);
        $quote->setSubtotalWithDiscount($price);
        $quote->setBaseSubtotalWithDiscount($price);
        
        $quote->setGrandTotal($price);
        $quote->setBaseGrandTotal($price);
        $quote->save();
        
        
        
        
        // Submit the quote and create the order
///        $quote = $this->cartRepositoryInterface->get($quote->getId());
        
        $order=$this->quoteManagement->submit($quote);
///        $orderId = $this->cartManagementInterface->placeOrder($quote->getId());
        $order->setEmailSent(0);
    
        return $order;
    }
}