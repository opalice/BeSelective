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
namespace Bss\PromotionBar\Controller\Render;

use Magento\Framework\Json\Helper\Data as JsonHelper;
use Bss\PromotionBar\Model\Source\Position;
 
class PromotionBar extends \Magento\Framework\App\Action\Action
{
    /**
     * Result Raw Factory
     *
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * Layout Factory
     *
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * Helper
     *
     * @var \Bss\PromotionBar\Helper\Data
     */
    protected $helper;

    /**
     * Helper
     *
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Bss\PromotionBar\Helper\Data $helper
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param JsonHelper $jsonHelper,
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Bss\PromotionBar\Helper\Data $helper,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        JsonHelper $jsonHelper,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->helper = $helper;
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $allPosition = [Position::PAGE_TOP,Position::MENU_TOP,Position::MENU_BOTTOM,Position::CONTENT_TOP,Position::PAGE_BOTTOM];
            $pageDisplay = $this->getRequest()->getPost('pageDisplay');
            $storeViewId = $this->getRequest()->getPost('storeViewID');
            $productId = $this->getRequest()->getPost('productId');
            $categoryId = $this->getRequest()->getPost('categoryId');
            $result = [];
            $customerGroup = $this->helper->getCustomerGroupId();

            /** @var \Magento\Framework\View\Layout $layout */
            $layout = $this->layoutFactory->create();

            foreach($allPosition as $posDisplay) {
                $block = $layout->createBlock(\Bss\PromotionBar\Block\Ajax::class);
                $block->setBlockPage($pageDisplay);
                $block->setBlockPosition($posDisplay);
                $block->setStoreViewId($storeViewId);
                $block->setCustomerGroupId($customerGroup);
                $block->setProductId($productId);
                $block->setCategoryId($categoryId);

                $block->setTemplate('Bss_PromotionBar::ajax/promotionbar.phtml');

                $result[] = ['html' => $block->toHtml(), 'targetDiv' => ".promotionbar_position_".$pageDisplay.$posDisplay];
            }

            return $this->getResponse()->setBody($this->jsonHelper->jsonEncode($result));
        } else {
            $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('no-route');
        }
    }

}
