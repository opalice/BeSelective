<?php
namespace Ibapi\Multiv\Model\Quote\Address\Total;
class Vipdiscount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;
    
    /**
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency [description]
     */
    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
        ) {
            $this->_priceCurrency = $priceCurrency;
    }
    
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
        ) {
            parent::collect($quote, $shippingAssignment, $total);
            
            $customDiscount = -10;
            
            $total->addTotalAmount('vip_discount', $customDiscount);
            $total->addBaseTotalAmount('vip_discount', $customDiscount);
            $quote->setCustomDiscount($customDiscount);
            return $this;
            
    }


/**
 * Assign subtotal amount and label to address object
 *
 * @param \Magento\Quote\Model\Quote $quote
 * @param Address\Total $total
 * @return array
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
{
    return [
        'code' => 'vip_discount',
        'title' => $this->getLabel(),
        'value' => 10
    ];
}

/**
 * get label
 * @return string
 */
public function getLabel()
{
    return __('Vip  Discount');
}

}