<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at thisURL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_SizeChart
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\PromotionBar\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Bss\PromotionBar\Model\Source\Position;

class EditLayout implements ObserverInterface
{

    protected $layoutFactory;

    protected $helper;

    public function __construct(
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Bss\PromotionBar\Helper\Data $helper
    ) {
        $this->layoutFactory = $layoutFactory;
        $this->helper = $helper;
    }

    /**
     *Check tab information
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $position = 0;
        if ($observer->getElementName() == 'promotionbar_home_page_top') {
            $position = 1;
        }
        if ($observer->getElementName() == 'promotionbar_home_menu_top') {
            $position = 2;
        }
        if ($observer->getElementName() == 'promotionbar_home_menu_bottom') {
            $position = 3;
        }
        if ($observer->getElementName() == 'promotionbar_home_content_top') {
            $position = 4;
        }
        if ($observer->getElementName() == 'promotionbar_home_page_bottom') {
            $position = 5;
        }
        if ($observer->getElementName() == 'promotionbar_category_page_top') {
            $position = 1;
        }
        if ($observer->getElementName() == 'promotionbar_category_menu_top') {
            $position = 2;
        }
        if ($observer->getElementName() == 'promotionbar_category_menu_bottom') {
            $position = 3;
        }
        if ($observer->getElementName() == 'promotionbar_category_content_top') {
            $position = 4;
        }
        if ($observer->getElementName() == 'promotionbar_category_page_bottom') {
            $position = 5;
        }
        if ($observer->getElementName() == 'promotionbar_product_page_top') {
            $position = 1;
        }
        if ($observer->getElementName() == 'promotionbar_product_menu_top') {
            $position = 2;
        }
        if ($observer->getElementName() == 'promotionbar_product_menu_bottom') {
            $position = 3;
        }
        if ($observer->getElementName() == 'promotionbar_product_content_top') {
            $position = 4;
        }
        if ($observer->getElementName() == 'promotionbar_product_page_bottom') {
            $position = 5;
        }
        if ($observer->getElementName() == 'promotionbar_checkout_page_top') {
            $position = 1;
        }
        if ($observer->getElementName() == 'promotionbar_checkout_content_top') {
            $position = 4;
        }
        if ($observer->getElementName() == 'promotionbar_checkout_page_bottom') {
            $position = 5;
        }
        if ($observer->getElementName() == 'promotionbar_cart_page_top') {
            $position = 1;
        }
        if ($observer->getElementName() == 'promotionbar_cart_menu_top') {
            $position = 2;
        }
        if ($observer->getElementName() == 'promotionbar_cart_page_top') {
            $position = 1;
        }
        if ($observer->getElementName() == 'promotionbar_cart_menu_bottom') {
            $position = 3;
        }
        if ($observer->getElementName() == 'promotionbar_cart_content_top') {
            $position = 4;
        }
        if ($observer->getElementName() == 'promotionbar_cart_page_bottom') {
            $position = 5;
        }
        if ($observer->getElementName() == 'promotionbar_default_page_top') {
            $position = 1;
        }
        if ($observer->getElementName() == 'promotionbar_default_menu_top') {
            $position = 2;
        }
        if ($observer->getElementName() == 'promotionbar_default_menu_bottom') {
            $position = 3;
        }
        if ($observer->getElementName() == 'promotionbar_default_content_top') {
            $position = 4;
        }
        if ($observer->getElementName() == 'promotionbar_default_page_bottom') {
            $position = 5;
        }
//        if ($observer->getElementName() == 'promotionbar_home_menu_top') {
        if ($position) {

                $allPosition = [Position::PAGE_TOP,Position::MENU_TOP,Position::MENU_BOTTOM,Position::CONTENT_TOP,Position::PAGE_BOTTOM];
                $blockPromotionBar = $observer->getLayout()->getBlock($observer->getElementName());
                $page = $blockPromotionBar->getBlockType();
//                $position = 1;//$blockPromotionBar->getBlockPosition();

                $storeViewId = $blockPromotionBar->getStoreId();
                $productId = $blockPromotionBar->getProductId();
                $categoryId = $blockPromotionBar->getCategoryId();

                $customerGroup = $this->helper->getCustomerGroupId();

                $layout = $this->layoutFactory->create();
//            foreach($allPosition as $posDisplay) {
                $block = $layout->createBlock(\Bss\PromotionBar\Block\Ajax::class);
                $block->setBlockPage($page);
                $block->setBlockPosition($position);
                $block->setStoreViewId($storeViewId);
                $block->setCustomerGroupId($customerGroup);
                $block->setProductId($productId);
                $block->setCategoryId($categoryId);
                $block->setTemplate('Bss_PromotionBar::ajax/promotionbar.phtml');
                $html = $observer->getTransport()->getOutput() .  $block->toHtml();
                $observer->getTransport()->setOutput($html);

        }


    }
}
