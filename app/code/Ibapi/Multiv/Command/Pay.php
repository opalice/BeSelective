<?php 
namespace Ibapi\Multiv\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Customer\Model\Customer;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ScopeInterface;
use Magento\Framework\Cache\ConfigInterface;
use Ibapi\Multiv\Model\Checkout\ReportOrderPlaced;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Magento\Quote\Model\QuoteRepository;
use Magento\Sales\Model\Order\InvoiceRepository;
class Pay extends Command
{
    var $attrcl;
    var $rtfact;
    var $scopeconfig;
    var $helper;
    var $eavConfig;
    var $orderFactory;
    var $subFactory;
    var $orderRepo;
    var $customerRepo;
    var $adrepo;
    var $state;
    var $invoiceService;
    var $resFac;
    var $invoiceRepo;
    var $sender;
    /*
     * @var \Magento\Quote\Model\QuoteRepository\QuoteRepository 
     */
    var $quoteRepo;
/**
 * @param \Magento\Framework\App\State $state
 * @param \Ibapi\Multiv\Model\RtableFactory $rtfact
 * @param \Ibapi\Multiv\Helper\Data $helper
 * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderFactory
 * @param OrderRepositoryInterface $orderRepo
 * @param \Ibapi\Multiv\Model\SubscriptionFactory $subFactory
 * @param QuoteRepository $quoteRepo
 * @param \Magento\Sales\Model\Service\InvoiceService $invoiceService
 * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
 * @param \Ibapi\Multiv\Model\ReserveFactory $rfact
 */
    
    public  function  __construct(
        \Magento\Framework\App\State $state ,
        \Ibapi\Multiv\Model\RtableFactory $rtfact,\Ibapi\Multiv\Helper\Data $helper,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderFactory,
        OrderRepositoryInterface $orderRepo,
        \Ibapi\Multiv\Model\SubscriptionFactory $subFactory,
        QuoteRepository $quoteRepo,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, // Add this,
        \Ibapi\Multiv\Model\ReserveFactory $rfact,
        InvoiceRepository $invRepo,
        Order\Email\Sender\InvoiceSender $sender
){

        $this->invoiceRepo=$invRepo;
        $this->state=$state;

        ///$this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL); //SET CURRENT AREA
        parent::__construct('pay');
        $this->invoiceService=$invoiceService;
        $this->rtfact=$rtfact;
        $this->helper=$helper;
        $this->orderFactory=$orderFactory;
        $this->orderRepo=$orderRepo;
        $this->subFactory=$subFactory;
        $this->customerRepo=$customerRepository;
        $this->resFac=$rfact;
//        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_CRONTAB); // or \Magento\Framework\App\Area::AREA_ADMINHTML, depending on your needs
        $this->quoteRepo=$quoteRepo;
        $this->sender=$sender;
    }
    
    /*
     * 
     CREATE TABLE m21_1.multiv_sub (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	cid INT UNSIGNED NOT NULL,
	oid INT UNSIGNED NOT NULL,
	itid INT UNSIGNED NOT NULL,
    pid INT UNSIGNED NOT NULL,
	price DOUBLE NOT NULL,
	nextd DATE NOT NULL,
	status SMALLINT DEFAULT 0 NOT NULL
)
ENGINE=InnoDB
     * 
     */
    
    
    protected function configure()
    {

        $options = [
            new InputOption(
                'test',
                null,
                InputOption::VALUE_OPTIONAL,
                'Test --test=1'
            )
        ];

        $this->setName("pay");
        $this->setDescription("order cron.")->setDefinition($options);
///        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_CRONTAB); // or \Magento\Framework\App\Area::AREA_ADMINHTML, depending on your needs
        
        parent::configure();
    }
    
    protected  function _log($msg){
        if(is_array($msg)){
            $msg=print_r($msg,1);
        }
        try {
            echo " $msg\n";
            file_put_contents('invoicecron.txt', $msg, FILE_APPEND);
        }catch(\Exception $e){
            echo $e->getMessage();
            echo "###eeee\n";
        }
    }
    
