<?php 
namespace Ibapi\Multiv\Model;

use Ibapi\Multiv\Model\Type\DepType;
use Magento\Framework\Event\ObserverInterface;

class Shipmail implements ObserverInterface


{
    private  $rfact;

    public function __construct(

        \Ibapi\Multiv\Model\ReserveFactory $rfact
    )
    {
       $this->rfact=$rfact;
    }

    private  function  _log($x){
        file_put_contents('shipmail.txt',$x."\n",FILE_APPEND);
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $rsrv=$this->rfact->create();
        /**@var $sender     \Magento\Sales\Model\Order\Email\Sender\EmailSender */
        /**@var $shipment  \Magento\Sales\Model\Order\Shipment */
        /**@var $order \Magento\Sales\Model\Order */
        /**@var $rsrv  \Ibapi\Multiv\Model\Reserve */
        $trasport = $observer->getEvent()->getTransport();
        /**@var $invoice  \Magento\Sales\Model\Order\Invoice */



        $order=$trasport['order'];
        $trasport['status']=$order->getStatus();
        $this->_log("Processing ".$order->getRealOrderId()." ST ".$order->getStatus());
        if($order->getStatus()=='delivery_progress') {

            $invoice=  $order->getInvoiceCollection()->getFirstItem();

            $this->_log("Processed ".$order->getRealOrderId()." ST ".$order->getStatus());
            $r=$rsrv->getInfo($order->getRealOrderId());
            ///$trasport['returndate']=$r->sd;
            $order->setSd($r->sd);
            $order->setReturndate($r->rd);
            $order->setAmt($invoice->getBaseGrandTotal());



            $order->setStatusDp(1);
        }
        if($order->getStatus()=='rent_process'){


            $this->_log("Processed ".$order->getRealOrderId()." ST ".$order->getStatus());

            $order->setStatusRp(1);

        }


        if($order->getStatus()=='partial_return'){

            $amt=$order->getBaseTotalRefunded();
            $trasport['amount']=$amt;
            $this->_log("  order: ".$order->getId()." real ".$order->getRealOrderId()." invoice ".$trasport['amount']." status ".$order->getStatus());

            $this->_log("Processed ".$order->getRealOrderId()." ST ".$order->getStatus());
            $order->setAmt($trasport['amount']);
            $order->setStatusPr(1);
        }
        if($order->getStatus()=='rent_default_full'){

              $invoice=  $order->getInvoiceCollection()->getLastItem();
              $trasport['amount']=$invoice->getBaseGrandTotal();

            $this->_log("  order: ".$order->getId()." real ".$order->getRealOrderId()." invoice ".$trasport['amount']." status ".$order->getStatus());

            $this->_log("Processed ".$order->getRealOrderId()." ST ".$order->getStatus());

            $order->setFault($trasport['amount']);
            $order->setStatusRdf(1);
        }
        if($order->getStatus()=='rent_default'){

            $invoice=  $order->getInvoiceCollection()->getLastItem();
            $trasport['amount']=$invoice->getBaseGrandTotal();

            $order->setFault($trasport['amount']);
            $this->_log("  order: ".$order->getId()." real ".$order->getRealOrderId()." invoice ".$trasport['amount']." status ".$order->getStatus());

            $order->setStatusRd(1);
        }
        if($order->getStatus()=='delivery_return'){
            $r=$rsrv->getInfo($order->getRealOrderId());
            $trasport['returndate']=$r->rd;


            $order->setReturndate($trasport['returndate']);
            $this->_log("  order: ".$order->getId()." real ".$order->getRealOrderId()." rd ".$trasport['returndate']." status ".$order->getStatus());

            $this->_log("Processed ".$order->getRealOrderId()." ST ".$order->getStatus());

            $order->setStatusDr(1);
        }
        if($order->getstatus()=='successful_return'){

            $amt=$order->getBaseTotalRefunded();
            $trasport['amount']=$amt;

            $order->setAmt($trasport['amount']);
            $this->_log("  order: ".$order->getId()." real ".$order->getRealOrderId()." invoice ".$trasport['amount']." status ".$order->getStatus());
            $order->setStatusSr(1);
            $this->_log("Processed ".$order->getRealOrderId()." ST ".$order->getStatus());
        }
        if($order->getStatus()=='waiting_review') {


$urls=[];
            foreach($order->getAllItems() as $item){
                if(strpos($item->getSku(),'res-')===0){
                    continue;

                }
                if($item->getProductType()==DepType::TYPE_CODE){
                    continue;
                }
             $urls[]=   $item->getProduct()->getProductUrl();

            }

            $trasport['urls']=implode("\n",$urls);
            $r=$rsrv->getInfo($order->getRealOrderId());
            $trasport['returndate']=$r->rd;

    //$order->setData('returndate',$trasport['returndate']);

            $this->_log("  order: ".$order->getId()." real ".$order->getRealOrderId()." status ".$order->getStatus());
            $this->_log("Processedx".$order->getRealOrderId()." ST ".$trasport['status']." dt ".$trasport['returndate']);
           /// $this->_log(print_r(array_keys($trasport),1));
            $order->setUrls($trasport['urls']);
            $order->setStatusWr(1);

        }
        ///$observer->getEvent()->setTransport($trasport);


        /*
        $shipment=$transport['shipment'];
        $order=$trasport['order'];
        $sender='';
        list($car,$method)=$order->getShippingMethod();
        if($method=='pick'){
          ///  $trasport['comment']='';
        }
        */

        return $this;
    }
}