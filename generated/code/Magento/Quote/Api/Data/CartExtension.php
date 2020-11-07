<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\CartInterface
 */
class CartExtension extends \Magento\Framework\Api\AbstractSimpleObject implements CartExtensionInterface
{
    /**
     * @return \Magento\Quote\Api\Data\ShippingAssignmentInterface[]|null
     */
    public function getShippingAssignments()
    {
        return $this->_get('shipping_assignments');
    }

    /**
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface[] $shippingAssignments
     * @return $this
     */
    public function setShippingAssignments($shippingAssignments)
    {
        $this->setData('shipping_assignments', $shippingAssignments);
        return $this;
    }

    /**
     * @return \Ibapi\Multiv\Api\Data\QuoteOptionInterface|null
     */
    public function getRentalData()
    {
        return $this->_get('rental_data');
    }

    /**
     * @param \Ibapi\Multiv\Api\Data\QuoteOptionInterface $rentalData
     * @return $this
     */
    public function setRentalData(\Ibapi\Multiv\Api\Data\QuoteOptionInterface $rentalData)
    {
        $this->setData('rental_data', $rentalData);
        return $this;
    }
}
