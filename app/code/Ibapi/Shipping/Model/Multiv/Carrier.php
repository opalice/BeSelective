<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Shipping\Model\Multiv;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Shipping\Model\Tracking\Result\StatusFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Store\Model\ScopeInterface;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Magento\Customer\Model\Logger;


/**
 * Class Carrier In-Store Pickup shipping model
 */
class Carrier extends AbstractCarrier implements CarrierInterface
{
    /**
     * Carrier's code
     *
     * @var string
     */
    protected $_code = 'multivship';

    /**
     * Whether this carrier has fixed rates calculation
     *
     * @var bool
     */
    protected $_isFixed = true;
    
    /**
     * @var StatusFactory
     */
    private $trackStatusFactory;
    
    /**
     * @var MethodFactory
     */
    private $rateMethodFactory;
    
    /**
     * @var ResultFactory
     */
    private $rateResultFactory;
    
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    
    /**
     * @var ShipmentRepositoryInterface
     */
    private $shipmentRepository;
    
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    
    /**
     * @var OrderInterfaceBuilder
     */
    private $orderBuilder;
    
    /**
     * @var OrderReferenceInterfaceFactory
     */
    private $orderReferenceFactory;
    

    private $customerRepo;
    
    protected $_resourceConnection;
/**
 * 
 * @param ScopeConfigInterface $scopeConfig
 * @param ErrorFactory $rateErrorFactory
 * @param LoggerInterface $logger
 * @param StatusFactory $trackStatusFactory
 * @param MethodFactory $rateMethodFactory
 * @param ResultFactory $rateResultFactory
 * @param ManagerInterface $messageManager
 * @param ShipmentRepositoryInterface $shipmentRepository
 * @param OrderRepositoryInterface $orderRepository
 * @param OrderInterfaceBuilder $orderBuilder
 * @param OrderReferenceInterfaceFactory $orderReferenceFactory
 * @param array $data
 */    
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        StatusFactory $trackStatusFactory,
        MethodFactory $rateMethodFactory,
        ResultFactory $rateResultFactory,
        ManagerInterface $messageManager,
        CustomerRepository $custRepo,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
//        ShipmentRepositoryInterface $shipmentRepository,
  //      OrderRepositoryInterface $orderRepository,
    //    OrderInterfaceBuilder $orderBuilder,
      //  OrderReferenceInterfaceFactory $orderReferenceFactory,
        
