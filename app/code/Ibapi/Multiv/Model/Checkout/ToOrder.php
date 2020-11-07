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
class ToOrder implements ObserverInterface{
private $helper;
private $quoterepo;
private  $rfact;
/*
	public function __construct(QuoteRepository $quoterepo,Data $helper,RtableFactory $rfact){

        $this->rfact=$rfact;
		$this->helper=$helper;
		$this->quoterepo=$quoterepo;
	}
*/

	public function execute(Observer $observer){
		/* @var $order \Magento\Sales\Model\Order */
		$order=$observer->getEvent()->getOrder();
		$quote=$observer->getEvent()->getQuote();
		/* @var $item \Magento\Quote\Model\Quote\Item */
		/* @var $quote \Magento\Quote\Model\Quote\Quote */
		$vip=$quote->getVipdiscount();
		$vis=$quote->getViscount();
		$order->setData('vipdiscount',$vip);
		$order->setData('viscount',$vis);
		$order->setData('base_vipdiscount',$vip);
		$order->setData('base_viscount',$vis);
        $tis=$quote->getTiscount();
        $btis=$quote->getBaseTiscount();
        $order->setData('tiscount',$tis);
        $order->setData('base_tiscount',$btis);

		file_put_contents('torder.txt' ,"pointpay:vip $vip vis $vis \n",FILE_APPEND);
	
	}
		
		}