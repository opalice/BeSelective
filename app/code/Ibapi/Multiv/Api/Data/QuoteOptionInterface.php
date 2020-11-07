<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Multiv\Api\Data;

/**
 * Downloadable Option
 * @api
 */
interface QuoteOptionInterface
{
    const OPTION_KEY= 'rental_option';

    /**
     * Returns the list of downloadable links
     *
     * @return string
     */
    public function getRentalDates();

    /**
     * Sets the list of downloadable links
     *
     * @param string $info
     * @return $this
     */
    public function setRentalDates($info);
    
    public function getLastRequest();
    
    public function setLastRequest($dt,$pid,$depo);
}
