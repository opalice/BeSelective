<?php
namespace Ibapi\Multiv\Model\Checkout;
use Magento\Framework\Event\Observer;
use Ibapi\Multiv\Helper\Data;
use Magento\Quote\Model\QuoteRepository;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;
use Magento\Framework\Event\ObserverInterface;
use Ibapi\Multiv\Model\RtableFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
class SaveOrder implements ObserverInterface{
private $helper;
private $quoterepo;
private  $rfact;

	public function __construct(QuoteRepository $quoterepo,Data $helper,RtableFactory $rfact){

        $this->rfact=$rfact;
		$this->helper=$helper;
		$this->quoterepo=$quoterepo;
	}


	public function execute(Observer $observer){
		/* @var $order \Magento\Sales\Model\Order */
		$order=$observer->getEvent()->getOrder();

		$quote=$this->quoterepo->get($order->getQuoteId());
		/* @var $item \Magento\Quote\Model\Quote\Item */
		/* @var \Magento\Framework\DataObject\Copy */
		/*
		 * $this->objectCopyService->copyFieldsetToTarget('sales_convert_quote', 'to_order', $quote, $order);
		 * https://github.com/magento/magento2/issues/5823
		 * https://github.com/magento/magento2/issues/5823
		 */
		$oid=$quote->getReservedOrderId();

		$st=\Magento\Sales\Model\Order::STATE_NEW;
		$this->helper->log("\nsave order  state ".$order->getState()." status ".$order->getStatus()." checking $st ID".$oid);
		
		if($order->getState()&&$st!=$order->getState()){
		    $this->helper->log("not new order ".$oid." state ".$order->getState()." status ".$order->getStatus());
		    return $this;
		}
		if(!$oid){
        return $this;		    
		}
		

		$ordid=$oid;
		$has=false;
		$this->helper->log("saveorder:start item");
		foreach ($quote->getAllItems() as $itemp){
		    $itid=$itemp->getItemId()." id ".$itemp->getId().' '.$itemp->getProductType()." :";
        $this->helper->log("saveorder:process $itid  type ".$itemp->getProduct()->getId());
		 
        if($itemp->getProductType()=='bundle'){
            
        }else{
///            continue;
        }
		    $item=$itemp;
		    if($item->getProductType()!==AccessoryType::TYPE_CODE&&$item->getProductType()!==ClothType::TYPE_CODE){
				continue;
			}



			$v=$item->getOptionByCode('rental_option');
			
			$val=$v->getValue();
			if(!is_string($val)){
			    $val=print_r($val,1);
			}
			list($y,$m,$d,$dd)=explode('-',$val);
			$dt="$y-$m-$d";
			//        mktime(0,0,0,$m,$d,$y);
			list($sd,$ed)=$this->helper->getDatePair($dt, $dd);
			
			
			$this->helper->log(" :quote:v ".$v->getCode()." value  $val");

			$pid=$item->getProduct()->getId();
			$rt=$this->rfact->create();
			$incid=$quote->getReservedOrderId();
			$qid=$quote->getId();
			
			
			
			if(!$rt->isreserved($pid,$qid,$sd,$ed,$incid,$item->getId())){
			    $this->helper->log(" cannot put $pid, qid $qid se $sd ed $ed incid $incid itemid ".$item->getId());
			    
			    throw new LocalizedException(__('Too late reservation cancelled. please try again.'));

			}
			
			
			$orderItem=$order->getItemByQuoteItemId($item->getItemId());

			/* @var $orderItem \Magento\Sales\Api\Data\OrderItemInterface */
/*
			$info=$v->getValue();
			list($y,$m,$d,$dd)=explode('-', $info);
			$sd="$y-$m-$d";
			list($sd,$ed)=$this->helper->getDatePair($sd, $dd);
			$this->helper->log("##datepair $sd to $ed ####");
            $f=$rt->reserve($orderItem->getProductId(), $sd, $ed,'u',$ordid);
            if(!$f){
                throw new LocalizedException(__('Cannot reserve for %1 to %2 . try another date.',$sd,$ed))
                ;
            }
*/
			
			


///			$this->helper->copyToOrder($img,$order->getIncrementId().'_'.$pid);

            $info=$val;
			$orderItem->setRentalDates($info);




		}
$this->helper->log("saveorder:processed allitem");		
return $this;

	}

}