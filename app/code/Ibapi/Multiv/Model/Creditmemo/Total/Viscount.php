<?php

namespace Ibapi\Multiv\Model\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Prince\Extrafee\Helper\Data as FeeHelper;

class Viscount extends \Magento\Sales\Model\Order\Total\AbstractTotal
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
        
        
        
        
        
        
        
        $invoice->setViscount(0);
        $invoice->setBaseViscount(0);
        $amount = $invoice->getOrder()->getViscount();
        $invoice->setViscount($amount);
        $amount = $invoice->getOrder()->getBaseViscount();
        $invoice->setBaseViscount($amount);
        $t=$invoice->getGrandTotal() + $invoice->getViscount();
        $bt=$invoice->getBaseGrandTotal() + $invoice->getViscount();
        $invoice->setGrandTotal($t);
        $invoice->setBaseGrandTotal($bt);
        
        
        
        
        
        
        
        

        return $this;
    }
}
