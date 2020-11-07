<?php

namespace Ibapi\Multiv\Model;

use Magento\Framework\Event\ObserverInterface;

class SubmitOrder implements ObserverInterface
{
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        
        $quote = $observer->getQuote();
        $d = $quote->getVipdiscount();
        $b = $quote->getBaseVipdiscount();
       // if (!$d || !$b) {
           /// return $this;
        ///}
        
        $order = $observer->getOrder();
        $order->setData('vipdiscount', $d);
        $order->setData('base_vipdiscount', $b);
        $d = $quote->getViscount();
        $b = $quote->getBaseViscount();
        $order->setData('viscount', $d);
        $order->setData('base_viscount', $b);
        $d = $quote->getTiscount();
        $b = $quote->getBaseTiscount();
        $order->setData('tiscount', $d);
        $order->setData('base_tiscount', $b);
        return $this;
    }
}
