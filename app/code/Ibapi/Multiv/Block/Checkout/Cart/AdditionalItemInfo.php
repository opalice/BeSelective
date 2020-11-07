<?php
namespace Ibapi\Multiv\Block\Checkout\Cart;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Store\Model\StoreManager;
use Magento\Framework\View\Element\Template;
use Ibapi\Multiv\Helper\Data;
use Magento\Framework\App\Test\Unit\ObjectManagerFactoryTest;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;


class AdditionalItemInfo extends \Magento\Checkout\Block\Cart\Additional\Info{
	
	private $helper;
	private $xdata;
	private $objectmanager;
	/**
	 *
	 * @param Template\Context $context
	 * @param array $data
	 * @param Data $helper
	 */
	
	public function __construct(Template\Context $context, array $data = [],Data $helper,    \Magento\Framework\ObjectManagerInterface $objectmanager
			)
	{
		parent::__construct($context,$data);
		$this->helper=$helper;
		$this->xdata=$data;
		$this->objectmanager=$objectmanager;
		
		

	}
	public function getType(){
	
		return $this->getItem()->getProduct()->getTypeId();
		
	}
	
	
	

	/**
	 * @return additional information data
	 */
	public function getAdditionalData()
	{
	
		$item=$this->getItem();
		if($item->getProduct()->getTypeId()
		    ==AccessoryType::TYPE_CODE||$item->getProduct()->getTypeId()==ClothType::TYPE_CODE) {
		        
		        $option=$item->getOptionByCode('rental_option');
		        
		      if($option&&is_object($option)){
		          $opt=$option->getValue();
		          
		      list($y,$m,$d,$dd)=explode('-',$opt);  
		      return __('from %1 for %2 days',"$y-$m-$d",$dd);
		      }
		      
		    }
				
						
	return "";				
		
	}
	
	
}