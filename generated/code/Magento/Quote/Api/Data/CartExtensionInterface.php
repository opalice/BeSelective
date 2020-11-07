<?php
namespace Magento\Quote\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Quote\Api\Data\CartInterface
 */
interface CartExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Magento\Quote\Api\Data\ShippingAssignmentInterface[]|null
     */
    public function getShippingAssignments();

    /**
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface[] $shippingAssignments
     * @return $this
     */
    public function setShippingAssignments($shippingAssignments);

    /**
     * @return \Ibapi\Multiv\Api\Data\QuoteOptionInterface|null
     */
    public function getRentalData();

    /**
     * @param \Ibapi\Multiv\Api\Data\QuoteOptionInterface $rentalData
     * @return $this
     */
    public function setRentalData(\Ibapi\Multiv\Api\Data\QuoteOptionInterface $rentalData);
}
