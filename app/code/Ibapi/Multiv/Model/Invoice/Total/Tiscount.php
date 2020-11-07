<?php


namespace Ibapi\Multiv\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class Tiscount extends \Magento\Sales\Model\Order\Total\AbstractTotal
{
    /**
     * @param Invoice $invoice
     * @return $this
     */
    public function collect1(Invoice $invoice)
    {
        $invoice->setViscount(0);
        $invoice->setBaseViscount(0);
        $amount = $invoice->getOrder()->getViscount();
        ///file_put_contents('inv.txt', "model amount $amount\n",FILE_APPEND);
        $invoice->setViscount($amount);
        $amount = $invoice->getOrder()->getBaseViscount();
        $invoice->setBaseViscount($amount);
        $t=$invoice->getGrandTotal()+$invoice->getViscount();
        $bt=$invoice->getBaseGrandTotal() + $invoice->getViscount();
        $invoice->setGrandTotal($t);
        $invoice->setBaseGrandTotal($bt);
        return $this;
    }
    
    
    /**
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {


        $invoice->setTiscount(0);
        $invoice->setBaseTiscount(0);
        $totalDiscountAmount = 0;
        $baseTotalDiscountAmount = 0;

        $addShippingDiscount = true;
        foreach ($invoice->getOrder()->getInvoiceCollection() as $previousInvoice) {
            if ($previousInvoice->getTiscount()) {
                $addShippingDiscount = false;
            }
        }
        
        if ($addShippingDiscount) {
            $totalDiscountAmount =  $invoice->getOrder()->getTiscount();
            $baseTotalDiscountAmount =
            $invoice->getOrder()->getBaseTiscount();
        
        /* @var $invoice \Magento\Sales\Model\Order\Invoice */

           // $ig=$invoice->getBaseGrandTotal();
            //$i=$invoice->getGrandTotal();
         $invoice->setTiscount($totalDiscountAmount);
         $invoice->setBaseTiscount($baseTotalDiscountAmount);
       /// $t=time();
      ///  file_put_contents('invoicevis.txt', "viss $t $totalDiscountAmount bvs $baseTotalDiscountAmount i $i ig $ig\n",FILE_APPEND);
        ///$invoice->setGrandTotal($i + $totalDiscountAmount);
        ///$invoice->setBaseGrandTotal($ig + $baseTotalDiscountAmount);
        
        }
     //   $amount = $invoice->getOrder()->getViscount();
   //     $orderid=$invoice->getOrder()->getId();
///        $gt=$invoice->getBaseGrandTotal();
        return $this;
    }
    
    
}
