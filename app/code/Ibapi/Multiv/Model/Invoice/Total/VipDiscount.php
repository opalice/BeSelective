<?php


namespace Ibapi\Multiv\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class VipDiscount extends \Magento\Sales\Model\Order\Total\AbstractTotal
{
    /**
     * @param Invoice $invoice
     * @return $this
     */
    public function collect1(Invoice $invoice)
    {
        $invoice->setVipdiscount(0);
        $invoice->setBaseVipdiscount(0);
        $amount = $invoice->getOrder()->getVipdiscount();
        file_put_contents('inv.txt', "model amount $amount\n",FILE_APPEND);
        $invoice->setVipdiscount($amount);
        $amount = $invoice->getOrder()->getBaseVipdiscount();
        $invoice->setBaseVipdiscount($amount);
        $t=$invoice->getGrandTotal()+$invoice->getVipdiscount();
        $bt=$invoice->getBaseGrandTotal() + $invoice->getVipdiscount();
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
        $invoice->setVipdiscount(0);
        $invoice->setBaseVipdiscount(0);
        $totalDiscountAmount = 0;
        $baseTotalDiscountAmount = 0;

        $addShippingDiscount = true;
        foreach ($invoice->getOrder()->getInvoiceCollection() as $previousInvoice) {
            if ($previousInvoice->getVipdiscount()) {
                $addShippingDiscount = false;
            }
        }
        
        if ($addShippingDiscount) {
            $totalDiscountAmount =  $invoice->getOrder()->getVipdiscount();
            $baseTotalDiscountAmount =
            $invoice->getOrder()->getBaseVipdiscount();
        
            
        /* @var $invoice \Magento\Sales\Model\Order\Invoice */
            $ig=$invoice->getBaseGrandTotal();
            $i=$invoice->getGrandTotal();
            
            $invoice->setVipdiscount($totalDiscountAmount);
        $invoice->setBaseVipdiscount($baseTotalDiscountAmount);
     ///   $invoice->setBaseDiscountTaxCompensationAmount()
      ///  $t=time();
      ///  file_put_contents('invoicevis.txt', "diss $t $totalDiscountAmount bvs $baseTotalDiscountAmount i $i ig $ig\n",FILE_APPEND);
        $invoice->setGrandTotal($i + $totalDiscountAmount);
        $invoice->setBaseGrandTotal($ig + $baseTotalDiscountAmount);
        
        }
        return $this;
    }
    
    
}
