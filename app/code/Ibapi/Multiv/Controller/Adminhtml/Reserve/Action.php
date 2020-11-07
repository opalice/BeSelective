<?php
namespace Ibapi\Multiv\Controller\Adminhtml\Reserve;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogSearch\Block\ResultTest;
use Magento\Directory\Model\PriceCurrency;
use Magento\Eav\Model\EavCustomAttributeTypeLocator\SimpleType;
use Magento\Framework\Controller\ResultFactory;
use Magento\Sales\Model\OrderRepository;
use Magento\Sales\Api\RefundInvoiceInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Sales\Model\Order\Invoice;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\SubType;
use Magento\Sales\Model\Order;
use Ibapi\Multiv\Model\Type\DepType;
use Ibapi\Multiv\Controller\Index\Image;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Model\Order\ItemRepository;
use Magento\Store\Model\StoreFactory;

class Action extends \Magento\Backend\App\Action
{
	protected $resultFactory = false;
	protected $reserveFactory;
    protected $orderRepo;
    protected $invoiceService;
    protected $refunder;
    protected $customerrepo;
    protected $scope;
    protected  $helper;
    protected  $itRepo;
    protected $invoiceRepo;
    protected  $searchCb;
    protected $registry;
    protected $ordersender;
    protected  $transportBuilder;
    protected  $inlineTranslation;
    protected  $priceCurrency;
    protected  $productRepo;
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
	    \Magento\Framework\Controller\ResultFactory $resultFactory,
	    OrderRepository $orderRepo,
	    \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
	    \Ibapi\Multiv\Model\ReserveFactory $reserveFactory,
	    \Magento\Sales\Model\Service\InvoiceService $invoiceService,
	    \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
	    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
	    RefundInvoiceInterface $refunder,
	    InvoiceRepositoryInterface $invRepo,
	    ItemRepository $itrepo,
	    \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender $orderSender,
	    \Magento\Framework\Registry $registry,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
	    \Ibapi\Multiv\Helper\Data $helper,
       PriceCurrency $priceCurrency,
    ProductRepositoryInterface  $pi


	    )
	{
	    
	    
		parent::__construct($context);
		$this->priceCurrency=$priceCurrency;
		$this->transportBuilder = $transportBuilder;
$this->inlineTranslation = $inlineTranslation;
$this->productRepo=$pi;
		$this->itRepo=$itrepo;
		$this->ordersender=$orderSender;
		$this->invoiceRepo=$invRepo;
		$this->reserveFactory=$reserveFactory;
		$this->orderRepo=$orderRepo;
		$this->resultFactory = $resultFactory;
        $this->invoiceService=$invoiceService;
        $this->refunder=$refunder;
        $this->scope=$scopeConfig;
        $this->helper=$helper;
        $this->registry=$registry;
        $this->searchCb=$searchCriteriaBuilder;
      $this->customerrepo=$customerRepositoryInterface;
	}
	
	public function templateReplace($tmpl,$note,\Magento\Sales\Model\Order $order){
	   $nm= $order->getCustomer()->getName();
	   $id=$order->getRealOrderId();
	   return substr_replace(['#NAME','#ORDERID','#NOTE'], [$nm,$id,$note],$nm);
	}

	public function execute()
	{
	    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	    $localeInterface = $objectManager->create('Magento\Framework\Locale\ResolverInterface');
	    $stf = $objectManager->create(StoreFactory::class);
	    $depoitems=[];

	    $a=$this->getRequest()->getParam('action');
	    $id=$this->getRequest()->getParam('id');
	    $res=$this->reserveFactory->create()->load($id);
	    $owsers=[];

        $cp=$this->scope->getValue('rentals/procat/comm')/100;
	    /**@var $res \Ibapi\Multiv\Model\Reserve */
	    switch($a){
	        default:
	            break;
	        case 'O':
	            $oid=$res->getOid();
	            $otid=$res->getOtid();
	            $ownerid=$res->getO();
	            $owneramts=[];
	            $ownerprds=[];

	            $searchCriteria = $this->searchCb->addFilter('increment_id', $oid, 'eq')->create();
	            $order = $this->orderRepo->getList($searchCriteria)->getFirstItem();
	            $orderid=$order->getId();
	            $resrs=$this->reserveFactory->create()->getCollection()->addFieldToFilter('oid',$oid)->getItems();
	            foreach($resrs as $ro){
	                $owners[$ro->getOtid()]=$ro->getO();
	                $owneramts[$ro->getO()]=0;

	                $product=$this->productRepo->getById($ro->getPid());



	                $ownerprds[$ro->getO()][]=[ 'id'=>$ro->getId() ,'oid'=>$ro->getOid() ,'pid'=>$ro->getPid(),'c'=>$ro->getP()/1.21,'prd'=>$product->getName(),'prdurl'=>$product->getProductUrl()];

	            }
	            
	            
	            
//	            $reserv=$res->getCollection()->addFieldToFilter('oid',$orderid)->addFieldToFilter('otid',$itemid)->getFirstItem();
                $this->helper->log("adminer: $oid order  $orderid otid $otid owner $ownerid ID: $id");	            
	            
	            $pa=$this->getRequest()->getParam('paction');
                $part=(float)$this->getRequest()->getParam('part'); 
                $note=$this->getRequest()->getParam('note');
                $cc=$this->getRequest()->getParam('message');
                
                $oa=$this->getRequest()->getParam('raction');
                
	
                $depo=$depoid=$rent=$rentid='';
                
                $depo=$res->getD();
                $rent=$res->getP();
                /**@var $order \Magento\Sales\Model\Order */
                $items=$order->getAllItems();
                $allitems=[];
                $rentids=[];
                $amt=0;
                $depoids=[];
                $selitem='';
                /**@var $item \Magento\Sales\Model\Order\Item */
                foreach($order->getItems() as $item){


                    if(in_array($item->getProductType(),['simple','virtual'])) {

                        $rentids[$item->getId()]=$item->getQtyOrdered();
                        $depoids[$item->getId()]=0;


                    }
                    else if(in_array($item->getProductType(),[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])){
                        $allitems[$item->getParentItemId()]['r']=$item->getId();
                        $allitems[$item->getParentItemId()]['o']=$item;
                        $amt+=$item->getBasePrice();
                        
                        $prod=$item->getProduct();
                        
                       $psku= $item->getParentItem()->getSku();
                        $dds=explode('-',$psku);
                        $dis=$prod->getData($dds[5]==4?'vip_discount':'vip_discount8');
                        $oi=$owners[$item->getId()];
                        $owneramts[$oi]+=$item->getBaseRowTotal()-$dis;
                        $this->helper->log("discoutn $dis for $oi added for item ".$prod->getSku()."\n");



                        
                        $rentids[$item->getId()]=1;
                        $depoids[$item->getId()]=0;
                        
                    }else if($item->getProductType()==DepType::TYPE_CODE){
                        $allitems[$item->getParentItemId()]['d']=$item->getId();
                        $depoids[$item->getId()]=1-$item->getQtyInvoiced();
                        $item->setIsQtyDecimal(true);

                        $rentids[$item->getId()]=0;
                        $depoitems[]=$item;
                        
                    }else if($item->getProductType()==SubType::TYPE_CODE){
                        $allitems[$item->getParentItemId()]['r']=$item->getId();
                        $depoids[$item->getId()]=0;
                        $rentids[$item->getId()]=1;
                        
                    }
                    
                    else if($item->getProductType()!='bundle'){
                        $depoids[$item->getId()]=0;
                        $rentids[$item->getId()]=0;
                    }
                }
                
//                $items=$order->getItems();
                /**@var $item \Magento\Sales\Model\Order\Item */
                
                
                
                
            ///    file_put_contents('admino.txt',"depo $depoid pid $rentid   price $rent depo $depo  pa $pa oa $oa 1\n");
                if($pa=='p'){
                    
                    if(!in_array($order->getStatus(),['delivery_return','rent_default'])){
                        
                        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['error'=>'1','result'=>__('order not in return mode # %1',$order->getRealOrderId())]);
                    }
                    
                    
                    if($part){
                        
                        $per=$part/$depo;
                        if($per>100){
                            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['error'=>'1','result'=>__('Invalid amount # %1/%2',$part,$depo)]);
                        }
                        
                        foreach($allitems as $p=>$k){
                            if(!isset($k['d'])){
                                $this->helper->log('notmsg:'.print_r($k,1));
                                continue;
                            }
                            $depox=$k['d'];
                            $rx=$k['r'];
                            if($rx==$otid){
                                $selitem=$k['o'];
                                $selitem->setIsQtyDecimal(true);
                                $depoids[$depox]=$per;
                            $this->helper->log("adminr: partay depo $depox per $per rx $rx resid $id  ");
                                
                            }else{
                                $depoids[$depox]=0;
                            }
                            
                        }
                        
                        $this->registry->register('ops_always_capture',true);
                        
                        $invoice=$this->invoiceService->prepareInvoice($order,$depoids);
                        
                        if($cc)
                            $invoice->addComment($cc,true,true);
                                $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
                                
                                $invoice->register();
                                $invoice->setState(Invoice::STATE_PAID);
                                $this->invoiceRepo->save($invoice);
                             
                                $IID=$invoice->getId();
                                $amt=$invoice->getGrandTotal();
                                /*@var $pay \Magento\Sales\Api\Data\OrderPaymentInterface */
                               $this->invoiceService->notify($invoice->getId());
                                /*
                                $invoice=$this->invoiceService->prepareInvoice($order);
                                $this->invoiceRepo->save($invoice);
                                $this->invoiceService->setVoid($invoice);
                                $invoice->setState(Invoice::STATE_PAID);
                                $this->invoiceRepo->save($invoice);
                                */
///                                $this->invoiceService->notify($invoice->getId());
                                
                                $order->setStatus('rent_default');

                                
                                
                                    

                                $order->addStatusHistoryComment(
                                    __("Deposit partial capture invoice #%1 %2",$IID ,$note?"\n$note":'')
                                    ,'rent_default')->setIsCustomerNotified(true);
                                    ;




                              /*
                                    $order->setState(Order::STATE_PROCESSING);
                                    
                                    $tt=$order->getTotalPaid()+$invoice->getGrandTotal();
                                    $ttt=$order->getBaseTotalPaid()+$invoice->getBaseGrandTotal();
                                    $order->setTotalPaid($tt);
                                    $order->setBaseTotalPaid($ttt);
                                    $order->getPayment()->setAmountPaid($order->getBaseTotalPaid());
                                    */
                                    $order->setStatusdate(date('Y-m-d H:i:s'));

                                    $this->orderRepo->save($order);
                                    $this->ordersender->send($order,true);

                                    /*
                                    $store=$stf->create()->load($order->getStoreId());
                                    $localeInterface->setLocale($store->getCode());
                                    $localeInterface->setDefaultLocale($store->getCode());
                                    
                                    $note=__("We have received your rented items. For the reasons listed below, we are obliged to retain an amount of € %1 on the deposit.

REASONS:
1. damage to rented items
2. delivery delay
3. no return of rented items
4. others",number_format($amt,2)).$note?"\n".$note:'';

                        $order->addStatusHistoryComment($note,'rent_default')->setIsCustomerNotified(true);

                */
                       ///             $this->ordersender->send($order,true,$note);
                                    
                                        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['ok'=>1 ,'error'=>0,'all'=>1,'result'=>__('deposit partial capture. %1 . Rest needs to be voided ',$amt)]);

                        
                    }
                    else{
                        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['error'=>'1','result'=>__('Amount not specified.')]);
                    }

                
                }
                
                
                else if($pa=='t'){
                    
                    $qtleft=1-$item->getQtyInvoiced();

                    foreach($allitems as $p=>$k){
                        if(!isset($k['d'])){
                            continue;
                        }
                        $depox=$k['d'];
                        $rx=$k['r'];
                        if($rx!=$otid){
                            
                            $depoids[$depox]=0;
                            
                        }
                        
                    }
                    
                    
                    $invoice=$this->invoiceService->prepareInvoice($order);
  
                      $it=  $invoice->getBaseGrandTotal();
                      
                      $this->helper->log("invoice: $it");
                    if($cc)
                        $invoice->addComment($cc,true,true);
                    
                    
                    if($pa=='v'){

                        if(!in_array($order->getStatus(),['rent_default'])){
                            
                            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['error'=>'1','result'=>__('order not in return mode # %1',$order->getRealOrderId())]);
                        }
                        
                        
                        
                        $this->helper->log("adminr: voided $depox rx $rx resid $id  ");
                    ///    $this->invoiceRepo->save($invoice);
                        $this->invoiceService->setVoid($invoice->getId());
                    ///$order->setStatus('delivery_return');

                        //$this->invoiceService->notify($invoice->getId());
                        $order->setStatus('rent_default_full');                       
                        $order->setStatusdate(date('Y-m-d H:i:s'));
                        $order->setState(Order::STATE_CLOSED);
                        $this->invoiceRepo->save($invoice);
                        
                        
                        $order->addStatusHistoryComment(
                            __('balance Deposit refunded invoice #%1. %2', $invoice->getId(),$note?"\n$note":'')
                            ,'rent_default_full')->setIsCustomerNotified(true);
                            ;

                            $this->orderRepo->save($order);
                            $this->ordersender->send($order,true);
                            
                            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['ok'=>'1','result'=>__('balance deposit refunded  invoice created # %1',$invoice->getId())]);
                            
                    
                    }
                     else
                     {
                         if($order->getStatus()!=='delivery_return'){
                             
                             return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['error'=>'1','result'=>__('order not returned # %1',$order->getRealOrderId())]);
                         }
                         
                         $this->helper->log("adminr: forfeit $depox rx $rx resid $id  ");
                         $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
                         //$invoice->setState(Invoice::STATE_PAID);
                         $this->registry->register('ops_always_capture',true);
                         $invoice->register();
