<?php
namespace Ibapi\Multiv\Model\Total;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Ibapi\Multiv\Model\Type\SubType;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;

class Tiscount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
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
       $this->setCode('tiscount');
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
       return parent::collect($quote, $shippingAssignment, $total);


     //  $total->setTotalAmount($this->getCode(), 0);
     /// $total->setBaseTotalAmount($this->getCode(), 0);





       $items = $shippingAssignment->getItems();
       if (!count($items)) {
           return $this;
       }


       ///    $t=$total->getBaseGrandTotal();
       $bd = $this->helper->getTiscount($quote);
       
       $d=$this->_priceCurrency->convert($bd);
       
       //$total->setTotalAmount($this->getCode(), $d);
       ///$total->setBaseTotalAmount($this->getCode(), $bd);
       $total->setBaseTiscount($bd);
       $total->setTiscount($d);

       ///$total->setGrandTotal($t+$d);
    
      /// $total->setBaseGrandTotal($t+$bd);
       
       $quote->setBaseTiscount($bd);
       $quote->setTiscount($d);

     ///  file_put_contents('quotetotal.txt',"tis  bd $bd \n",FILE_APPEND);
       /*$quote->setTotalAmount($this->getCode(), $d);
       $quote->setBaseTotalAmount($this->getCode(), $bd);
       */
//       $quote->setGrandTotal($t+$d);
  //     $quote->setBaseGrandTotal($this->getCode(), $t+$bd);
       return $this;
   }
   
   protected function clearValues(Total $total)
   {
//       $total->setTotalAmount('tax', 0);
       $total->setTotalAmount('tiscount', 0);


       $total->setBaseTotalAmount('tiscount', 0);
   //    $total->setBaseTotalAmount('tax', 0);
       $total->setTotalAmount('tiscount_tax_compensation', 0);
       $total->setBaseTotalAmount('tiscount_tax_compensation', 0);
     ///  $total->setTotalAmount('shipping_discount_tax_compensation', 0);
     //  $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
 //      $total->setSubtotalInclTax(0);
   //    $total->setBaseSubtotalInclTax(0);

 /*
  *
  * $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
  */


   }
   

   
   public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
   {
       $baseDiscount = $this->helper->getTiscount($quote);
       
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
       return __('Taxes');
   }
   
}