        array $data = []
    ) {
        $this->customerRepo=$custRepo;
        $this->trackStatusFactory = $trackStatusFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->rateResultFactory = $rateResultFactory;
        $this->messageManager = $messageManager;
        $this->_resourceConnection=$resourceConnection;
        
//        $this->shipmentRepository = $shipmentRepository;
   //     $this->orderRepository = $orderRepository;
     //   $this->orderBuilder = $orderBuilder;
       // $this->orderReferenceFactory = $orderReferenceFactory;
        
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        
    }

    /**
     * Generates list of allowed carrier`s shipping methods
     * Displays on cart price rules page
     *
     * @return array
     * @api
     */
    public function getAllowedMethods()
    {
        
        return ['pick'=>'Pickup' ,'std'=>__('Standard'),'exp'=>__('Express')];
    }

    /**
     * Collect and get rates for storefront
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param RateRequest $request
     * @return DataObject|bool|null
     * @api
     */
    public function collectRates(RateRequest $request)
    {
        /**
         * Make sure that Shipping method is enabled
         */
        if (!$this->isActive()) {

            return false;
        }
        $items=$request->getAllItems()        ;
        /** @var $item Magento\Quote\Model\Quote\Item */
    ////    $defs=$this->helper->getValue('rentals/procat/subid');
        $defs=$this->_scopeConfig->getValue('rentals/procat/subid',ScopeInterface::SCOPE_STORE);
        $grp=false;
        $first=false;
        
        $time=time();
        
        $con=$this->_resourceConnection->getConnection();
        
        
        
        
        $cid=$qid='';
        
        foreach($items as $item){
                /**@var $item \Magento\Quote\Model\Quote\Item */
            
            $cid=$item->getQuote()->getCustomerId();
            $qid=$item->getQuoteId();
            $quote=$item->getQuote();  
                
                break;
        
        }
        
        
        $ship=$quote->getShippingAddress();
        
        $code=$ship->getPostcode();
        $zip=strstr($this->getConfigData('zip'),$code);
        
        /**
         * Build Rate for each location
         * Each Rate displayed as shipping method under Carrier(In-Store Pickup) on frontend
         */
        
        $result = $this->rateResultFactory->create();
        if(!$cid||!$qid){
            return $result;
        }
        
        /*
         * 3 delivery modes:

Block Step 1 Time Range: become a information block with button (next)
Its important that a magento block is called so we can design and translate it easy

Click & collect
-> 0€

STANDARD 48h - 15€
-> before 16h J+1
-> after 16h J+2

EXPRESS 24h – 24€ (Available only for 19 POSTCODE-region)
-> before 16h ok for the next day
-> after 16h J+1

Saturday & Sunday no delivery available (i think you need to block all Sunday into calendar)
Sunday can not be selected as first renting day.

48h can become free if rent prince (not sub-total) is > 100€
         */
        
        $dt=date('Hi');
        $c=time();
        if($dt<1598){
            
            $std=time();
            
        }else{
            $std=strtotime('+1 day',$c);
        }
        $stdd=date('Y-m-d',$std);
        $table=$con->getTableName('multiv_res');

        
        
        $r=$con->fetchRow("select datediff(sd,'$stdd') as sd from $table where qid='$qid' ",null,\PDO::FETCH_OBJ);


        $result->reset();
        $method = $this->rateMethodFactory->create();
        
        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethod('pick');
        $method->setMethodTitle($this->getConfigData('pick_title'));
        
        $method->setPrice(0);
        $method->setCost(0);
        $result->append($method);
        
if(!$r){

            $method = $this->rateMethodFactory->create();
            $dt= date('Y-m-d H:i',strtotime('+1 day',$std));
            $txt=__('Express delivery within %1',$dt);

            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod('exp');
            $method->setMethodTitle(str_replace('%1',$dt,$this->getConfigData('vip_title')));
            $method->setMethodTitle($this->getConfigData('vip_title'));
            $amt=$this->getConfigData('vipprice');
            $method->setPrice($amt);
            $method->setCost($amt);
            $result->append($method);


            $method = $this->rateMethodFactory->create();
	        $dt= date('Y-m-d H:i',strtotime('+2 day',$std));
            $txt=__('Standard delivery within %1',$dt);
            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod('std');
            $method->setMethodTitle(str_replace('%1',$dt,$this->getConfigData('normal_title')));

            $amt=0;
            /**@var $item \Magento\Quote\Model\Quote\Item */
            foreach($quote->getAllItems() as $item){
                if(in_array($item->getProductType(), [ClothType::TYPE_CODE,AccessoryType::TYPE_CODE,'simple'])){
                    $amt+=$item->getRowTotal();
                }

            }
            $this->_logger->log(\Monolog\Logger::DEBUG, "shippingamount :$amt ");


            $std=(int)$this->getConfigData('free');
            if($amt>=$std){
                $amt=0;
            }else{
                $amt=$this->getConfigData('price');
            }

            $method->setPrice($amt);
            $method->setCost($amt);
            $result->append($method);



            ///     file_put_contents('shipperr.txt', "norow select datediff(sd,'$stdd') as sd from $table where qid='$qid'    dt $dt"."\n",FILE_APPEND);
            return $result;




        }
        
        
        if($r->sd<1){


            return $result;
            
        }

        if($r->sd==1){
            if(!$zip){

                return $result;
            }

            $method = $this->rateMethodFactory->create();
            $dt= date('Y-m-d H:i',strtotime('+1 day',$std));
            $txt=__('Express delivery within %1',$dt);

            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod('exp');
            $method->setMethodTitle(str_replace('%1',$dt,$this->getConfigData('vip_title')));
            $method->setMethodTitle($this->getConfigData('vip_title'));
            $amt=$this->getConfigData('vipprice');
            $method->setPrice($amt);
            $method->setCost($amt);
            $result->append($method);
            return $result;
        
        }
    	    $method = $this->rateMethodFactory->create();
	            $dt= date('Y-m-d H:i',strtotime('+2 day',$std));
            $txt=__('Standard delivery within %1',$dt);
            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod('std');
            $method->setMethodTitle(str_replace('%1',$dt,$this->getConfigData('normal_title')));
            
            $amt=0;
            /**@var $item \Magento\Quote\Model\Quote\Item */
            foreach($quote->getAllItems() as $item){
                if(in_array($item->getProductType(), [ClothType::TYPE_CODE,AccessoryType::TYPE_CODE,'simple'])){
                    $amt+=$item->getRowTotal();
                }
                
            }
            $this->_logger->log(\Monolog\Logger::DEBUG, "shippingamount :$amt ");
            
            
            $std=(int)$this->getConfigData('free');
            if($amt>=$std){
                $amt=0;
            }else{
                $amt=$this->getConfigData('price');
            }
            
            $method->setPrice($amt);
            $method->setCost($amt);
            $result->append($method);
        
        
        
        
        
        /**@var $result Rat*/
        
        /**@var $result Magento\Shipping\Model\Rate\Result */
        
        
$cl='';        
         
        return $result;
    }
    
    /**
     * @return Method
     */
    protected function buildRate($grp)
    {
        /*
         *                $rate = $this->_rateMethodFactory->create();
                $rate->setCarrier('ups');
                $rate->setCarrierTitle($this->getConfigData('title'));
                $rate->setMethod($method);
                $methodArray = $this->configHelper->getCode('method', $method);
                $rate->setMethodTitle($methodArray);
                $rate->setCost($costArr[$method]);
                $rate->setPrice($price);
                $result->append($rate);

         */
        
        $rateResultMethod = $this->rateMethodFactory->create();
        
        if($grp){
            $methodTitle=$this->getConfigData('vip_title');
            $m='rental_vip';
        }
        else{
            $m='rental_normal';
            $methodTitle=$this->getConfigData('normal_title');
        }
        
        $rateResultMethod->setData('method_title', $methodTitle);
        $rateResultMethod->setData('method', $m);
        file_put_contents('log.txt',"\ngrouped m $m  title $methodTitle price $price\n",FILE_APPEND);
        
        if($grp){
            $price=0;
        }else{
            $price=$this->getConfigData('price');
        }
        
        $rateResultMethod->setPrice($price);
        $rateResultMethod->setData('cost', $price);
        
        return $rateResultMethod;
    }
    


    /**
     * Get configured Store Shipping Origin
     *
     * @return array
     */
    protected function getShippingOrigin()
    {
        /**
         * Get Shipping origin data from store scope config
         * Displays data on storefront
         */
        return [
            'country_id' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_COUNTRY_ID,
                ScopeInterface::SCOPE_STORE,
                $this->getData('store')
            ),
            'region_id' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_REGION_ID,
                ScopeInterface::SCOPE_STORE,
                $this->getData('store')
            ),
            'postcode' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_POSTCODE,
                ScopeInterface::SCOPE_STORE,
                $this->getData('store')
            ),
            'city' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_CITY,
                ScopeInterface::SCOPE_STORE,
                $this->getData('store')
            )
        ];
    }
}
