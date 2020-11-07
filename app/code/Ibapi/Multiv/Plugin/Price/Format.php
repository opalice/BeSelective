<?php
namespace Ibapi\Multiv\Plugin\Price;

class Format
{
    public function afterGetPriceFormat(\Magento\Framework\Locale\Format $ssubject, $result) {
        $result['precision'] = 2;
        $result['requiredPrecision'] = 2;

        return $result;
    }
}