///                         $invoice->capture();
///                         $invoice->setState(Invoice::STATE_PAID);
                         $this->invoiceRepo->save($invoice);
                        $this->invoiceService->notify($invoice->getId());
                         
                         $order->setStatusdate(date('Y-m-d H:i:s'));
                         $order->setState(Order::STATE_CLOSED);
                         $order->setStatus('rent_default_full');

     /*                    
                         $tt=$order->getTotalPaid()+$invoice->getGrandTotal();
                         $ttt=$order->getBaseTotalPaid()+$invoice->getBaseGrandTotal();
                         $order->setTotalPaid($tt);
                         $order->setBaseTotalPaid($ttt);
                         $order->getPayment()->setAmountPaid($order->getBaseTotalPaid());
                         
   */                      

                         
                         
                         
                         $order->addStatusHistoryComment(
                             __("Deposit forfeited invoice #%1. %2", $invoice->getId(),$note?"\n".$note:'')
                             ,'rent_default_full')->setIsCustomerNotified(true);
                             ;
                             

                         
                         $this->orderRepo->save($order);

                         $this->ordersender->send($order,true);
                         return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['ok'=>'1','result'=>__('deposit Amount forfeited and invoice created # %1',$invoice->getId()),'all'=>1]);
                     }

                     
                 //   file_put_contents("admino.txt", "voided $depoid inv ".$invoice->getId()."\n",FILE_APPEND);
                    return;
                    
                    
                }
                else if($oa=='r'||$pa=='v'){
                    
                    if($pa!='n'){
                        if($pa=='v'){
                            if(!in_array($order->getStatus(),['rent_default']))
                            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['error'=>'1','result'=>__('order not in fault mode # %1',$order->getRealOrderId())]);
                            $stt='partial_return';
                        }

                        else if($order->getStatus()!=='delivery_return'){
                            
                            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['error'=>'1','result'=>__('order not returned # %1 = %2 ',$order->getRealOrderId(),$order->getStatus())." pa $pa"]);
    
                        }else{
                            $stt='successful_return';
                            $store=$stf->create()->load($order->getStoreId());
                            $localeInterface->setLocale($store->getCode());
                            $localeInterface->setDefaultLocale($store->getCode());
                            
                            $note=__('We have received the rented items and your deposit will be released soon in its entirety. ').$note?"<br>\n".$note:'';
                        }
                        
                        $this->helper->log("adminr: received amt rx $amt resid $id ritds ".print_r($depoids,1));
                        
                        //   file_put_contents('admin.txt',"creaiting info for rent $rentid and depoid $depoid\n",FILE_APPEND);
                        $invoice=$this->invoiceService->prepareInvoice($order,$depoids);
