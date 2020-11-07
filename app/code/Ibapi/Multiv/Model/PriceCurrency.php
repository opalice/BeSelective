<?php
namespace Ibapi\Multiv\Model;


class PriceCurrency extends \Magento\Directory\Model\PriceCurrency
{


    const DEFAULT_PRECISION = 1;

    public function round($price)
    {
   ///     file_put_contents('taxcalc.txt',"price $price\n ",FILE_APPEND);

        return round($price, self::DEFAULT_PRECISION);
    }
    public function convertAndRound($amount, $scope = null, $currency = null, $precision = self::DEFAULT_PRECISION)
    {

        $precision=1;
        return parent::convertAndRound($amount, $scope, $currency, $precision);
    }


}