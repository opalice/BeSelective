<?php 
namespace  Ibapi\Multiv\Model\Type;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Ibapi\Multiv\Helper\Data;

 class SubType extends AbstractType{
    const TYPE_CODE= 'subscription';
     
	private $helper;

	/**
	 *
	 * @param \Magento\Catalog\Model\Product\Option $catalogProductOption
	 * @param \Magento\Eav\Model\Config $eavConfig
	 * @param \Magento\Catalog\Model\Product\Type $catalogProductType
	 * @param \Magento\Framework\Event\ManagerInterface $eventManager
	 * @param \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb
	 * @param \Magento\Framework\Filesystem $filesystem
	 * @param \Magento\Framework\Registry $coreRegistry
	 * @param \Psr\Log\LoggerInterface $logger
	 * @param ProductRepositoryInterface $productRepository
	 * @param Data $helper
	 */

	public function __construct(
			\Magento\Catalog\Model\Product\Option $catalogProductOption,
			\Magento\Eav\Model\Config $eavConfig,
			\Magento\Catalog\Model\Product\Type $catalogProductType,
			\Magento\Framework\Event\ManagerInterface $eventManager,
			\Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb,
			\Magento\Framework\Filesystem $filesystem,
			\Magento\Framework\Registry $coreRegistry,
			\Psr\Log\LoggerInterface $logger,
			ProductRepositoryInterface $productRepository,
			Data $helper
	) {
		parent::__construct($catalogProductOption, $eavConfig, $catalogProductType, $eventManager, $fileStorageDb, $filesystem, $coreRegistry, $logger, $productRepository);
		$this->helper=$helper;

	}

	public function getCode(){
	    return self::TYPE_CODE;
	}


	/**
	 * {@inheritdoc}
	 */
	public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
	{
		// method intentionally empty
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
	
	public  function  isVirtual($product){
	    return true;
	}
	public function isSalable($product){
	    return true;
	}
	
	public function isPossibleBuyFromList($product)
	{
	    return true;
	}

}