    protected function getPaymentSpecificParams(\Magento\Sales\Model\Order $order)
    {
        /**@var $cc \Netresearch\OPS\Helper\Creditcard */
        $methodCode = $order->getPayment()->getMethod();
        if($methodCode!='ops_cc'){
            return  false;
        }
        
        $alias = $order->getPayment()->getAdditionalInformation('alias');
        
        
        $params =  [
            'method_title'=>'Ops Alias',
             'method'=>'ops_alias',
       ////     'cvc'=>'333',
            'CC_BRAND'=>$order->getPayment()->getAdditionalInformation('CC_BRAND'),
            'BRAND'=>$order->getPayment()->getAdditionalInformation('CC_BRAND'),
            'alias' => $alias,
        ];
        
        
        if (is_numeric($order->getPayment()->getAdditionalInformation('cvc'))) {
            $params['cvc'] = $order->getPayment()->getAdditionalInformation('cvc');
        }
///        print_r($params);
      
        return $params;        
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

ob_start();

      ///   $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL); //SET CURRENT AREA

        $rt = $this->rtfact->create();
        /*
                $atid=87//88/89 images;

                $attrs=$rt->getResource()->getConnection()->fetchAll("select * from catalog_product_entity_varchar where attribute_id=$atid order by entity_id,store_id",null,\PDO::FETCH_OBJ);

                $rows=[];
                $st=-1;
                $n=0;
                $nors=[];
                $rrs=[];
                $table='catalog_product_entity_varchars';
                foreach($attrs as $at){
                    if($at->entity_id!=$st&&$st>0){
                        if(!isset($rows[10])||!strstr($rows[10]->value,'.jpg')){
                            foreach([0,7,8] as $r){
                                if(!isset($rows[$r])){
                        //            echo "no r $r $st \n";
                                    $nors[]=$st;
                                    continue;
                                }
                                if(strstr($rows[$r]->value,'.jpg')){
                                $n++;
                                 echo "added image ".$rows[$r]->value." to ".$rows[$r]->entity_id." \n";
                                 $rrs[]=$rows[$r]->entity_id;
                                 $img=$rows[$r]->value;
                                 if(!isset($rows[10])){
                                     $rt->getResource()->getConnection()->insert($table, [  'attribute_id'=>$atid,  'store_id'=>10,,entity_id=>$rows[$r]->entity_id, 'value'=>$img,  ]                      );
                                     break;
                                 }else{
                                     echo "replaced ".$rows[$r]->entity_id."\n";
                                     $rt->getResource()->getConnection()->update($table, ['value'=>$img],"value_id=".$rows[10]->value_id);


                             break;
                            }
                                }

                        }
                        }else{
                            echo "has image ".$rows[10]->value." > ".$rows[10]->entity_id." \n";
                        }
                        $rows=[];
                        $st=$at->entity_id;



                    }
                    if($st<0){
                        $st=$at->entity_id;
                    }
                    $rows[$at->store_id]=$at;
                ///    echo "set ".$at->store_id." img ".$at->value."\n";
                }

                    $ct=count($attrs);

                echo "\nmade $n imgage attrs $ct \n";
                return;
                            */

        $test=$input->getOption('test');

        $orders =$test? [] : $this->orderFactory->create()->addFieldToSelect(
            '*'
        )->addFieldToFilter('state', [
                Order::STATE_PROCESSING, Order::STATE_COMPLETE

            ]

        )->setOrder(
            'created_at',
            'desc'
        )->setPageSize(100)->getItems();

        $sub = $this->subFactory->create();

        try {
            foreach ($orders as $order) {



                /**@var $order \Magento\Sales\Model\Order */


                /**@var $invoice \Magento\Sales\Model\Order\Invoice */
                $this->_log("order: processing " . $order->getId() . "\n");
                /*

                $invoice=$this->invoiceService->prepareInvoice($order);
                $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);

                $invoice->register()->save();



                echo "done";
                return;
               */

                $order = $this->orderRepo->get($order->getId());
                /**@var $order \Magento\Sales\Model\Order */
                $items = $order->getItems();

                $p = $this->getPaymentSpecificParams($order);

                if (!$p) {
                    $this->_log("order: processing " . $order->getId() . " nopayment \n");
                    continue;
                }


                /**@var $it \Magento\Sales\Model */
                foreach ($items as $it) {
                    /**@var $ad \Magento\Quote\Model\Quote\Address */
                    /**@var $it \Magento\Sales\Api\Data\OrderItemInterface */

                    if ($it->getProductType() == 'subscription') {

                        if ($it->getQtyInvoiced() < 0.1) {
                            $this->_log("order: processing " . $order->getId() . " notinvoiced\n");
                            continue;
                        }

                        /**@var $sub \Ibapi\Multiv\Model\Subscription */

                        $subitm = $sub->getCollection()->addFieldToFilter('oid', $order->getId())->getFirstItem();
                        if (!$subitm || !$subitm->getId()) {
                            $nd = date('Y-m-d', strtotime("+1 month", strtotime($order->getCreatedAt())));

                            $sub->getResource()->getConnection()->insert($sub->getResource()->getMainTable(), [

                                    'cid' => $order->getCustomerId(),
                                    'oid' => $order->getId(),
                                    'itid' => $it->getItemId(),
                                    'pid' => $it->getProductId(),
                                    'price' => $it->getBaseRowInvoiced(),
                                    'status' => 1,
                                    'nextd' => $nd

                                ]

                            );
                            $this->_log("order: processing " . $order->getId() . " next: $nd \n");
                        }


                        ///   echo   $it->getRowInvoiced().  "  ". $it->getProductType()."  saved ".$sub->getId()."\n";
                        break;

                    }


                }

            }///orders
        } catch (\Exception $e) {
            print $e->getTraceAsString();
            print $e->getMessage();
        }
        echo "create subs\n";
        $subids = $test? [$test]:$this->subFactory->create()->getCollection()->addFieldToFilter('status', ['eq' => 1])->addFieldToFilter('nextd', ['lteq' => date('Y-m-d')])->getAllIds();


        try {
            foreach ($subids as $subid) {

                $sub = $this->subFactory->create()->load($subid);
                $orderid = $sub->getOid();

                $price = $sub->getPrice();


                $pid = $sub->getPid();
                $nd = $sub->getNextd();
                $this->_log("subsribe: processing: " . $sub->getOid() . "  ORDER# " . $orderid . "\n");
                try {
                    $order = $this->orderRepo->get($orderid);
                } catch (\Exception $e) {
                 //   echo "cannot find order "
                    $order = false;
                }

                if (!$order || !$order->getId()) {
                    $this->_log("subsribe: " . $sub->getOid() . "  ORDER# " . $orderid . " NOORDER \n");
                    continue;
                }


                $data['billing_address'] = [
                    'firstname' => $order->getBillingAddress()->getFirstname(),
                    'lastname' => $order->getBillingAddress()->getLastname(),
                    'street' => $order->getBillingAddress()->getStreet(),
                    'city' => $order->getBillingAddress()->getCity(),
                    'country_id' => $order->getBillingAddress()->getCountryId(),
                    'region' => $order->getBillingAddress()->getRegion(),
                    'postcode' => $order->getBillingAddress()->getPostcode(),
                    'telephone' => $order->getBillingAddress()->getTelephone(),
                    'fax' => $order->getBillingAddress()->getFax(),
                    'save_in_address_book' => 0


                ];
                $data['address_id'] = $order->getBillingAddressId();

                $data['customer'] = $customer = $this->customerRepo->getById($order->getCustomerId());

                $data['store'] = $order->getStore();
                $data['product_id'] = $pid;
                $data['price'] = $price;

                $p = $this->getPaymentSpecificParams($order);
                $data['pay'] = $p;

                echo "create order\n";
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $neworder = $objectManager->get(\Ibapi\Multiv\Model\CreateSubscription::class)->createOrder($data);
                /**@var $neworder \Magento\Sales\Model\Order */

                echo "created order\n";
                $neworder->setBaseSubtotal($price);
                $neworder->setSubtotal($price);
                $neworder->setSubtotalInclTax($price);
                $neworder->setBaseSubtotalInclTax($price);
                $neworder->setBaseGrandTotal($price);
                $neworder->setGrandTotal($price);
                $neworder->setActionFlag(\Magento\Sales\Model\Order::ACTION_FLAG_UNHOLD, true);

                $neworder->setState(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
                $neworder->setStatus('pending_payment');
                if ($neworder->canUnhold()) {
                    $neworder->unhold();
                }
                $neworder->setState(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
                $neworder->setStatus('pending_payment');

                echo "saving order\n";
                $this->orderRepo->save($neworder);
                $this->_log("subsribe: willpay:" . $sub->getOid() . "  NEWORDER# " . $neworder->getId() . "\n");


                $invoice = $this->invoiceService->prepareInvoice($neworder);
                $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
                try {
                    $invoice->setState(Invoice::STATE_OPEN);
                    $invoice->register();
                    ///    $invoice->setState(Invoice::STATE_PAID);

                    $ndd = strtotime("+ 1 month $nd");
                    $ndt = date('Y-m-d', $ndd);


                    $invoice->addComment('vip membership extended. Next payment due on %1', $ndt, true, true);
                    $this->invoiceRepo->save($invoice);

                   $this->invoiceService->notify($invoice->getId());
                    //$this->sender->send($invoice ,true);
                    $this->_log("invoice:".$invoice->getId());


                    $this->_log("INVOICE: " . $sub->getOid() . "  NEWORDER# " . $neworder->getId() . " INV " . $invoice->getId() . "\n");
                    ///$this->invoiceService->notify($invoice->getId());
                    $customer->setGroupId(5);
                    $this->customerRepo->save($customer);

                    $sub->setData('nextd', $ndt);
                    $sub->setData('status', 1);
                    $sub->save();
                    $this->_log("subscribe: " . $sub->getId() . " saved to $ndd\n");


                } catch (\Exception $e) {
                    print $e->getTraceAsString();
                    echo "#####\n";
                    print $e->getMessage();
                    echo "####\n";
                    $customer->setGroupId(1);
                    $st = $sub->getData('status');
                    $sub->setData('status', $st + 1);
                    $sub->save();
                    $this->customerRepo->save($customer);

                    $this->_log("customer: " . $customer->getId() . " demoted \n");
                }


            }
        }
    catch(\Exception $e)
    {
   print $e->getTraceAsString();
   print $e->getMessage();
}
                        
                        
                        echo "####\n";
                        
                        /**@var $quote \Magento\Quote\Model\Quote */
                        
                        
                        
                        
                    
        

                         
        
        
        
    }
    
}
    