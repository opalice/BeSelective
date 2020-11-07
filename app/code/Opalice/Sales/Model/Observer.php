<?php

class Opalice_Sales_Model_Observer
{

    /**
     * Add vat_id in address
     *
     */
    public function addAdditionalDataToAddress(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $address = $event['address'];
        $orderData = $address->getOrder()->getData();
        $address->setData(
            array_merge(
                $address->getData(),
                array(
                    'vat_id' => $orderData['customer_taxvat'],
                )
        ));
    }
}