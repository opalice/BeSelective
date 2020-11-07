<?php
namespace Ibapi\Multiv\Helper;

use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\SubType;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Psr\Log\LoggerInterface;
use Zend\Log\Logger;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Monolog;
use Magento\Store\Model\ScopeInterface;
use Magento\CatalogInventory\Api\StockStatusRepositoryInterface;
use Magento\ProductAlert\Model\Stock;
use Magento\Framework\Registry;

class Data extends  AbstractHelper{
    
    protected $rtfact;
    protected $_registry;
    protected $_phelper;
    /**
     * CookieManager
     *
     * @var CookieManagerInterface
     */
    private $cookieManager;
    
    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;
    
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $sessionManager;
    
    private $storeManager;
    private $imgFolder;
    private $tmpFolder;
    private $customUrl;
    private $messageManager;
    private $stockRegistry;
    private $repoStst;
    private $registry;
    private $colfact;
    var $custSession;
/**
 * 
 * @param \Magento\Framework\App\Helper\Context $context
 * @param \Magento\Framework\Registry $registry
 * @param \Ibapi\Multiv\Model\RtableFactory $rtfact
 * @param \Psr\Log\LoggerInterface $logger
 * @param CookieManagerInterface $cookieManager
 * @param CookieMetadataFactory $cookieMetadataFactory
 * @param SessionManagerInterface $session
 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
 * @param \Magento\Framework\Pricing\Helper\Data $phelp
 * @param \Magento\Framework\Message\ManagerInterface $messageManager
 * @param \Magento\Customer\Model\Url $customerUrl
 * @param unknown $tmpFolder
 * @param unknown $imgFolder
 */     
    public function __construct(\Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Registry $registry,
        
        \Ibapi\Multiv\Model\RtableFactory $rtfact,
        \Psr\Log\LoggerInterface $logger,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Customer\Model\Session $customerSession,
         \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Pricing\Helper\Data $phelp,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        StockStatusRepositoryInterface $repoStst,
       
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        $tmpFolder,
        $imgFolder
        
        
        
        ){
        
        parent::__construct($context);
        $this->custSession=$customerSession;
        $this->_phelper=$phelp;
        $this->_registry=$registry;
        $this->rtfact=$rtfact;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $session;
        $this->storeManager=$storeManager;
        $this->tmpFolder=$tmpFolder;
        $this->imgFolder=$imgFolder;
        $this->customUrl=$customerUrl;
        $this->messageManager=$messageManager;
        $this->stockRegistry = $stockRegistry;
        $this->repoStst=$repoStst;
        $customer=$this->sessionManager->getCustomer();
        $this->colfact=$collectionFactory;
        if($customer){
            
        }
        
    }
    
    
    public function saveSession($key,$val){
        $this->custSession->setData($key,$val);
    }
    public function getSession($key){
        return $this->custSession->getData($key);
    }
    
    
    
   public  function  setRegistry($k,$v){
        $this->_registry->register($k,$v);
   }
   public function  getRegistry($k){
        return $this->_registry->registry($k);
   }
    
    public function getSizes($skugr){
        
return        $this->colfact->create()->addFieldToSelect('skugr')->addAttributeToSelect('size')->addFieldToFilter('skugr',$skugr)->getItems();
    }
    
    public function setSecure($f){
        $this->_registry->unregister('isSecureArea');
        $this->_registry->register('isSecureArea', $f);
        
    }
   
    
    public function getSearchCats($sd='',$d='',$sz=''){
        $vals=$this->_registry->registry('multiv');
        list($sdd,$dd,$ssz)=$this->getSearchCookies();
        
        
        
        $nv=['sz'=>'','d'=>'','sd'=>''];
        $ch=false;
        if(!$sd&&$sdd){
           $sd=$sdd;
        }
        
        if($sd){
            $ch=true;
            $nv['sd']=$sd;        
        }
        if($d){
            if($d!=4&&$d!=8){
                $d=4;
            }
            $nv['d']=$d;
        }
        if($sz){
            $ch=true;
            $nv['sz']=$sz;
        }
        if($ch){
            
                if($vals){
                    $this->_registry->unregister('multiv');
                }
                
                $this->_registry->register('multiv', $nv);
                return $nv;
            }else{
            
            return $vals;
            }
        
        
    }
     public function getTaxEnable(){

        return true;
    }
    public function getTaxTitle(){

        return __('Taxes');
    }
public function getVipEnable(){
        
        return true;
    }
    public function getVipTitle(){

        return __('Vip Discount');
    }

