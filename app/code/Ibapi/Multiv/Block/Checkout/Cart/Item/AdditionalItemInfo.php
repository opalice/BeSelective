<?php
namespace Ibapi\Multiv\Block\Checkout\Cart\Item;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Store\Model\StoreManager;
use Magento\Framework\View\Element\Template;
use Ibapi\Multiv\Helper\Data;
use Magento\Framework\App\Test\Unit\ObjectManagerFactoryTest;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;
use Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;


class AdditionalItemInfo extends \Magento\Framework\View\Element\Template{
	
	private $helper;
	private $xdata;
	private $objectmanager;
	private $item;
	protected $pi;
	
	private $depo;
	private $rent;
    private $sd;
    private $dd;
    private $to;
    private $from;
    private $product;
    private $sku;
	private $cloth;
	private $dis;
	
	
	/**
	 *
	 * @param Template\Context $context
	 * @param array $data
	 * @param Data $helper
	 */
    public function setPi($pi){
        $this->pi=$pi;
    }
    public function getSku(){
        return $this->sku;
    }
    public function getCloth(){
        
        return $this->cloth?1:0;
    }
	
	public function getDeposit($a=0){
	    if($a) return $this->depo;
	    return  $this->pi->format($this->product->getData('deposit'))  ;
	    
	}
	public function setProduct($product){
	$this->product=$product;    
	}
	public function getTo(){
	    return $this->to;
	}
	
	public function getRent($a=0){
	    
	    if($a) return $this->rent;
	   
	    ///file_put_contents('rent.txt', " days ".$this->getDays()." rent ".$this->rent." depo ".$this->depo);
	    
    return	    $this->pi->format($this->rent)  ;   
	    
	}
	public function getDis($a=0){

	    
	    if($a) return $this->dis;
	    
	    ///file_put_contents('rent.txt', " days ".$this->getDays()." rent ".$this->rent." depo ".$this->depo);
	    
	    return	    $this->pi->format($this->dis)  ;
	    
	}
	public function setItem($item){
	    $this->cloth=0;
	    $this->sku=$item->getProduct()->getSku();
	    foreach($item->getChildren() as $lt){
	        if(in_array($lt->getProduct()->getTypeId(),[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])){
                $this->item=$lt;
                if($lt->getProduct()->getTypeId()==ClothType::TYPE_CODE){
                   $this->cloth=1;
                }
                /**@var $lt \Magento\Quote\Model\Quote\Item */
                $this->dis=0;///$lt->getDiscountAmount();
                $this->product=$lt->getProduct();
                file_put_contents('itemprice.txt', "it ".$lt->getSku()." price ".$lt->getCustomPrice()." row t ".$lt->getRowTotal()."\n",FILE_APPEND);
	            break;
	        }
	    }
	    if(!$this->item||!$this->product){
	        return;
	    }
	    $item=$lt;
	    $this->depo=$this->product->getData('deposit');
	    $product=$this->product;
	    
	    $option=$item->getOptionByCode('rental_option');
	    $opts=explode('-',$option->getValue());
	    
	    list($y,$m,$d,$dd)=$opts;
	    $this->rent=$product->getData('rent'.($dd==8?"8":"4"));
	///    file_put_contents('rent2.txt', " days ".$this->getDays()." rent ".$this->rent." depo ".$this->depo." sku ".$product->getSku());
	    $this->from="$y-$m-$d";
	    $this->dd=$dd;
	    $this->to=date('Y-m-d',strtotime($this->from.'+ '.$dd.' days'));
	    
	    
	    return $this;
	}
	public function getFrom(){
	    return $this->from;
	}
	public function getDays(){
	    return $this->dd;
	}
	public function getTotal(){
	    return $this->pi->format($this->dd*$this->rent+$this->depo);
	}
	public function getItem(){
	    return $this->item;
	}
	
	
/*	
	public function getAdditionalData()
	{
	    
	    $item=$this->getItem();
	    if($item->getProduct()->getTypeId()
	        ==Helmet::TYPE_CODE) {
	            
	            
	            return					$this->objectmanager->create('\Ibapi\Configpro\Block\Checkout\Cart\AdditionalHelmetItemInfo',['data'=>$this->xdata,'item'=>$item,'helper'=>$this->helper,'storeManager'=>$this->_storeManager])->getAdditionalData();
	        }
	        
	        if($item->getProduct()->getTypeId()
	            ==Bag::TYPE_CODE) {
	                return					$this->objectmanager->create('\Ibapi\Configpro\Block\Checkout\Cart\AdditionalBagItemInfo',['data'=>$this->xdata,'item'=>$item,'helper'=>$this->helper,'storeManager'=>$this->_storeManager])->getAdditionalData();
	                
	                
	            }
	            
	            return "";
	            
	}
	*/
	

	/**
	 * @return additional information data
	 */
	public function getAdditionalData()
	{}
	
	
}