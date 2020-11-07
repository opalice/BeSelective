<?php
namespace Ibapi\Multiv\Plugin\Pricing\Render;

use Magento\Framework\Pricing\PriceCurrencyInterface;


class Amount
{
    public function beforeFormatCurrency(

        \Magento\Framework\Pricing\Render\Amount   $subject,
        $amount,
        $includeContainer = true,
        $precision = PriceCurrencyInterface::DEFAULT_PRECISION
    ) {
        $amount=round($amount,1);
        return [$amount, $includeContainer,2];
    }
}