    public function getDisEnable(){
        
        return true;
    }
    public function getDisTitle(){
        
        return __('Money Box');
    }
    public function getCatTree($cids){
        
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        
        $cathelper=$objectManager->create(\Magento\Catalog\Helper\Category::class);
        
        /**@var $categories \Magento\Framework\Data\Tree\Node\Collection */
        
        function printcats($cathelper,$cat,$c,$cids){
            
            $cats=$cat->getChildren();
            $ct=$cats->count();
            $arr=[];
            foreach($cats as $id=>$cat ){
                /**@var $cat \Magento\Framework\Data\Tree\Node */
                $pp=[];
                $nm=$cat->getName();
                $pp['id']=$id;
                $pp['data']=$nm;
                $pp['attr']=[
                    'id'=>'cx_'.$id
                    
                ];
                $op=false;
                $sel=false;
                
                if(in_array($id, $cids)){
                    $op=true;
                    $sel=true;
                }
                
                $pp['state']=['opened'=>$op,'disabled'=>false,'selected'=>$sel];
                $pp['metadata']=['id'=>$id];
                
                $pp['children']=[];
                
                ////                echo str_repeat('-',$c*2)." $id : $nm,  $ct\n";
                if($ct){
                    $pp['children']=    printcats($cathelper,$cat, $c+1,$cids);
                }
                $arr[]=$pp;
            }
            return $arr;
        }
        
        $categories = $cathelper->getStoreCategories(true,false,true);
        $c=2;
        $arr=[];
        
        foreach ($categories as $cat){
            $pp=[];
            $pp['id']=$cat->getId();
            $pp['data']=$cat->getName();
            $pp['metadata']=['id'=>$cat->getId()];
            $op=true;
            $sel=false;
            
            if(in_array($cat->getId(), $cids)){
                $op=true;
                $sel=true;
            }
            
            $pp['state']=['opened'=>$op,'disabled'=>false,'selected'=>$sel];
            
            
            $pp['children']=[];
            if(  $cat->getChildren()->count()){
                $pp['children']=printcats($cathelper, $cat, $c,$cids);
                
            }
            $arr[]=$pp;
            
        }
        
        return $arr;
    }
    