///                        $this->invoiceRepo->save($invoice);
                        $can=[];
                        $rr=0;
                        foreach ($depoitems as $item) {
                            $f=(float)$item->getQtyInvoiced();
                            $can[$item->getId()]=$f;
                            if($f>0) $rr=1;
                                
                        }
                        
                        
                        $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
                        $invoice->void();
                        $to=$invoice->getBaseGrandTotal();

///                     
                        
                        $invoice->setState(Invoice::STATE_CANCELED);
                        


                        foreach ($depoitems as $item) {
                               $item->setQtyCanceled(1-$can[$item->getId()]);
                               
                                $this->helper->log("item cancelled ".$item->getQtyToCancel());
///                                $item->save();
    $item->cancel();
    $this->itRepo->save($item);
                        }


///                        $this->invoiceService->setVoid($invoice->getId());

   ///                     $this->invoiceService->notify($invoice->getId());
                        if(!$rr){
                        $order->setStatus('successful_return');
                        $rr='successful_return';

                            $note=__("We have  released  deposit ϵ %1. %2",  $invoice->getGrandTotal(),$note?"\n$note":'');
                        }
                        else{
                            $rr='partial_return';
                            $order->setStatus('partial_return');
                            $note=__("We have partially released  ϵ %1. %2",  $invoice->getGrandTotal(),$note?"\n$note":'');

                        }
                     //   $this->invoiceService->notify($invoice->getId());

                        $this->helper->log("cancel: totaldue canceled $to ");
                        ///$order->setTotalDue($order->getTotalDue()-$to);

                        $order->setTotalRefunded($to);
                        $order->setBaseTotalRefunded($to);

                        $order->setTotalDue(0);
                        $order->setBaseTotalDue(0);

      //                  $order->setTotalRefunded($order->getTotalRefunded()+$invoice->getGrandTotal());
         ///               $order->setBaseTotalRefunded($order->getTotalRefunded()+$invoice->getBaseGrandTotal());
                        
                        $order->setState(Order::STATE_COMPLETE);
                        
                        $order->setStatusdate(date('Y-m-d H:i:s'));
                        
                        $order->addStatusHistoryComment(
                            __("Deposit refunded. %1", $note?"\n$note":'')
                            ,$rr)->setIsCustomerNotified(true);
                            ;
                        

                            
                        $this->orderRepo->save($order);
                       $this->ordersender->send($order,true);
                        ///$this->helper->log("malsent: $f");
                        
                        /*
                         * 1) Order Processing (When payment is done)
                         2) Rent Delivery in progress When shipping is done
                         3) Rent in process (Automatically change shipping +1)
                         4) Delivery Return in progress (Automatically change the day of return plannified + send a e-mail with the date + hour frame variable
                         5) Successful Return (When admin click on button successful return in back-end)
                         6) Waiting Review (automatically change 1 day after Successful Return + send a e-mail asking user to leave a Review on the rent
                         Link to leave a review also available on the admin dashboard user on order list
                         7) Complete (only change is review is done)
                         
                         *
                         *
                         *
                         ALTER TABLE m21_1.customer_entity ADD pointpay DECIMAL DEFAULT 0 NOT NULL;
                         
                         --  Actual parameter values may differ, what you see is a default string representation of values
                         INSERT INTO sales_order_status (status,label)
                         VALUES ('delivery_progress','Rend Delivery In Progress') ;
                         INSERT INTO sales_order_status (status,label)
                         VALUES ('rent_process','Rent In Process') ;
                         INSERT INTO sales_order_status (status,label)
                         VALUES ('delivery_return','Delivery Return in Progress') ;
                         INSERT INTO sales_order_status (status,label)
                         VALUES ('successful_return','Successful return') ;
                         INSERT INTO sales_order_status (status,label)
                         VALUES ('waiting_review','Waiting Review') ;
                         INSERT INTO sales_order_status (status,label)
                         VALUES ('amount_forfeit','Forfeit') ;
                         INSERT INTO sales_order_status (status,label)
                         VALUES ('full_refund','Full refund') ;
                         
                         
                         
                         INSERT INTO sales_order_status_state (status,state)
                         VALUES ('delivery_progress','processing') ;
                         INSERT INTO sales_order_status_state (status,state)
                         VALUES ('delivery_return','processing') ;
                         INSERT INTO sales_order_status_state (status,state)
                         VALUES ('successful_return','complete') ;
                         INSERT INTO sales_order_status_state (status,state)
                         VALUES ('waiting_review','complete') ;
                         INSERT INTO sales_order_status_state (status,state)
                         VALUES ('amount_forfeit','closed') ;
                         INSERT INTO sales_order_status_state (status,state)
                         VALUES ('full_refund','closed') ;
                         
                         
                         */
                        
                        /*
                        if($cc)
                            $invoice->addComment($cc,true,true);
                            if($note)
                                $invoice->addComment($note,false,false);
                                $invoice->save();
                                */
                                //                                $order>setState('your_state');
