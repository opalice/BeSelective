<?php 
namespace  Ibapi\Multiv\Model\Type;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Ibapi\Multiv\Helper\Data;
abstract  class RentalType extends AbstractType{

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

	abstract public function getCode();


	/**
	 * {@inheritdoc}
	 */
	public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
	{
		// method intentionally empty
	}


	/**
	 * Process product configuration
	 *
	 * @param \Magento\Framework\DataObject $buyRequest
	 * @param \Magento\Catalog\Model\Product $product
	 * @param string $processMode
	 * @return array|string
	 */
	public function processConfiguration(
	    \Magento\Framework\DataObject $buyRequest,
	    $product,
	    $processMode = self::PROCESS_MODE_LITE
	    ) {
	        $products = $this->_prepareProduct($buyRequest, $product, $processMode);
	///        $this->processFileQueue();
	        return $products;
	}
	


public function hasOptions($product){
	return true;
}



/**
 * Prepare additional options/information for order item which will be
 * created from this product
 *
 * @param \Magento\Catalog\Model\Product $product
 * @return array
 */
public function getOrderOptions($product)
{
	$options = parent::getOrderOptions($product);
	$option = $product->getCustomOption('rental_option');


	$options = array_merge($options, ['rental_option' => $option->getValue()]);

	$options = array_merge(
			$options,
			[ 'real_product_type' => $this->getCode()]
	);
	return $options;
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
	{}
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
		file_put_contents('logs/processbuy.txt', 'process');
		$infos = $buyRequest->getRentalOption();
//		$links = is_array($links) ? array_filter($links, 'intval') : [];

		$options = ['rental_option' => $infos];

		return $options;
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

		if($processMode!==self::PROCESS_MODE_FULL){
			return parent::_prepareProduct($buyRequest, $product, $processMode);
		}
		$info=$buyRequest->getRentalOption();
		$dds=explode('-',$info);
		if(count($dds)!==4){
		    file_put_contents('logs/buy.txt','invalid',FILE_APPEND);
		    
		    return __('Invalid configuration');
		}
		list($y,$m,$d,$dd)=$dds;
		$price=$d!=8?$product->getData('rent4'):$product->getData('rent8');
		

		file_put_contents('logs/buy.txt',"price  $price  : ".$product->getData('rent4')." r8 ".$product->getData('rent8')."\n");
		
		
		$depo=$product->getData('deposit');
		if(!$depo){
		    $depo=$product->getData('Deposit');
		    
		}
		if(!$depo){
		    $depo=0;
		}
		$buyRequest->setCustomPrice($price+$depo);
		
		
		
		
		
		if(!is_string($info))
			$s=print_r($info,1);
		else
			$s=$info;

		//$s=json_decode($s,true);
		file_put_contents('logs/buy.txt', $s,FILE_APPEND);
       


		try{
		$pro=$buyRequest->getData('_processing_params');
		if($pro){
			$config=$pro->getCurrentConfig();
			$opt=$config->getData('rental_option');
			if($opt&&!$this->_isStrictProcessMode($processMode)){
				$opt=\Zend_Json::decode($opt);
			}
		}

		$result = parent::_prepareProduct($buyRequest, $product, $processMode);
		
		
		
		if (is_string($result)) {
		    file_put_contents('logs/buy1.txt', $result." result ");
		    
		    return $result;
		}





		if($s){

		    file_put_contents('logs/buy1.txt', 'rentalget');
		    
			$product->addCustomOption('rental_option', $s);
			file_put_contents('logs/buy1.txt', $s);
			
		}
		}catch(\Exception $e){
		    file_put_contents('logs/buy1.txt', $e->getMessage());
		    
		    die($e->getTraceAsString());
		}
		
			return $result;

	}
	
	public function isSalable($product){
	    return true;
	}
	
	public  function  isVirtual($product){
	    return false;
	}
	
	public function isPossibleBuyFromList($product)
	{
	    return false;
	}

}