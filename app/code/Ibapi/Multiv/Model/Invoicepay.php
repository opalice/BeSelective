<?php
namespace Ibapi\Multiv\Model;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
class Invoicepay implements ObserverInterface
{
    /**
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        
       /// file_put_contents('invoice.txt',"paid ".$invoice->getGrandTotal() );
        //$order = $invoice->getOrder();
       /// $order->setTotalPaid($order->getTotalPaid() + $invoice->getGrandTotal());
     ///   $order->setBaseTotalPaid($order->getBaseTotalPaid() + $invoice->getBaseGrandTotal());
    }
}