//                                $order->setStatus('delivery_return');
                            //    $order->save();
                                
                                
                                //      file_put_contents("admino.txt", "created ".$invoice->getId()."\n",FILE_APPEND);
                                return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['ok'=>'1','result'=>__('Deposit Refunded.  # %1',$invoice->getId()),'all'=>1]);
                                
                                
                                
                    }
                    
                    
                    
                }
                
                else if($oa=='s'){
                   
///                        $order->setState();
                    
                    if($pa!='n'){
                        
                        $paid=$order->getTotalPaid();
                        $base=$order->getBaseTotalPaid();
                        $this->helper->log("adminr: paid amt rx $amt resid $id ritds ".print_r($rentids,1));
                        
                        if(!$order->getStatus()==Order::STATE_PROCESSING){
                        
                            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['error'=>'1','result'=>__('order not processed # %1',$order->getRealOrderId())]);
                        }
                        
                        
                     //   file_put_contents('admin.txt',"creaiting info for rent $rentid and depoid $depoid\n",FILE_APPEND);
                        $invoice=$this->invoiceService->prepareInvoice($order,$rentids);
                        
                        //$invoice->setState(Invoice::STATE_PAID);
                        ////$invoice->register()->save();
                        
///                        $inv=$invoice->getId();

///                        $this->helper->log("adminr: inv $inv");
                        $invoice->setState(Invoice::STATE_OPEN);
                        $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
                        
                        ///$capt=$this->invoiceService->setCapture($invoice->getId());
                        
/*
                        $shippingAmount = $order->getShippingAmount();
                        $subTotal = $amt;
                        $baseSubtotal = $bamt;//+$order->getBaseShippingAmount();
                        $grandTotal = $baseSubtotal+$order->getBaseShippingAmount();
                        $baseGrandTotal = $order->getBaseGrandTotal();
                        */
                        /*
                        $invoice->setShippingAmount($shippingAmount);
                        
                        $invoice->setDiscountAmount($order->getDiscountAmount());
                        
                        $invoice->setSubtotal($subTotal);
                        $invoice->setBaseSubtotal($baseSubtotal);
                        $invoice->setGrandTotal($grandTotal);
                        $invoice->setBaseGrandTotal($baseGrandTotal);
                        */
                        $invoice->register();
                        $invoice->pay();
                     ///   $invoice->setState(Invoice::STATE_PAID);
                        if($cc){
                            $invoice->addComment($cc,true,true);
                        }
                        
                        /*
                        $transactionSave = $this->_transaction->addObject(
                            $invoice
                            )->addObject(
                                $invoice->getOrder()
                                );
                            $transactionSave->save();
                            */
                            if(!$invoice->getId()){
                                $this->invoiceRepo->save($invoice);
                                $tt=$order->getTotalPaid()+$invoice->getGrandTotal();
                         //       $ttt=$order->getBaseTotalPaid()+$invoice->getBaseGrandTotal();
                        //        $order->setTotalPaid($tt);
                        //        $order->setBaseTotalPaid($ttt);
                        //        $order->getPayment()->setAmountPaid($order->getBaseTotalPaid());
                             $this->helper->log("order saved total  with new invoice");
                          ///      $this->orderRepo->save($order);
                            }else{
                                $this->helper->log("order saved total  with old invoice ");
                            }
                            try{

//                                $note=__('We have shipped your order').$note?"\n$note":'';
                           $this->invoiceService->notify($invoice->getId());
                            
                            ///$this->invoiceSender->send($invoice);
                            //send notification code



                   ///         $order->setState(Order::STATE_PROCESSING);
                            
                            $order->setStatusdate(date('Y-m-d H:i:s'));
                            
///                            $order->setState(Order::STATE_PROCESSING);   
                            $order->addStatusHistoryComment(
                                __("Payment of rental invoice #%1. %2", $invoice->getId(),$note?"\n".$note:'') ,'delivery_progress')->setIsCustomerNotified(true);
                                ;
                                $this->orderRepo->save($order);
                                $this->ordersender->send($order,true);
                            }catch(\Exception $e){

                                $this->helper->log("orderstatus:".$e->getMessage());
                            }
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
                        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                        $connection = $resource->getConnection();
                        
                        
                            foreach($owneramts as $ownerid=>$amt){
                       /// $this->helper->log("adminr: $capt ownerid $ownerid");
                        $ow=$this->customerrepo->getById($ownerid);
                        if(!$ow||!$ow->getId()){
                            $this->helper->log("adminr: noowner");
                            return;
                        }
                        

                        try{
                        $tableName = $resource->getTableName('multiv_res'); //gives table name with prefix

                            $ca=0;
                            foreach($ownerprds[$ownerid]  as $lt) {

                                $c = $lt['c']*$cp;
                                $ca += $c;

                                $connection->query("update $tableName set c=$c where id=" . $lt['id']);


                                $this->inlineTranslation->suspend();
                                try {
                                    $obj = new \Magento\Framework\DataObject();
                                    $obj->setData([
                                        'orderid'=>$lt['oid'],
                                        'fname' => $ow->getFirstname(),
                                        'lname' => $ow->getLastname(),
                                        'amt' => $this->priceCurrency->format($c,false),
                                        'prd' => $lt['prd'],
                                        'prdurl' => $lt['prdurl']


                                    ]);


                                    $error = false;

                                    $sender = [
                                        'name' => '',
                                        'email' => '',
                                    ];

                                    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                                    $transport = $this->transportBuilder
                                        ->setTemplateIdentifier('commission_mail')
                                        ->setTemplateOptions(
                                            [
                                                'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                                                'store' => $order->getStoreId(),
                                            ]
                                        )
                                        ->setTemplateVars(['data' => $obj])
                                        ->setFrom('general')
                                        ->addCc('info@beselective.be','admin')
                                        ->addTo($ow->getEmail(),$ow->getFirstname().' '.$ow->getLastname())
                                            ->getTransport();
                                    ///$transport->getMessage()->setMessageType();
                                    $transport->sendMessage();;
                                    $this->inlineTranslation->resume();
                                    $this->helper->log("mail:sent ".print_r($lt,1));

                                } catch (\Exception $e) {

                                    $this->helper->log("mail:error " . $e->getMessage());
                                }

                            }



                        $tableName = $resource->getTableName('customer_entity'); //gives table name with prefix
                        $ca=$amt*$cp;
                        $this->helper->log("commission: per $cp amt $amt add $ca ");
                        $this->helper->log("commission:update $tableName set pointpay=pointpay+$ca where entity_id=$ownerid");
                        $connection->query("update $tableName set pointpay=pointpay+$ca where entity_id=$ownerid");


                        }catch(\Exception $e){
                            $this->helper->log("commission: ".$e->getMessage());
                            
                        }
                        
                    }
                        /*
                        $pp=$ow->getData('pointpay');
                                           
                        $cc=$amt*$cp/100;
                        $ow->setData('pointpay',$pp+$cc);
                        $ow->save();
                        $res->setData('c',$cc);
                        $res->save();
                        */
                 /*
                  * 1) Order Processing (When payment is done)
2) Rent Delivery in progress When shipping is done
3) Rent in process (Automatically change shipping +1)
4) Delivery Return in progress (Automatically change the day of return plannified + send a e-mail with the date + hour frame variable
5) Successful Return (When admin click on button successful return in back-end)
6) Waiting Review (automatically change 1 day after Successful Return + send a e-mail asking user to leave a Review on the rent
Link to leave a review also available on the admin dashboard user on order list
7) Complete (only change is review is done)

                  *
                  *
                  --  Actual parameter values may differ, what you see is a default string representation of values
INSERT INTO sales_order_status (status,label)
VALUES ('delivery_progress','Rend Delivery In Progress') ;
INSERT INTO sales_order_status (status,label)
VALUES ('rent_process','Rent In Process') ;
INSERT INTO sales_order_status (status,label)
VALUES ('delivery_return','Delivery Return in Progress') ;
INSERT INTO sales_order_status (status,label)
VALUES ('successful_return','Successful return') ;
INSERT INTO sales_order_status (status,label)
VALUES ('waiting_review','Waiting Review') ;
INSERT INTO sales_order_status (status,label)
                         VALUES ('amount_forfeit','Forfeit') ;
INSERT INTO sales_order_status (status,label)
                         VALUES ('full_refund','Full refund') ;
                         
                         
                         
INSERT INTO sales_order_status_state (status,state)
VALUES ('delivery_progress','processing') ;
INSERT INTO sales_order_status_state (status,state)
VALUES ('delivery_return','processing') ;
INSERT INTO sales_order_status_state (status,state)
VALUES ('successful_return','complete') ;
INSERT INTO sales_order_status_state (status,state)
VALUES ('waiting_review','complete') ;
INSERT INTO sales_order_status_state (status,state)
VALUES ('amount_forfeit','closed') ;
INSERT INTO sales_order_status_state (status,state)
VALUES ('full_refund','closed') ;                         
                         

                  */       
                        
                        /*
                        if($cc)
                            $invoice->addComment($cc,true,true);
                            if($note)
                                $invoice->addComment($note,false,false);
                                 $invoice->save();
                                 */
//                                $order>setState('your_state');
                        
                        
                  //      file_put_contents("admino.txt", "created ".$invoice->getId()."\n",FILE_APPEND);
                            //$this->ordersender->send($order,true);

                            $localeInterface->setLocale($order->getStore()->getLocaleCode());
                            $localeInterface->setDefaultLocale($order->getStore()->getLocaleCode());
                            
                         ///   $this->ordersender->send($order,true,$note);
                            
                        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['ok'=>'1','result'=>__('Amount captured and invoice created # %1',$invoice->getId()),'all'=>1]);
                        
                        
                        
                    }
                    
                   
                    
                }
                
                
                
                /**@var $order \Magento\Sales\Model\Order */

	            
	            
///	            $ct=	            $order->getInvoiceCollection()->load()->count();
	            
	            
	            
	                            
//	            echo "refunding $invoiceId<br>";
	            
	            //$this->refunder->execute($invoiceId,[],true);
	            
	            
	            /**@var $order \Magento\Sales\Model\Order */
	            /*
	            $item=$order->getItemById($itemid);
                    echo "ITEm ".$item->getItemId()."<br>";	           
	            $price=$res->getData('p');
	            $invoice=$this->invoiceService->prepareInvoice($order);
	            
	            $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
	            
   	           $invoice->register();
	            */
	            
	            
	            
	    }
	    die(json_encode('okaction'));
	    

	    return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['ok'=>'1']);
	    
	}


}