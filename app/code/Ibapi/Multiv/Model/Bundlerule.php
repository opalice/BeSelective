<?php 
namespace Ibapi\Multiv\Model;

use Magento\Framework\Event\ObserverInterface;
use Ibapi\Multiv\Model\Type\SubType;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;

class Bundlerule implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**@var $r \Magento\SalesRule\Model\Rule  */
        /**@var $item \Magento\Quote\Model\Quote\Item */
        /**@var $data         \Magento\SalesRule\Model\Rule\Action\Discount\Data */

        $item=$observer->getEvent()->getItem();
        $data=$observer->getEvent()->getResult();
        $r=$observer->getEvent()->getRule();
        $bun=$r->getCouponCode();

        if(strpos($item->getSku(),'res-')!==0){
            return $this;
            
        }
        $amount=0;
        $sk='';
        foreach ($item->getChildren() as $it){
            if(in_array($it->getProduct()->getTypeId(),[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])){
                $sk=$it->getProduct()->getSku();
                $amount=$it->getCustomPrice();


                break;
            }
            
        }
        if(!$amount){
            return;
        }
        $f=$r->getDiscountAmount();
        $amount=$f*$amount/100;
        if(stripos($bun,'BES')===0){

            $data->setAmount($amount);
            $data->setBaseAmount($amount);
            $data->setBaseOriginalAmount($amount);
            $data->setOriginalAmount($amount);
            
        }
        
        return $this;
    }
}