    public function updateProductStock($product) {
        $stockItem=$this->stockRegistry->getStockItem($product->getId()); // load stock of that product
        $stockItem->setData('is_in_stock',1); //set updated data as your requirement
        $stockItem->setData('qty',10000000); //set updated quantity
        $stockItem->setData('manage_stock',0);
        $stockItem->setData('use_config_notify_stock_qty',1);
        $stockItem->save(); //save stock of item
        //$product->save(); //  also save product
        $stockStatus=$this->stockRegistry->getStockStatus($product->getId());
        $stockStatus->setStockStatus($stockStatus);
        $this->log("updated stock for ".$product->getId());
        
    }

    
    public function addStock($product){
        $sku=$product->getSku();
        
///        $sku = 'ABC123';
        $qty = 10;
        $stockItem = $this->stockRegistry->getStockItemBySku($sku);
///        if($stockItem->setStockStatusChangedAuto($stockStatusChangedAuto))


        $stockItem->setManageStock(false);
        $stockItem->setUseConfigManageStock(false);
///        $stockItem->setBackorders($backOrders)
        $stockItem->setQty(10000);
        $stockItem->setIsInStock(1); // this line
        $pid=$product->getId();
    $stockItem->setMinQty(0);
    $stockItem->setBackorders(1);
        
        $this->stockRegistry->updateStockItemBySku($sku, $stockItem);

        
        $stockStatus = $this->stockRegistry->getStockStatus($product->getId(), $product->getStore()->getWebsiteId());
        $stockStatus->setStockStatus(\Magento\CatalogInventory\Model\Stock::STOCK_IN_STOCK);
        $stockStatus->setQty(10);
        $stockStatus->getStockItem()->setBackorders(1);        
        $stockStatus->getStockItem()->setIsInStock(true);
        $stockStatus->getStockItem()->setMinQty(0);
        
        $stockStatus->setStockId($stockItem->getStockId());
        $stockStatus->setProductId($product->getId());
        
        
        ///        $this->repoStst->save($stockStatus);
        ///        $stockStatus = $this->stockRegistry->getStockStatus($product->getId());
        ///     $stockStatus->setStockStatus(\Magento\CatalogInventory\Model\Stock::STOCK_IN_STOCK);
        
$stockStatus->getStockItem()->setIsInStock(true);
        $this->log("set stock ".$product->getId());
        $this->repoStst->save($stockStatus);
        
//        $this->stockRegistry->``
    }
    
    
    /**
     * 
     * @return \Magento\Store\Model\StoreManagerInterface
     */
    public function getStoreManager(){
        return $this->storeManager;
    }
    public function getImgUrl($im){
        
    return       rtrim( $this->getStoreManager()->getStore(0)->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA),'/').'/catalog/product/'.ltrim($im,'/');
    }
    public function getValue($path){
        
        return $this->scopeConfig->getValue($path,ScopeInterface::SCOPE_STORE);
    }
    
    
    public function getCurrentCatId(){
        
$cat=        $this->_registry->registry('current_category');
if($cat&&is_object($cat)){
    return $cat->getId();
}

return 0;

    }
    

    public function getSel($opt){
        return $this->rtfact->create()->getSelect($opt);
    }
    
    public function addSt($st,$et,$qid){
        $rt=$this->rtfact->create();
      
        $rt->addSt($st, $et, $qid, $this->getCustomerId());
        
        
        
    }
    public function canreserve($opt,$pid,$wash){
        $this->log("reserving $opt qote $pid");
        
        list($y,$m,$d,$dd)=explode('-',$opt);
        
        $rt=$this->rtfact->create();
        $sd="$y-$m-$d";
        $dd=$dd-1;
        $edx=$ed=date('Y-m-d',strtotime("+$dd day",strtotime($sd)));
        
        $ed=strtotime("+$wash day",strtotime($ed));
        
        /* @var $orderItem \Magento\Sales\Api\Data\OrderItemInterface */
        list($sd,$ed)=$this->getDatePair($sd, $dd);
        $this->log("##datepaircan $sd to $ed ####");
        $con=$rt->canreserve($pid, $sd, $ed);
        if(!$con){
            $this->log("##cannot reserve $sd to $ed $edx ####");
            return false;         
///            throw new LocalizedException(__('Cannot reserve for %1 to %2 . try another date.',$sd,$ed))
            ;
        }else{
            $this->log("canreserved");
        }
        
        return [$sd,$edx];
        
    }
    
    public function reserve($opt,$pid,$qid,$oe,$ue,$pe,$de,$wash,$dis){
        $this->log("reserving $opt qote $qid");
        
        list($y,$m,$d,$dd)=explode('-',$opt);
        
        $rt=$this->rtfact->create();
        $sd="$y-$m-$d";
        
       /** @var $rt \Ibapi\Multiv\Model\Rtable*/


        /* @var $orderItem \Magento\Sales\Api\Data\OrderItemInterface */
         list($sd,$ed)=$this->getDatePair($sd, $dd);
         $this->log("##datepair $sd to $ed ####");
         $con=$rt->reserve($pid,$this->getCustomerId(), $sd, $ed,'u',$qid,$oe,$ue,$pe,$de,$wash,$dis);
         if(!$con){
             $this->log("##cannot reserve $sd to $ed ####");
             
         throw new LocalizedException(__('Cannot reserve for %1 to %2 . try another date.',$sd,$ed))
         ;
         }else{
             $this->log("reserved");
         }
         
        return $con;
        
    }
    
    public function getCustomerId(){
        $cid=$this->sessionManager->getCustomerId();
        if(is_null($cid)||!$cid) return '';
        return $cid;
    }
    
    public function isVip(){
        if(is_object($this->sessionManager->getCustomer())){
            return $this->sessionManager->getCustomerGroupId()==5;
        }
    return false;
    }
    public function getCustomerGroup(){
   
   return     $this->sessionManager->getCustomer()->getGroupId();
        
    }
    
    public function getTmpFolder(){
        return $this->tmpFolder;
    }
