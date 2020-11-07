<?php


namespace Ibapi\Multiv\Block\Adminhtml\Sales\Order\Invoice;

class Totals extends \Magento\Framework\View\Element\Template
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



            if($this->getNameInLayout()=='tiscount'){
            $txt=__('Taxes');
            $dis=($this->getInvoice()->getBaseGrandTotal()-$this->getInvoice()->getViscount())*0.21/1.21;
        }else if($this->getNameInLayout()=='viscount'){

            $dis=$this->getInvoice()->getViscount();
            $txt=__('Money Box');

        }else if($this->getNameInLayout()=='vipdiscount'){

            $txt=__('Vip Discount');
            $dis=$this->getInvoice()->getVipdiscount();
        }

        
        if(!$dis) {
            return $this;
        }
        $total = new \Magento\Framework\DataObject(
            [
                'code' =>  $this->getNameInLayout(),
                'strong' => false,
                'value' => $dis,
                'label' =>$txt//$this->getNameInLayout()=='viscount'?__('Point Discount'): $this->getNameInLayout()=='tiscount'? __('Taxes'):__('Vip Discount'),
            ]
            );
        
        

        $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        return $this;
    }
}
