<?php 
namespace Ibapi\Multiv\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Bundle\Model\Plugin\QuoteItem;
use Netresearch\OPS\Plugin\QuoteItemRepositoryPlugin;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Magento\Sales\Model\Order\Email\Sender\OrderCommentSender;
use Magento\Store\Model\StoreFactory;
class Quote extends Command
{
    var $attrcl;
    var $rtfact;
    var $scopeconfig;
    var $helper;
    var $registry;
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
    /*
     * @var \Magento\Quote\Model\QuoteRepository\QuoteRepository 
     */
    var $quoteRepo;
    var $pi;
    var $searchCriteria;
    var $filterBuilder;
    var $filterGroup;
    var $ordersender;
    var $onlineF;
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
        ProductRepositoryInterface $pi,
        QuoteRepository $quoteRepo,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, // Add this,
        \Ibapi\Multiv\Model\ReserveFactory $rfact,
        \Magento\Framework\Api\SearchCriteriaInterface $criteria,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroup,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        InvoiceRepository $invRepo,
        OrderCommentSender $sender
,
        \Magento\Customer\Model\ResourceModel\Online\Grid\CollectionFactory $onlineCustomerCollectionFactory
){
        $this->invoiceRepo=$invRepo;
        $this->state=$state;
        $registry->register('isSecureArea', true);
        parent::__construct('quote');
        $this->invoiceService=$invoiceService;
        $this->rtfact=$rtfact;
        $this->helper=$helper;
        $this->registry=$registry;
        $this->orderFactory=$orderFactory;
        $this->orderRepo=$orderRepo;
        $this->helper=$helper;
        
        $this->subFactory=$subFactory;
        $this->customerRepo=$customerRepository;
        $this->resFac=$rfact;
        $this->pi=$pi;
        $this->searchCriteria=$criteria;
        $this->filterBuilder=$filterBuilder;
        $this->filterGroup=$filterGroup;
        $this->ordersender=$sender;
//        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_CRONTAB); // or \Magento\Framework\App\Area::AREA_ADMINHTML, depending on your needs
        $this->quoteRepo=$quoteRepo;
        $this->onlineF=$onlineCustomerCollectionFactory;
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
        $this->setName("quote");
        $this->setDescription("quote removal cron.");
///        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_CRONTAB); // or \Magento\Framework\App\Area::AREA_ADMINHTML, depending on your needs
        
        parent::configure();
    }
    
    protected  function _log($msg){
        if(is_array($msg)){
            $msg=print_r($msg,1);
        }
        echo " $msg\n";
        file_put_contents('cronquote.txt', $msg,FILE_APPEND);
        
    }
    

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $localeInterface = $objectManager->create('Magento\Framework\Locale\ResolverInterface');
        $stf = $objectManager->create(StoreFactory::class);

        ///$cust=$this->customerRepo->get('debapi1@gmail.com');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

                $online=$this->onlineF->create();
        /**@var $online \Magento\Customer\Model\ResourceModel\Online\Grid\Collection  */


        $custs=$online->getItems();

$customers=[];
        foreach($custs as $c){

            if($c->getCustomerId()>0)
            $customers[]=$c->getCustomerId();
        }
        $customers=array_unique($customers);


        /*
        $customer = $objectManager->create(\Magento\Customer\Model\CustomerFactory::class)->create()->load($cust->getId());

        echo $cust->getId();
    //    $v= $cust->getCustomAttribute('pointpay');
//        $cust->getPointpay();
        echo $customer->getData('pointpay')." g ".$customer->getGroupId()."\n";
        $customer->setData('pointpay',100000);
        $customer->setGroupId(5);
//        ALTER TABLE quote ADD viscount DECIMAL(12,4) DEFAULT 0 NOT NULL;
  //      ALTER TABLE quote ADD base_viscount DECIMAL(12,4) DEFAULT 0 NOT NULL;
       
            $customer->save();
            */
  //      print_r($v->getValue());
 //       $this->customerRepo->save($customer);
        
  ///  return;
        ///$rt->unreserveByCid($pid, $sd, $ed, $qid)
        

        /**@var $res \Ibapi\Multiv\Model\Reserve */
        $res=$this->resFac->create();
        $rows=$res->getAllTime();
        $rt=$this->rtfact->create();

        $this->_log("logged in customers ".print_r($customers,1));
        
        foreach($rows as $r){

           if(in_array($r->uid,$customers)){
               $this->_log("customer ".$r->uid." logged in ");
               continue;
           }


          $this->_log( "delete: ".$r->id." quote   ".$r->qid." oid ".$r->oid." t " .$r->t." customer".$r->uid. "\n");
          
          if(!$r->oid){
              
          
               $rt->unreserveByQid($r->qid);
               
               try{
                    $quote=$this->quoteRepo->get($r->qid);
                    if($quote&&is_array($quote->getItems())) {
                        foreach ($quote->getItems() as $it) {

                            if (strpos($it->getSku(), 'res-') === 0) {

                                try {
                                    $this->_log("Product " . $it->getSku() . "  deleted \n");

                                    $this->pi->deleteById($it->getSku());
                                } catch (\Exception $e) {
                                    $msg = $e->getMessage();
                                    $this->_log("ERROR Product " . $it->getSku() . " :$msg: not deleted \n");
                                }

                            }
                        }
                    }else{

                        if($quote)
                        $this->_log("noitems for #".$quote->getItems());

                    }
//                    $this->registry->register('isSecureArea', false);
                    $this->quoteRepo->delete($quote);
                    
                    
               }catch(\Exception $e){


                   $this->_log( "ERROR ".$e->getMessage()."\n");
               }
               $this->_log("delete:unreserved ".$r->id."\n"); 
         
          
         }
          
          
          
          
            
        }
        
        $this->_log( "old quotes deleted\n");
        
        $rt=$this->rtfact->create();
        /**@quote $item \Magento\Quote\Model\Quote */
        /**@var $item \Magento\Quote\Model\Quote\Item */
       
        $time=strtotime('-30 minutes');
        $fils1=         $this->filterGroup->addFilter($this->filterBuilder->setField('sku')->setConditionType('like')->setValue('res-%')->create())->create();
        $fils2=$this->filterGroup->addFilter(
            $this->filterBuilder->setField('created_at')->setConditionType('lteq')->setValue(date('Y-m-d H:i:s',$time))->create()
            )->create();
        
            $this->searchCriteria->setFilterGroups([$fils1,$fils2]);
            $list=$this->pi->getList($this->searchCriteria);
            foreach($list->getItems() as $product){
                echo "deleting ".$product->getId()."  ".$product->getSku()." created ".$product->getCreatedAt()."\n";
//                $this->pi->deleteById($product->getSku());
                $this->pi->delete($product);
            }
            
            
            
        
