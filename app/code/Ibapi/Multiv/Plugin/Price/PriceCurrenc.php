<?php
namespace  Ibapi\Multiv\Plugin\Price;

class PriceCurrenc
{
    public function beforeConvertAndRound(

      \Magento\Directory\Model\PriceCurrency  $subject,
        $amount,
        $scope = null,
        $currency = null,
        $precision = \Magento\Directory\Model\PriceCurrency::DEFAULT_PRECISION
    ) {
        return [$amount, $scope, $currency, 2];
    }

    public function beforeFormat(
        \Magento\Directory\Model\PriceCurrency $subject,
        $amount,
        $includeContainer = true,
        $precision = \Magento\Directory\Model\PriceCurrency::DEFAULT_PRECISION,
        $scope = null,
        $currency = null
    ) {
        $amount=round($amount,1);

        return [$amount, $includeContainer, 2, $scope, $currency];
    }
}