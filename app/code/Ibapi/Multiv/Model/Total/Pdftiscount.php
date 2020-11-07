<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Multiv\Model\Total;

class Pdftiscount extends \Magento\Sales\Model\Order\Pdf\Total\DefaultTotal
{

    /**
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @param \Magento\Tax\Model\Calculation $taxCalculation
     * @param \Magento\Tax\Model\ResourceModel\Sales\Order\Tax\CollectionFactory $ordersFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Tax\Helper\Data $taxHelper,
        \Magento\Tax\Model\Calculation $taxCalculation,
        \Magento\Tax\Model\ResourceModel\Sales\Order\Tax\CollectionFactory $ordersFactory,
        array $data = []
    ) {
        parent::__construct($taxHelper, $taxCalculation, $ordersFactory, $data);
    }

    /**
     * Check if tax amount should be included to grandtotal block
     * array(
     *  $index => array(
     *      'amount'   => $amount,
     *      'label'    => $label,
     *      'font_size'=> $font_size
     *  )
     * )
     * @return array
     */
    public function getTotalsForDisplay()
    {
        $store = $this->getOrder()->getStore();



        $amountInclTax = $this->getSource()->getBaseGrandTotal()-$this->getSource()->getViscount();
///        file_put_contents('pdfdisc.txt'," amount $amountInclTax inv ".$this->getSource()->getId()."\n",FILE_APPEND);

        $qb=$amountInclTax*0.21/1.21;

        $amount = $this->getOrder()->formatPriceTxt($qb);

///        $this->getAmount();
        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
                    $totals = [
                [
                    'amount' =>  $amount,
                    'label' => __('Taxes') . ':',
                    'font_size' => $fontSize,
            ]];






        return $totals;
    }
}
