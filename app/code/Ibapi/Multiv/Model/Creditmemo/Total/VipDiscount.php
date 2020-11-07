<?php

namespace Ibapi\Multiv\Model\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Prince\Extrafee\Helper\Data as FeeHelper;

class VipDiscount extends \Magento\Sales\Model\Order\Total\AbstractTotal
{
    protected $helper;

    public function __construct(\Ibapi\Multiv\Helper\Data $helper, array $data = [])
    {
        parent::__construct($data);
        $this->helper = $helper;
    }

    /**
     * @param Creditmemo $creditmemo
     * @return $this
     */
    public function collect(Creditmemo $invoice)
    {
        
        
        
        
        
        
        
        $invoice->setVipdiscount(0);
        $invoice->setBaseVipdiscount(0);
        $amount = $invoice->getOrder()->getVipdiscount();
        $invoice->setVipdiscount($amount);
        $amount = $invoice->getOrder()->getBaseVipdiscount();
        $invoice->setBaseVipdiscount($amount);
        $t=$invoice->getGrandTotal() + $invoice->getVipdiscount();
        $bt=$invoice->getBaseGrandTotal() + $invoice->getVipdiscount();
        $invoice->setGrandTotal($t);
        $invoice->setBaseGrandTotal($bt);
        
        
        
        
        
        
        
        

        return $this;
    }
}
