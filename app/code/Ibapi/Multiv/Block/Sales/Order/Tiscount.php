<?php 
namespace  Ibapi\Multiv\Block\Sales\Order;


class Tiscount extends \Magento\Framework\View\Element\Template
{
    /**
     * Tax configuration model
     *
     * @var \Magento\Tax\Model\Config
     */
    protected $_config;
    
    /**
     * @var Order
     */
    protected $_order;
    
    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Tax\Model\Config $taxConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
        ) {
            parent::__construct($context, $data);
    }
    
    /**
     * Check if we nedd display full tax total info
     *
     * @return bool
     */
    public function displayFullSummary()
    {
        return true;
    }
    
    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->_source;
    }
    public function getStore()
    {
        return $this->_order->getStore();
    }
    
    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }
    
    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }
    
    /**
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }
    
    /**
     * Initialize all order totals relates with tax
     *
     * @return \Magento\Tax\Block\Sales\Order\Tax
     */
    public function initTotals()
    {
        
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();
        
        $store = $this->getStore();
       $dis=$this->_source->getViscount();
       //$cls=file_put_contents('source.txt',get_class($this->_source)." dis $dis \n");

        $tx=($this->getSource()->getBaseGrandTotal()-$dis)*0.21/1.21;
        $total = new \Magento\Framework\DataObject(
            [
                'code' => 'tiscount',
                'strong' => false,
                'value' => $tx,
                'label' => __('Taxes'),
            ]
            );
        
///        $parent->addTotal($fee, 'vipdiscount');
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        
        return $this;
    }
    
}