<?php
namespace Ibapi\Multiv\Model\Total;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Ibapi\Multiv\Model\Type\SubType;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;

class Viscount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
   /**
    * @var \Magento\Framework\Pricing\PriceCurrencyInterface
    */
   protected $_priceCurrency;
   protected $_discount;
   protected $_repo;
   protected  $helper;
   /**
    * Custom constructor.
    * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    */
   public function __construct(
       \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
       ProductRepositoryInterface $productRepo,
       \Ibapi\Multiv\Helper\Discount $helper
   ){
       $this->helper=$helper;
       $this->setCode('viscount');
       $this->_repo=$productRepo;
       $this->_priceCurrency = $priceCurrency;
   }
   /**
    * @param \Magento\Quote\Model\Quote $quote
    * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
    * @param \Magento\Quote\Model\Quote\Address\Total $total
    * @return $this|bool
    */
   public function collect1(
       \Magento\Quote\Model\Quote $quote,
       \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
       \Magento\Quote\Model\Quote\Address\Total $total
   )
   {}
   
   public function collect(Quote $quote, 
       \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
       \Magento\Quote\Model\Quote\Address\Total $total
       
       )

   {
       parent::collect($quote, $shippingAssignment, $total);
       
///        $this->clearValues($total);

   ///     $quote->setBaseViscount(0);
      /// $quote->setViscount(0);

       $items = $shippingAssignment->getItems();
       if (!count($items)) {
           return $this;
       }
       $t=$total->getBaseGrandTotal();
       $bd = $this->helper->getViscount($quote)*-1;
       
       $d=$this->_priceCurrency->convert($bd);
       
       $total->setTotalAmount($this->getCode(), $d);
       $total->setBaseTotalAmount($this->getCode(), $bd);
       $total->setBaseViscount($bd);
       $total->setViscount($d);

       $total->setGrandTotal($t+$d);
    
       $total->setBaseGrandTotal($t+$bd);
       
       $quote->setBaseViscount($bd);
       $quote->setViscount($d);


       $quote->setTotalAmount($this->getCode(), $d);
       $quote->setBaseTotalAmount($this->getCode(), $bd);


       $quote->setGrandTotal($t + $d);
       $quote->setBaseGrandTotal($t + $bd);

       file_put_contents('quotetotal.txt',"vis t $t bd $bd \n",FILE_APPEND);



//       $quote->setGrandTotal($t+$d);
  //     $quote->setBaseGrandTotal($this->getCode(), $t+$bd);
       return $this;
   }
   
   protected function clearValues(Total $total)
   {
      // $total->setTotalAmount('subtotal', 0);
       ///$total->setBaseTotalAmount('subtotal', 0);
//       $total->setTotalAmount('tax', 0);
       $total->setTotalAmount('viscount', 0);
       $total->setBaseTotalAmount('viscount', 0);
   //    $total->setBaseTotalAmount('tax', 0);
       $total->setTotalAmount('viscount_tax_compensation', 0);
       $total->setBaseTotalAmount('viscount_tax_compensation', 0);
     ///  $total->setTotalAmount('shipping_discount_tax_compensation', 0);
     //  $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
 //      $total->setSubtotalInclTax(0);
   //    $total->setBaseSubtotalInclTax(0);
   }
   

   
   public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
   {
       $baseDiscount = $this->helper->getViscount($quote)*-1;
       
       return [
           'code' => $this->getCode(),
           'title' => $this->getLabel(),
           'value' => $baseDiscount
       ];
   }
   /**
    * @return \Magento\Framework\Phrase
    */
   public function getLabel()
   {
       return __('Money Box');
   }
   
}
