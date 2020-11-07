<?php

namespace Ibapi\Multiv\Model\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Prince\Extrafee\Helper\Data as FeeHelper;

class Tiscount extends \Magento\Sales\Model\Order\Total\AbstractTotal
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
        
        
        
        
        
        
        
        $invoice->setTiscount(0);
        $invoice->setBaseTiscount(0);
        $amount = $invoice->getOrder()->getTiscount();
        $invoice->setTiscount($amount);
        $amount = $invoice->getOrder()->getBaseTiscount();
        $invoice->setBaseTiscount($amount);
        ///$t=$invoice->getGrandTotal() + $invoice->getTiscount();
        ///$bt=$invoice->getBaseGrandTotal() + $invoice->getTiscount();
        ///$invoice->setGrandTotal($t);
        //$invoice->setBaseGrandTotal($bt);
        
        
        
        
        
        
        
        

        return $this;
    }
}
