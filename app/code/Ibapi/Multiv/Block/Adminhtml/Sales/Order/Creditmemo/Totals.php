<?php


namespace Ibapi\Multiv\Block\Adminhtml\Sales\Order\Creditmemo;

class Totals extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Sales\Model\Order\Creditmemo
     */
    protected $_creditmemo = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCreditmemo()
    {
        return $this->getParentBlock()->getCreditmemo();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getCreditmemo();
        $this->getSource();
        $dis=$this->getNameInLayout()=='viscount'?$this->getSource()->getViscount():$this->getNameInLayout()=='tiscount'? $this->getSource()->getTiscount():$this->getSource()->getVipdiscount();
     if($this->getNameInLayout()=='tiscount'){
            $txt=__('Taxes');
            $dis=($this->getCreditmemo()->getBaseGrandTotal())*0.21/1.21;
        }else if($this->getNameInLayout()=='viscount'){

            $dis=$this->getCreditmemo()->getViscount();
            $txt=__('Money Box');

           /// file_put_contents('orderblock.txt',"invis $dis nm ".$this->getNameInLayout()." \n",FILE_APPEND);
        }else if($this->getNameInLayout()=='vipdiscount'){

            $txt=__('Vip Discount');
            $dis=$this->getCreditmemo()->getVipdiscount();
        }

        $total = new \Magento\Framework\DataObject(
            [
                'code' =>  $this->getNameInLayout(),
                'strong' => false,
                'value' => $dis,
                'label' =>$txt//$this->getNameInLayout()=='viscount'?__('Point Discount'):$this->getNameInLayout()=='tiscount'? __('Taxes'):__('Vip Discount'),
            ]
        );

        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
