<?php 
namespace Ibapi\Multiv\Block\Grid;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Reserve extends \Magento\Framework\View\Element\Template
{ 
    protected $_rFactory;
    protected $helper;
    protected  $pi;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ibapi\Multiv\Model\ReserveFactory $rFactory,
        \Ibapi\Multiv\Helper\Data $helper,
        array $data = []
        ) {
        $this->helper=$helper;
            $this->_rFactory = $rFactory;
            parent::__construct($context, $data);
            //get collection of data
            $collection = $rFactory->create()->getCollection();
            /**@var $collection \Ibapi\Multiv\Model\ResourceModel\Reserve\Collection */

            $cid=$this->helper->getCustomerId();
            $collection->addFieldToFilter('o',$cid)->addOrder('sd','DESC');
            
            $this->setCollection($collection);
            $this->pageConfig->getTitle()->set(__('My Reservations'));
    }
   
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getCollection()) {
            // create pager block for collection
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'webkul.grid.record.pager'
                )->setCollection(
                    $this->getCollection() // assign collection to pager
                    );
                $this->setChild('pager', $pager);// set pager block in layout
        }
        return $this;
    }
    
    /**
     * @return string
     */
    // method for get pager html
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}