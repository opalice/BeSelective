<?php
namespace Ibapi\Multiv\Model;



use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;

class Discount extends  \Magento\SalesRule\Model\Quote\Discount
{

     protected function distributeDiscount(\Magento\Quote\Model\Quote\Item\AbstractItem $item)
    {

        if(strpos($item->getSku(),'res-')!==0){
            return parent::distributeDiscount($item);
        }

        $parentBaseRowTotal = $item->getBaseRowTotal();
        $keys = [
            'discount_amount',
            'base_discount_amount',
            'original_discount_amount',
            'base_original_discount_amount',
        ];
        $roundingDelta = [];
        foreach ($keys as $key) {
            //Initialize the rounding delta to a tiny number to avoid floating point precision problem
            $roundingDelta[$key] = 0.0000001;
        }


        foreach ($item->getChildren() as $child) {


            $ratio =      in_array($child->getProduct()->getTypeId(),[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])?1:0;  ///$parentBaseRowTotal != 0 ? $child->getBaseRowTotal() / $parentBaseRowTotal : 0;


            foreach ($keys as $key) {
                if (!$item->hasData($key)) {
                    continue;
                }

                $value = $item->getData($key) * $ratio;


                file_put_contents('bundled.txt',"key $key  ".$child->getSku()." val $value \n",FILE_APPEND);
                $roundedValue = $this->priceCurrency->round($value + $roundingDelta[$key]);
                $roundingDelta[$key] += $value - $roundedValue;
                $child->setData($key, $roundedValue);
            }
        }

        foreach ($keys as $key) {
            $item->setData($key, 0);
        }
        return $this;
    }


}