public function getImgFolder(){
        return $this->imgFolder.'/'.$this->getCustomerId();
    }
    public function getReviewFolder(){
        return '/review';
    }
    public function log($msg){
///        Monolog\Logger::CRITICAL;
$msg=$msg."\n";

        file_put_contents('log.txt', $msg,FILE_APPEND);
        if(!strstr($msg, 'multiv:'))   {
            $msg='multiv:'.$msg;
        }

        $this->_logger->log(100, $msg);
    
    }
    public function getFormattedAmt($amt){
        
        return $this->_phelper->currency($amt,true,false);
    }
    
    public function  getDefaultDate(){
        
        return date('Y-m-d',strtotime('+1 day',time()));
    }
    public function getSearchCookies(){
        $nv2=$this->getSearchVal();
        
        
        if(!$nv2[0]){
        $nv= [0=>
             $this->cookieManager->getCookie('sd')
          ,
        1=>      $this->cookieManager->getCookie('r'),
          
          2=>  $this->cookieManager->getCookie('sz'),
            
        ];
        
        }else{
            $nv=$nv2;
        }
        
        return array_values($nv);
        
    }
    public function getSearchVal(){
        $nv=$this->_registry->registry('multiv');
        return [$nv['sd']??'',$nv['r']??'4',$nv['sz']??''];
        
    }
    public function setSearchCookies($params){
        
        if(isset($params['sd'])&&!$params['sd']){
            unset($params['sd']);
        }
        if(isset($params['sz'])&&!$params['sz']){
            unset($params['sz']);
        }
        if(isset($params['r'])&&!$params['r']){
            unset($params['r']);
        }
        $vals=$this->_registry->registry('multiv');
        if($vals&&count($vals)){
            $this->_registry->unregister('multiv');
        
        }
            $vals=$this->getSearchCookies();
            
        /*
        if(!isset($vals['r'])){
            $vals['r']='';
        }
        if(!isset($vals['sz'])){
            $vals['sz']='';
        }
        if(!isset($vals['sd'])){
            $vals['sd']='';
        }
        
        
        $sd=$params['sd']??$vals['sd'];
        $d=$params['r']??$vals['r'];
        $sz=$params['sz']??$vals['sz'];
        */
        $sd=$params['sd']??'';
        $d=$params['r']??'';
        $sz=$params['sz']??'';
        if(!$sd){
            $sd=$vals['sd']??'';
        }
        if(!$d){
            $d=$vals['d']??'';
        }
        if(!$sz){
            $sz=$vals['sz']??'';
        }
        
        if($sd){
            $stime=strtotime($sd);
            $tx=strtotime('+1 day');
            $cday=(int)date('Ymd',$tx);
            if($sd&& date('Ymd',$stime)<(int)$cday){
                
            $sd='';
            }else{
                $sd=date('Y-m-d',$stime);
            }
             if(!$d){
                $d=4;
            }
        }
        
        
        $nv=['sd'=>$sd,'r'=>$d,'sz'=>$sz];
       
        $this->_registry->register('multiv', $nv);

        
        $duration=86400;
        $metadata = $this->cookieMetadataFactory
        ->createPublicCookieMetadata()
        ->setDuration($duration)
        ->setPath($this->sessionManager->getCookiePath())
        ->setDomain($this->sessionManager->getCookieDomain());
        
        $this->cookieManager->setPublicCookie(
            'r',
            $d,
            $metadata
            );
        
        $this->cookieManager->setPublicCookie(
            'sd',
            $sd,
            $metadata
            );
        if($sz){
            $this->cookieManager->setPublicCookie(
                'sz',
                $sz,
                $metadata
                );
            
        }
        return [$sd,$d,$sz];
    }
    public function validateDate($sd){
        $t=time();
        if(!is_numeric($sd)){
            list($y,$m,$d)=explode('-',$sd);
            $m=(int)$m;
            $d=(int)$d;
            if($m>12||$d>31) return false;
            
            $dx=cal_days_in_month(CAL_GREGORIAN,$m,$y);
            if($d>$dx){
                return false;
            }
          $tm=strtotime($sd);
          if($tm<$t){
              return false;
          }
            return true;
            
        }
        else{
            
        }
        return true;
        
    }
    
    public function getDatePair($sd,$d){
        $this->log("datepair for  $sd : $d");
        if(!is_numeric($sd)){
            
            
            $sd=strtotime($sd);
        }
        $d=(int)$d;
        if(!in_array($d, [4,8])){
            $d=4;
        }        
        $ss=$sd;
        $d=$d-1;
        $ed= date('Y-m-d',strtotime("+ $d day",$sd));
       
        $sd=date('Y-m-d',$sd);
        $this->log("datepair for sd  $sd  ed $ed  d $d ts $ss");
        
        return [$sd,$ed];
        
        
        
    }
    public function getCat(){
        $cat= $this->_registry->registry('current_category');
        if(is_object($cat)){
            return $cat->getId();
        }
        return '';
    
    }
    
    /**
     * Get customer repository
     *
     * @return \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private function getCustomerRepository()
    {
        
        if (!($this->customerRepository instanceof \Magento\Customer\Api\CustomerRepositoryInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Customer\Api\CustomerRepositoryInterface::class
                );
        } else {
            return $this->customerRepository;
        }
    }
    
    
    
    public  function  getCalPair($sd){
        if(!is_numeric($sd)){
            $sd=strtotime($sd);
        }
       $dt=date('Y-m-d',$sd);
       list($y,$m,$d)=explode('-',$dt);
       $fdt=date('w',mktime(0,0,0,$m,1,$y));
       
       
       $st='';
       $et='';

       $pm=strtotime('-1 month',$sd);
       $ldpm = date("Y-m-t",$pm);
       if($fdt>0){
           list($py,$pmo,$pmd)=explode('-',$ldpm);
           $st=$pmd-$fdt+1;
           $pd="$py-$pmo-$st";
           
       }else{
           $pd="$y-$m-01";
       }
       $dt=date('Y-m-t',$sd);
       list($y,$m,$d)=explode('-',$dt);

       $fdt=date('w',mktime(0,0,0,$m,$d,$y));
       
       if($fdt<6){
           $st=6-$fdt;
           $nm=strtotime('+1 month',$sd);
           $dt=date('Y-m-t',$nm);
           list($y,$m,$d)=explode('-',$dt);
           
           $st="0".$st;
           
           $ld="$y-$m-$st";
           
           
       }else{
           $ld=date('Y-m-t',$sd);
           
       }
       
       
      return [$pd,$ld];
      
       
       
       
       
       
       
       
    }
    
    public function getForRange($product,$sd,$ed){
        if(!is_numeric($sd)){
            $sd=strtotime($sd);
        }
        $sd=date('Y-m-d',$sd);
        
        if(!is_numeric($ed)){
            $ed=strtotime($ed);
        }
        $ed=date('Y-m-d',$ed);
        
        
        return  $rtable=$this->rtfact->create()->getSchedule($product,$sd,$ed);
        
        
    }
    
    public function isFree($pid,$sd,$ed){
        
        $rtable=$this->rtfact->create();
        return $rtable->isFree($pid,$sd, $ed);
        
    }
    
    public function getGenBlocks($sd,$ed){
        
        $rtable=$this->rtfact->create();
        return $rtable->getGenBlock($sd, $ed);
        
    }

    public function getAGenBlocks($sd,$ed,$pid){
        
        $rtable=$this->rtfact->create();
        return $rtable->getAGenBlock($pid,$sd, $ed);
        
    }
    
    public function getForMo($pid,$d=0){
        if(!$d){
            $d=time();
        }
        if(!is_numeric($d)){
            $d=strtotime($d);
        }
        $dt=date('Y-m-d',$d);
        list($y,$m,$d)=explode('-',$dt);
        $sd=$y.'-'.$m.'-01';
        $ed = date("Y-m-t",$d);
        
      return  $rtable=$this->rtfact->create()->getSchedule($pid,$sd,$ed);    
            
    }
    
    public function nextdate($bd){
        $bd=date('Y-m-d',strtotime($bd));
        $tbd=date('Y-m-d',strtotime($bd.' +1 day'));
        return $tbd;
        
        
        
    }
    
    public function prevday($bd){
        $bd=date('Y-m-d',strtotime($bd));
        $tbd=date('Y-m-d',strtotime($bd.' -1 day'));
        return $tbd;
        
        
    }
    
    
}