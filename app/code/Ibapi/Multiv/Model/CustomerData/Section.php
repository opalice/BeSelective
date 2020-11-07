<?php
namespace  Ibapi\Multiv\Model\CustomerData;
use Ibapi\Multiv\Model\Type\AccessoryType;
use \Ibapi\Multiv\Model\Type\ClothType;

use Magento\Checkout\CustomerData\ItemPoolInterface;
use Magento\Customer\CustomerData\SectionSourceInterface;
use \Ibapi\Multiv\Model\Reserve;
use \Ibapi\Multiv\Model\ReserveFactory;
use Magento\Customer\Model\Session;
use Magento\Quote\Api\CartItemRepositoryInterface;

/**
 * Cart source
 */
class Section  implements SectionSourceInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $checkoutSession;
    
    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $checkoutCart;
    
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Url
     */
    protected $catalogUrl;
    
    /**
     * @var \Magento\Quote\Model\Quote|null
     */
    protected $quote = null;
    
    /**
     * @var \Magento\Checkout\Helper\Data
     */
    protected $checkoutHelper;
    
    /**
     * @var ItemPoolInterface
     */
    protected $itemPoolInterface;
    
    /**
     * @var int|float
     */
    protected $summeryCount;
    
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;
    
    protected  $rfact;
    /**
     * @var CartItemRepositoryInterface
     */
    private $cartItemRepository;

    /**
    * 
    * @param Session $checkoutSession
    * @param \Magento\Catalog\Model\ResourceModel\Url $catalogUrl
    * @param \Magento\Checkout\Model\Cart $checkoutCart
    * @param \Magento\Checkout\Helper\Data $checkoutHelper
    * @param ItemPoolInterface $itemPoolInterface
    * @param \Magento\Framework\View\LayoutInterface $layout
    * @param ReserveFactory $rfact
    * @param array $data
    */ 
    public function __construct(
        Session $checkoutSession,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Checkout\Model\Cart $checkoutCart,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        ItemPoolInterface $itemPoolInterface,
        \Magento\Framework\View\LayoutInterface $layout,
        ReserveFactory $rfact,
        CartItemRepositoryInterface $cartItemRepository,
        array $data = []
        ) {
        //    parent::__construct($data);
            $this->rfact=$rfact;
            $this->checkoutSession = $checkoutSession;
            $this->catalogUrl = $catalogUrl;
            $this->checkoutCart = $checkoutCart;
            $this->checkoutHelper = $checkoutHelper;
            $this->itemPoolInterface = $itemPoolInterface;
            $this->layout = $layout;

        $this->cartItemRepository = $cartItemRepository;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
///        $totals = $this->getQuote()->getTotals();
   ///     $subtotalAmount = $totals['subtotal']->getValue();
    
        
        $tt=$this->getTimer();
        
        return [
            'carttimer' => $tt,
        ];
    }
    protected function  getTimer(){
        $customer=$this->checkoutSession->getCustomer();
        if(!$customer){
            
            return 0;            
        }
        $cid=$customer->getEntityId();
        if(!$cid){
            
            return 0;
        }
        $allits=[];
        foreach($this->getQuote()->getAllVisibleItems() as $item){
            //file_put_contents("deleted.txt"," itemsku ".$item->getSku()."\n",FILE_APPEND);
            if(strpos($item->getSku(),'res-')!==false)
                $allits[]=$item;
            
        }
        
        foreach($this->getAllQuoteItems() as $item){
            
            if(in_array($item->getProduct()->getTypeId(),[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])){
                /**@var $product \Magento\Catalog\Model\Product */
       
                /**@var $res \Ibapi\Multiv\Model\Reserve */
                $res=$this->rfact->create();
                $r=$res->getTime($cid, $item->getProduct()->getId());
                //$sku=$item->getSku();
               // file_put_contents('deleted.txt', "prodnotr ".$item->getProduct()->getName()." sku $sku r $r\n ",FILE_APPEND);
                if(!$r){
                    return 0;
                }
                
                $t=$r;
                if(strstr($t, '-')){
                 // file_put_contents("deleted.txt"," will elete ".print_r($allits,1)."\n",FILE_APPEND);
                    foreach ($allits as $item){
                       ///$this->checkoutCart->removeItem($itemId);
                        try {

                          ///  $item->delete();

                            $this->cartItemRepository->deleteById($this->checkoutCart->getQuote()->getId(),$item->getId());
                        }catch (\Exception $e){

                            file_put_contents('deleted.txt', $e->getMessage()." error \n ",FILE_APPEND);
                        }
                   //     file_put_contents('deleted.txt', "prod ".$itemId."  t $t\n ",FILE_APPEND);
                    }
///                    $item->getParentItem()->delete();
                 ///   return '10:10:10';
                  return -1;
                }
                ///$t=10000;
                
                if($t){
                    
                  return $t;  
                }
                break;
            }else{
                
                
            }
            
            

        }
        return 0;
    }
    
    
    /**
     * Get active quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (null === $this->quote) {
            $this->quote = $this->checkoutCart->getQuote();
        }
        return $this->quote;
    }
    
    /**
     * Get shopping cart items qty based on configuration (summary qty or items qty)
     *
     * @return int|float
     */
    protected function getSummaryCount()
    {
        if (!$this->summeryCount) {
            $this->summeryCount = $this->checkoutCart->getSummaryQty() ?: 0;
        }
        return $this->summeryCount;
    }
    
    
    /**
     * Return customer quote items
     *
     * @return \Magento\Quote\Model\Quote\Item[]
     */
    protected function getAllQuoteItems()
    {
       
    $items=[];
        foreach ($this->getQuote()->getItemsCollection(false) as $item) {
          
            /** @var \Magento\Quote\Model\ResourceModel\Quote\Item $item */
            if (!$item->isDeleted()) {
                    $items[]=$item;       
            }
        }
        return $items;
    }
    
    /**
     * Check if guest checkout is allowed
     *
     * @return bool
     */
    public function isGuestCheckoutAllowed()
    {
        return $this->checkoutHelper->isAllowedGuestCheckout($this->checkoutSession->getQuote());
    }
}
