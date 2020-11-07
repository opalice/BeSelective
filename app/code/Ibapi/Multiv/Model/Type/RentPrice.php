<?php 
namespace  Ibapi\Multiv\Model\Type;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductTierPriceExtensionFactory;
use Ibapi\Multiv\Helper\Data;
use Magento\Catalog\Model\Product\Type\Price;
use Magento\Catalog\Model\Product;
use Magento\Customer\Api\GroupManagementInterface;
use Ibapi\Multiv\Model\RtableFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
                
 class RentPrice extends  Price{
    const TYPE_CODE= 'rent';
    var $rtfact;
    
    /**
     * Constructor
     *
     * @param \Magento\CatalogRule\Model\ResourceModel\RuleFactory $ruleFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param PriceCurrencyInterface $priceCurrency
     * @param GroupManagementInterface $groupManagement
     * @param \Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory $tierPriceFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param ProductTierPriceExtensionFactory|null $tierPriceExtensionFactory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\CatalogRule\Model\ResourceModel\RuleFactory $ruleFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        PriceCurrencyInterface $priceCurrency,
        GroupManagementInterface $groupManagement,
        \Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory $tierPriceFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        ProductTierPriceExtensionFactory $tierPriceExtensionFactory = null,
        RtableFactory $rfact
        ) {
            parent::__construct($ruleFactory, $storeManager, $localeDate, $customerSession, $eventManager, $priceCurrency, $groupManagement, $tierPriceFactory, $config,$tierPriceExtensionFactory);
            $this->rtfact=$rfact;
    }
    

	public function getCode(){
	    return self::TYPE_CODE;
	}
	
	

	
	public function  getFinalPrice($qty, $product) {

	   $cid=$this->_customerSession->getCustomerId();
	   
	   if(!$cid){
	       return parent::getFinalPrice($qty, $product);
	   }
	   
	   $r=$this->rtfact->create();
	   
	  $x= $r->getPrice($product->getId(), $cid);
	  if(!$x){
	   
	   $r=$this->_customerSession->getData('rental');
	   if(!$r){
	       
return	    parent::getFinalPrice($qty, $product);
	       
	      
	   }
	   return $r;
	  }
	  $r=$x->p;
	  
	  return $x->p;
	   
	}
	









	/**
	 * Check if product can be bought
	 *
	 * @param \Magento\Catalog\Model\Product $product
	 * @return $this
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	/*
	public function checkProductBuyState($product)
	{
		parent::checkProductBuyState($product);
		$option = $product->getCustomOption('info_buyRequest');
		if ($option instanceof \Magento\Quote\Model\Quote\Item\Option) {
			$buyRequest = new \Magento\Framework\DataObject(unserialize($option->getValue()));

//			
			if (!$buyRequest->hasLinks()) {
				if (!$product->getLinksPurchasedSeparately()) {
					$allLinksIds = $this->_linksFactory->create()->addProductToFilter(
							$product->getEntityId()
					)->getAllIds();
					$buyRequest->setLinks($allLinksIds);
					$product->addCustomOption('info_buyRequest', serialize($buyRequest->getData()));
				} else {
					throw new \Magento\Framework\Exception\LocalizedException(__('Please specify product link(s).'));
				}
			}


		}
		return $this;
	}
*/

	/**
	 * Prepare selected options for downloadable product
	 *
	 * @param  \Magento\Catalog\Model\Product $product
	 * @param  \Magento\Framework\DataObject $buyRequest
	 * @return array
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function processBuyRequest($product, $buyRequest)
	{
	    return parent::processBuyRequest($product, $buyRequest);
	}





	/**
	 * Prepare product and its configuration to be added to some products list.
	 * Perform standard preparation process and then prepare options for downloadable links.
	 *
	 * @param \Magento\Framework\DataObject $buyRequest
	 * @param \Magento\Catalog\Model\Product $product
	 * @param string $processMode
	 * @return \Magento\Framework\Phrase|array|string
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function _prepareProduct(\Magento\Framework\DataObject $buyRequest, $product, $processMode)
	{
	    return parent::_prepareProduct($buyRequest, $product, $processMode);
	    
	}

}