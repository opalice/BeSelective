<?php


namespace Ibapi\Multiv\Block\Adminhtml\Sales;

class Totals extends \Magento\Framework\View\Element\Template
{

   
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $_currency;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Directory\Model\Currency $currency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_currency = $currency;
    }

    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();



        $dis=$this->getNameInLayout()=='viscount'?$this->getOrder()->getViscount()  :$this->getNameInLayout()=='tiscount'? $this->getSource()->getTiscount(): $this->getSource()->getVipdiscount();
///        $cls=get_class('source.txt',get_class($this->getSource())." dis $dis \n",FILE_APPEND);

        if($this->getNameInLayout()=='tiscount'){
            $txt=__('Taxes');
            $dis=($this->getSource()->getBaseGrandTotal()-$this->getSource()->getViscount())*0.21/1.21;
        }else if($this->getNameInLayout()=='viscount'){

            $dis=$this->getSource()->getViscount();
            $txt=__('Money Box');

            file_put_contents('orderblock.txt',"invis $dis nm ".$this->getNameInLayout()." \n",FILE_APPEND);
        }else if($this->getNameInLayout()=='vipdiscount'){

            $txt=__('Vip Discount');
            $dis=$this->getSource()->getVipdiscount();
        }

        file_put_contents('orderblock.txt',"dis $dis nm ".$this->getNameInLayout()." \n",FILE_APPEND);
        if(!$dis) {
///            return $this;
        }
        $total = new \Magento\Framework\DataObject(
            [
                'code' => $this->getNameInLayout(),
                'value' => $dis,
                'label' =>$txt,
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