$fils1=         $this->filterGroup->addFilter($this->filterBuilder->setField('is_active')->setConditionType('eq')->setValue(1)->create())->create();        
        
        $fils2=$this->filterGroup->addFilter(
            $this->filterBuilder->setField('updated_at')->setConditionType('lteq')->setValue(date('Y-m-d H:i:s',$time))->create()
        )->create();
        
        
       
        

        $this->searchCriteria->setFilterGroups([$fils1,$fils2]);
        
        $products=[];
        $list=$this->quoteRepo->getList($this->searchCriteria);
       
        foreach($list->getItems() as $quote){
            
            echo " quote ".$quote->getId()." ".$quote->getUpdatedAt()."\n";
            $del=false;
            $has=false;
            foreach($quote->getItems() as $item){
                $has=true;
                echo "item ".$item->getItemId()."\n";
                if(strpos($item->getSku(),'res-')===0){
                    $products[]=$item->getSku();
                    $del=true;
                }//delete this quote
                
            }
            if($del||!$has){
                echo "deleting quote ".$quote->getId()."\n";
                $this->quoteRepo->delete($quote);
            }

        }
        
      
            
        
        
                
        ///$rt->unreserveByCid($pid, $sd, $ed, $qid)
        $pd=date('Y-m-d H:i:s',strtotime('-1 day'));

        $this->_log("testing for date  $pd");
        $rsrv=$this->resFac->create();
        $orders = $this->orderFactory->create()->addFieldToSelect(
            '*'
            )->addFieldToFilter('statusdate',[
                'lteq'=>$pd
            ]
                
                )->addFieldToFilter('status',['delivery_progress','rent_process','successful_return'])->setOrder(
                    'created_at',
                    'desc'
                    )->setPageSize(100)->getItems();
                    
                    foreach($orders as $order){
                        
                        $this->_log("PRocessing ".$order->getRealOrderId()." status ".$order->getStatus());
                        /**@var $order \Magento\Sales\Model\Order */
                        
                        if($order->getStatus()=='delivery_progress'){
                            
                            
                            $order->setStatus('rent_process');
                            $r=$rsrv->getInfo($order->getRealOrderId());
                            $ndst=date('Y-m-d H:i:s',strtotime($r->rd.'-1 day'));
                            $this->_log("orderstatus of ".$order->getRealOrderId()." changed to  rent_process next $ndst \n");
        
                            $order->setStatusdate($ndst);

                            $order->addStatusHistoryComment(__('Item expected to receive'),'rent_process')->setIsCustomerNotified(true);
                            $this->orderRepo->save($order);

                            try {
   ///                             $comment = 'Your order has reached you';
                                $this->ordersender->send($order, true);
                            }catch (\Exception $e){
                                print $e->getTraceAsString();
                                print $e->getMessage();
                            }
                        }
                        else if($order->getStatus()=='rent_process'){
                            
                            $order->setStatusdate(date('Y-m-d H:i:s'));
        
                            $this->_log("orderstatus of ".$order->getRealOrderId()." changed to  delivery_return \n");
                            $order->setStatus('delivery_return');
                       ///     $order->setStatus('rent_process');
                            $r=$rsrv->getInfo($order->getRealOrderId());

                            $store=$stf->create()->load($order->getStoreId());
                            $localeInterface->setLocale($store->getCode());
                            $localeInterface->setDefaultLocale($store->getCode());
                            $note=__("Your rental expires and wait for the return by courier or return to the showroom on %1 ",$r->rd);

                            $order->addStatusHistoryComment(__('Rent expiry notice'),'delivery_return')->setIsCustomerNotified(true);

                            $this->orderRepo->save($order);
                              $this->ordersender->send($order,true);
                            
                        }
                        else if($order->getStatus()=='successful_return'){
                            $order->setStatusdate(date('Y-m-d H:i:s'));
                            $this->_log("orderstatus of ".$order->getRealOrderId()." changed to  waiting_review \n");
                            $order->setStatus('waiting_review');
                            $order->addStatusHistoryComment('Waiting review ','waiting_review')->setIsCustomerNotified(true);
                            $this->orderRepo->save($order);


                            $this->ordersender->send($order,true);
                            
                        }
                        
                    }

        
        
        
        
        
        
        
        }
        
        
        }
        
       