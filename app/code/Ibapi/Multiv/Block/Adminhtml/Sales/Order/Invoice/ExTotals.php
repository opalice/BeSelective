<?php


namespace Ibapi\Multiv\Block\Adminhtml\Sales\Order\Invoice;

class ExTotals extends \Magento\Framework\View\Element\Template
{


    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    protected $_invoice = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getInvoice();
        $this->getSource();



            $txt=__('Ex Tax');
            $dis=($this->getSource()->getBaseGrandTotal()-$this->getSource()->getViscount())/1.21;


        $total = new \Magento\Framework\DataObject(
            [
                'code' =>  $this->getNameInLayout(),
                'strong' => false,
                'value' => $dis,
                'label' =>$txt//$this->getNameInLayout()=='viscount'?__('Point Discount'): $this->getNameInLayout()=='tiscount'? __('Taxes'):__('Vip Discount'),
            ]
            );

        

        $this->getParentBlock()->addTotal($total);
        return $this;
    }
}
