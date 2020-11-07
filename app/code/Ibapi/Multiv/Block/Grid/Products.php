<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Multiv\Block\Grid;

use \Magento\Framework\App\ObjectManager;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactoryInterface;

/**
 * Sales order history block
 *
 * @api
 * @since 100.0.2
 */
class Products extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'productlist.phtml';

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_orderConfig;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $products;

    /**
     * @var CollectionFactoryInterface
     */
    private $productCollectionFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $orderCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Products'));
    }

    /**
     * @return CollectionFactoryInterface
     *
     * @deprecated 100.1.1
     */
    private function getOrderCollectionFactory()
    {
        if ($this->orderCollectionFactory === null) {
            $this->orderCollectionFactory = ObjectManager::getInstance()->get(CollectionFactoryInterface::class);
        }
        return $this->orderCollectionFactory;
    }

    /**
     * @return bool|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProducts()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
///        $this->products->addAttributeToFilter($attribute)
        if (!$this->products) {
            $this->products = $this->productCollectionFactory->create()->addFieldToSelect(
                '*'
                )->addAttributeToSelect('rent')->addAttributeToSelect('deposit')
            ->addAttributeToFilter(
                'uid',
                  $customerId
            )->setOrder(
                'created_at',
                'desc'
            );
        }
        return $this->products;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getProducts()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'multiv.productlist.pager'
            )->setCollection(
                $this->getProducts()
            );
            $this->setChild('pager', $pager);
            $this->getProducts()->load();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param object $order
     * @return string
     */
    public function getViewUrl($product)
    {
        return $this->getUrl('catalog/product/view', ['id' => $product->getId()]);
    }


    /**
     * @param object $order
     * @return string
     */
    public function getEditUrl($product=null)
    {
        if(!$product){
            return $this->getUrl('multiv/index/product');
            
        }
        
        return $this->getUrl('multiv/index/product', ['id' => $product->getId()]);
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }
}
