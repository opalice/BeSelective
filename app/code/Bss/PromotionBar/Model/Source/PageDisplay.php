<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_PromotionBar
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\PromotionBar\Model\Source;

class PageDisplay implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Page values
     */
    const HOME_PAGE = 1;
    const CATEGORY_PAGE = 2;
    const PRODUCT_PAGE = 3;
    const CART_PAGE = 4;
    const CHECKOUT_PAGE = 5;
    const ALL_OTHER_PAGE = 6;

    /**
     * Get Option Array
     *
     * @return array
     */
    public function getOptionArray()
    {
        $optionArray = ['' => ' '];
        foreach ($this->toOptionArray() as $option) {
            $optionArray[$option['value']] = $option['label'];
        }
        return $optionArray;
    }

    /**
     * To Option Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::HOME_PAGE,  'label' => __('Home Page')],
            ['value' => self::CATEGORY_PAGE,  'label' => __('Catagory Page')],
            ['value' => self::PRODUCT_PAGE,  'label' => __('Product Page')],
            ['value' => self::CART_PAGE,  'label' => __('Cart Page')],
            ['value' => self::CHECKOUT_PAGE,  'label' => __('Checkout Page')],
            ['value' => self::ALL_OTHER_PAGE,  'label' => __('All Other Pages')]
        ];
    }
}
