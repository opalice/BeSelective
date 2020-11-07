<?php
namespace  Ibapi\Multiv\Plugin\Cart;


use Ibapi\Multiv\Helper\Discount;
use Ibapi\Multiv\Model\Extension\QuoteOption;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Checkout\Model\Cart\CartInterface;
use Magento\Checkout\Model\Cart;
use Magento\ConfigurableProduct\Api\Data\OptionInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Bundle\Api\Data\BundleOptionInterfaceFactory;
use Magento\GiftMessage\Model\CartRepository;
use Magento\Quote\Api\Data\CartExtensionFactory;

class Around{
    var $helper;
    var $productRepo;
    var $cart;
    var $extFact;
    var $bfact;
    var $msgmgr;
    var $carto;
    var $qo;
    private $ph;
    private $cartExtensionFactory;

    public  function  __construct(\Ibapi\Multiv\Helper\Data $helper,\Magento\Catalog\Model\ProductRepository $productRepository,

        BundleOptionInterfaceFactory $bfact,
//       ExtensionAttributesFactory $ext,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        CartRepository $carto,
        \Magento\Customer\Model\Session $customerSession,
        Discount $ph,
        QuoteOption $qo,
        CartExtensionFactory $cartExtensionFactory,
         \Magento\Checkout\Model\Session $checkoutSession){
        
  //         $this->extensionInterface=$ext;
        //$this->extFact=$ext;        
        $this->helper=$helper;
        $this->productRepo=$productRepository;    
        $this->cart=$checkoutSession;
        $this->bfact=$bfact;
        $this->cartExtensionFactory=$cartExtensionFactory;
        $this->msgmgr=$messageManager;
        $this->carto=$carto;
        $this->ph=$ph;
        $this->qo=$qo;
       /// $this->cart->getQuote()->getId();
    }
    /**
     * 
     * @param unknown $p1
     * @param unknown $p2
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    
    protected  function createBundle($p1,$p2,$ops,$rental,$depo){
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // instance of object manager
        $sku=time();
        
        
/**@var $p1  \Magento\Catalog\Api\Data\ProductInterface */
        
        
        
        $links=[];    
        
        
        
    /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
    $product = $objectManager->create(\Magento\Catalog\Api\Data\ProductInterface::class);
    
        
        /** @var \Magento\Catalog\Api\Data\ProductExtensionInterface $productExtension */
//        $productExtension = $objectManager->create(\Magento\Catalog\Api\Data\ProductExtensionInterface::class);

        
        
        
/**@var $product  */        
        
        
        
    $product->setTypeId('bundle')->setTaxClassId($p1->getTaxClassId())
    ->setAttributeSetId(4)->setStoreId($this->helper->getStoreManager()->getStore()->getId())
//        ->setWebsiteIds([$this->helper->getStoreManager()->getStore()->getWebsiteId()])
        ->setName($p1->getName())
        ->setSku('res-'.$p1->getId().'-'.$ops)
        ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE)
        ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
        //->setStockData(['use_config_manage_stock' => 0, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
        ->setPriceView(1)
    //    ->setPriceType(1)
        ->setShipmentType(1)
        ->setPriceType(0)->setWeightType(1)->setWeight($p1->getWeight());
     
      //  $stockData = ['backorders' => 0,
        //    'use_config_backorders' => 1];
        
       // $product->setStockData($stockData);
        $this->productRepo->save($product,true);
        $this->helper->log("created produdt id ".$product->getId());
        /**@var $product \Magento\Bundle\Model\Product\Type */
       
        $product->setBundleOptionsData(
            [
                [
                    'title' => 'Rental',
                    'default_title' => 'Rental',
                    'type' => 'select',
                    'required' => 1,
                    'delete' => '',
                ],
                [
                    'title' => 'deposit',
                    'default_title' => 'deposit',
                    'type' => 'select',
                    'required' => 1,
                    'delete' => '',
                ],
            ]
            )
            ->setBundleSelectionsData(
                [
                    [
                        [
                            'product_id' => $p1->getId(),
                            'selection_qty' => 1,
                            'selection_can_change_qty' => 0,
                            'delete' => '',
                            'selection_price_type' => 1,
                            'selection_price_value' => $rental,
//                            'option_id' => '',
   //                         'selection_id' => '',
                        ],
                    ],
                    [
                        [
                            'product_id' => $p2->getId(),
                            'selection_qty' => 1,
                            'selection_can_change_qty' => 0,
                            'delete' => '',
                            'selection_price_type' => 1,
                            'selection_price_value' => $depo,
  //                          'option_id' => '',
//                            'selection_id' => '',
                        ],
                    ]
                ]
                );
            
            if ($product->getBundleOptionsData()) {
                $options = [];
                foreach ($product->getBundleOptionsData() as $key => $optionData) {
                    
                    $this->helper->log("bundeloptiondata key $key  data ".print_r($optionData,1));
                    if (!(bool)$optionData['delete']) {
                        $option = $objectManager->create(\Magento\Bundle\Api\Data\OptionInterfaceFactory::class)
                        ->create(['data' => $optionData]);
                        $option->setSku($product->getSku());
                        $option->setOptionId(null);
                        
                        $links = [];
                        $bundleLinks = $product->getBundleSelectionsData();
                        if (!empty($bundleLinks[$key])) {
                            $this->helper->log("processing key $key count ".count($bundleLinks[$key]));
                            foreach ($bundleLinks[$key] as $linkData) {
                                if (!(bool)$linkData['delete']) {
                                    /** @var $link \Magento\Bundle\Api\Data\LinkInterface */
                                    $link = $objectManager->create(\Magento\Bundle\Api\Data\LinkInterfaceFactory::class)
                                    ->create(['data' => $linkData]);
                                    
                                    $linkProduct = $linkData['product_id']==$p1->getId()?$p1:$p2;
                                    $this->helper->log("added to $key ".$linkData['product_id']);
                                    $link->setSku($linkProduct->getSku());
                                    $link->setQty(1);
                                
                                    $link->setPriceType(\Magento\Bundle\Api\Data\LinkInterface::PRICE_TYPE_FIXED);
                                    $link->setCanChangeQuantity(false);
                                    $this->helper->log("create:setprice of ".$linkData['product_id']." = ".$linkData['product_id']==$p1->getId()?$rental:$depo);
                                    $link->setPrice($linkData['product_id']==$p1->getId()?$rental:$depo);
                                    $links[] = $link;
                                }
                            }
                            $this->helper->log("added to option $key ".count($links));
                            $option->setProductLinks($links);
                            $options[] = $option;
                        }
                    }
                }
                $this->helper->log("no of options ".count($options));
                $extension = $product->getExtensionAttributes();
                $extension->setBundleProductOptions($options);
                $product->setExtensionAttributes($extension);
            }
        
        
        
        
        
        
        
        
        $product->setData('rental_bundle',1);
        
        
        $this->productRepo->save($product,true);
        
        
        
        $productExtension=$product->getExtensionAttributes();
        
        
        /** @var \Magento\Bundle\Api\Data\OptionInterface $option */
        
        /** @var \Magento\CatalogInventory\Api\Data\StockItemInterface $stockItem */
        /*
        $stockItem = $objectManager->create(\Magento\CatalogInventory\Api\Data\StockItemInterface::class);
        
        $stockItem->setUseConfigManageStock(false);
        $stockItem->setManageStock(false);
        $stockItem->setIsInStock(true);
        $stockItem->setQty(9999);
        

        
        $productExtension->setStockItem($stockItem);
        
        
        $product->setExtensionAttributes($productExtension);
        */
        $this->productRepo->save($product, true);
        $this->helper->log("created bundle1 ".$product->getId());
        
        $product=$this->productRepo->getById($product->getId());
        $stockData=10;        
//        $product->setStockData(['qty' => $stockData, 'is_in_stock' => $stockData]);
        
        
        $product->setStockData(array(
            'use_config_manage_stock' => 0, //'Use config settings' checkbox
            'manage_stock' => 1, //manage stock
            'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
            'max_sale_qty' => 1, //Maximum Qty Allowed in Shopping Cart
            'is_in_stock' => 1, //Stock Availability
            'qty' => 100 //qty
        )
            );
        $product->setQuantityAndStockStatus(['qty' => 1, 'is_in_stock' => true]);
        $this->productRepo->save($product, true);
        
        
        
        
        return  $product;
        
    }
    

    
    
    /**
     * Get product object based on requested product information
     *
     * @param   Product|int|string $productInfo
     * @return  \Magento\Catalog\Model\Product Product
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getProduct($productInfo)
    {
        $product = null;
        if ($productInfo instanceof \Magento\Catalog\Model\Product) {
            $product = $productInfo;
            if (!$product->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t find the product.'));
            }
        } elseif (is_int($productInfo) || is_string($productInfo)) {
            $storeId = $this->helper->getStoreManager()->getStore()->getId();
            try {
                $product = $this->productRepo->getById($productInfo, false, $storeId);
            } catch (NoSuchEntityException $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t find the product.'), $e);
            }
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t find the product.'));
        }
        $currentWebsiteId = $this->helper->getStoreManager()->getStore()->getWebsiteId();
        if (!is_array($product->getWebsiteIds()) || !in_array($currentWebsiteId, $product->getWebsiteIds())) {
            throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t find the product.'));
        }
        return $product;
    }
    
    private function _check($sku,$requestInfo){
        $this->helper->log("this skuy $sku\n");
        list($rr,$pid,$sy,$sm,$sd,$sr,$skus,$dp)=explode('-',$sku);
        
        $opstr=$opts=$requestInfo['rental_option'];
        list($yr,$mo,$dt,$dd)=explode('-',$opstr);
        $this->helper->log("f: $rr - $pid ; $sy - $sm - $sd - $sr : $yr - $mo - $dt - $dd \n");
        
        if($yr!=$sy||(int)$mo!=(int)$sm||(int)$dt!=(int)$sd||(int)$sr!=$dd){
            $this->helper->log("invali df: $rr - $pid ; $sy - $sm - $sd - $sr : $yr - $mo - $dt - $dd \n");
            throw new \Magento\Framework\Exception\LocalizedException(__('Can\'t add products for 2 dates.'));
        }
        
        
    }
    
    /**
     * Add product to shopping cart (quote)
     * @param $subject
     * @param int|\Magento\Catalog\Model\Product $productInfo
     * @param \Magento\Framework\DataObject|int|array $requestInfo
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    
    
    public function aroundAddProduct($subject,\Closure $proceed, $productInfo, $requestInfo = null)
    {
        $con=false;
        $mainid="";
        $subid="";
        $opstr="";
        
        
        
        
        /**@var  $subject \Magento\Checkout\Model\Cart */
        
       
        
        if(!$this->helper->getCustomerId()){
            
            throw new LocalizedException(__('Not logged in.'));
        }
        
        try{
            
        $productInfo=$this->_getProduct($productInfo);
       
        
        if(!in_array($productInfo->getTypeId(),[AccessoryType::TYPE_CODE,ClothType::TYPE_CODE])){
            $this->helper->log("\n not multiv:added \n");
           return $proceed($productInfo,$requestInfo);
            
        }
        $items=$subject->getItems();
        
        foreach($items as $it){
  //          $this->helper->log("current product ".$it->getId()."\n");
            $pr=$it->getProduct();
            $this->helper->log("sku ".$pr->getSku()."\n");
            if(strpos($pr->getSku() ,'res-')===0){
                $this->helper->log("\n already :added \n");
                $this->_check($pr->getSku(), $requestInfo);
//              
   //               return $proceed($productInfo,$requestInfo);
///                throw new LocalizedException(__("Only One rental item can be added."));
                
            }
            
        }
        
        
        if(is_object($requestInfo)){
        $opts=$requestInfo->getData('rental_option');
        $opstr=$opts;
        $subject->getQuote()->getExtensionAttributes()->getRentalData()->setRentalDates($opstr);
        
        $depo=$productInfo->getData('deposit')??11;
        list(,,,$dd)=explode('-',$opstr);
        $rental=$dd!=8?$productInfo->getData('rent4'):$productInfo->getData('rent8');

        $vipdisc=$dd!=8?$productInfo->getData('vip_discount'):$productInfo->getData('vip_discount8');

        
        
        $sub=$requestInfo->getData('multiv_sub');
        }else if(is_array($requestInfo)){
            
            $rr=print_r($requestInfo,1);
            $this->helper->log("\nmultiv:".$rr."\n");
            if(!isset($requestInfo['rental_option'])){
                throw new LocalizedException("Invalid options");
                
            }
            
            
            $opstr=$opts=$requestInfo['rental_option'];
            list(,,,$dd)=explode('-',$opstr);
            $rental=$dd!=8?$productInfo->getData('rent4'):$productInfo->getData('rent8');

            $vipdisc=$dd!=8?$productInfo->getData('vip_discount'):$productInfo->getData('vip_discount8');
            $depo=$productInfo->getData('deposit')??11;
            $owner=$productInfo->getData('uid');
            $cid=$this->helper->getCustomerId();
            
            $sub=isset($requestInfo['multiv_sub'])?$requestInfo['multiv_sub']:'';
          if(!$opts)            throw new LocalizedException("Invalid options");
           $qid=$subject->getQuote()->getId();
            $qid2=$this->cart->getQuoteId();
           $s= true;
           if(!$qid){
               $this->helper->log("createquote: cannot add qid2 $qid2 qid $qid");
               throw new LocalizedException(__('Cannot add'));
           }
           
           $wash=(int)$productInfo->getData('wash');
           $this->helper->log("createquote: rental $rental depo $depo qid $qid qid2 $qid2 vip $vipdisc");
           $con=$this->helper->reserve($opts,$productInfo->getId(),$qid,$owner,$cid,$rental,$depo,$wash,$vipdisc);
           if(!$con){
               
               throw new LocalizedException(__("Cannot be added again."));
               
           }
            
        }else{
            throw new LocalizedException(__("Invalid options"));
        }
            
          $mainid=$productInfo->getId();
          $this->helper->log("mainid $mainid");
          $this->helper->saveSession('rental', $rental);
          $this->helper->saveSession('depo', $depo);
   //     $this->helper->addStock($productInfo);
        $prdd=$this->productRepo->get('deposit');
        
        $subid=$prdd->getId();
 //       $this->helper->addStock($prdd);
        $this->helper->log("create:creating bundle mainid $mainid subid $subid rental $rental depo $depo");

        if($subject->getQuote()->getExtensionAttributes()==null){
            $cartExtension = $this->cartExtensionFactory->create();
            $subject->getQuote()->setExtensionAttributes($cartExtension);
//            file_put_contents('lastq.txt','lastset');
            $subject->getQuote()->getExtensionAttributes()->setRentalData($this->qo);

        }

        $rental=$rental;///1.21;
        $depo=$depo;///1.21;





        $subject->getQuote()->getExtensionAttributes()->getRentalData()->setLastRequest($dd,$rental,$depo);
        $subject->getQuote()->getExtensionAttributes()->getRentalData()->setRentalDates($opstr);
        $_product=$this->createBundle($productInfo, $prdd,$opstr,$rental,$depo);
///        $this->helper->updateProductStock($_product);        
        $bundleid=$_product->getId();
        $this->helper->log("created $bundleid");
        // get selection option in a bundle product
        $selectionCollection = $_product->getTypeInstance(true)
        ->getSelectionsCollection(
            $_product->getTypeInstance(true)->getOptionsIds($_product),$_product);
        
        // create bundle option
        $cont = 0;
        $selectionArray = [];
        foreach ($selectionCollection as $proselection){
            $this->helper->log(" selection ".get_class($proselection)."\n");
            $selectionArray[$cont] = $proselection->getSelectionId();
            $cont++;
        }
        // get options ids
        $optionsCollection = $_product->getTypeInstance(true)
        ->getOptionsCollection($_product);
        $bos=[];   
        foreach ($optionsCollection as $options) {
            /**@var $options \Magento\Bundle\Api\D/ata\BundleOptionInterface */
           
///            $links=$options->getProductLinks();
   ////         $lnks=print_r($links,1);
            $id_option = $options->getId();
            
            $sel=$this->helper->getSel($id_option);
           
            
            
            $bos[$id_option]=[$sel];
            $this->helper->log("create: id $id_option sel $sel");
            
            
        }
        
        
        $params = [
            'product' => $_product->getId(),
            'bundle_option' => $bos,
            'qty' => 1
        ];                
        
        $parentid=$_product->getId();
        
        $requestInfo['product']=$_product->getId();
        $requestInfo['bundle_option']=$bos;
        $requestInfo['qty']=1;
        $bss=print_r($bos,1);
        $this->helper->log("create: adding to caert bos: $bss ");
        /**@var $result \Magento\Checkout\Model\Cart */
        ///$cart->addProduct($productInfo);
        
        /**@var $item \Magento\Quote\Model\Quote\Item */
        
 
        $result=$proceed($_product,$requestInfo);
        $this->helper->log("addeds");
        
       
       
        foreach($result->getItems() as $item ){
            
            if($item->getProductId()==$bundleid){

                
                $options = $item->getOptions();
                foreach ($options as $option)
                {
                    if ($option->getCode() == 'bundle_selection_attributes')
                    {
                        $oo=$option->getValue();
                        $this->helper->log("created: option bundle $oo");
                        
                      ///  $unserialized = unserialize($option->getValue());
                        //$unserialized['price'] = number_format($rental+$depo, 2, '.', ',');
                       /// $option->setValue(serialize($unserialized));
                    }
                }
                try
                {
///                    $item->setOptions($options)->save();
                }
                catch (\Exception $e)
                {}
                
                $item->setCustomPrice($rental+$depo);
                $item->setOriginalCustomPrice($rental+$depo);
                $this->helper->log("create:save bundle   $rental dep $depo");

        foreach($item->getChildren() as $it){
            
            $it->setIsSuperMode(true);
            if($it->getProduct()->getId()===$mainid){
                $it->setCustomPrice($rental);
                $it->setDiscountCalculationPrice($rental);
                
                $it->setOriginalCustomPrice($rental);
                $it->getProduct()->setIsSuperMode(true);
                $it->setFinalPrice($rental);
                $this->helper->log("create:set price ".$it->getId()." price parentid ".$item->getId()." paritid ".$item->getItemId());


                $options = $it->getOptions();
                foreach ($options as &$option)
                {
                    if ($option->getCode() == 'bundle_selection_attributes')
                    {
                        $oo=$option->getValue();    
                        $this->helper->log("created:  $mainid rental $oo");
                        
                        $unserialized = json_decode($option->getValue(),true);
                        $unserialized['price'] = number_format($rental, 2, '.', ',');
                        $option->setValue(json_encode($unserialized));
                        
                    }
                }
                try
                {
                    $it->setOptions($options);
                    ///                    $item->setOptions($options)->save();
                }catch(\Exception $e){
                    
                }
                
                
            }

            else if($it->getProduct()->getId()==$subid){
                $it->setCustomPrice($depo);
                $it->setDiscountCalculationPrice(0);
                $it->setOriginalCustomPrice($depo);
                $it->getProduct()->setIsSuperMode(true);
                $this->helper->log("create:set price ".$it->getId()." price  parentid ".$item->getId()." paritid ".$item->getItemId());
                
                $options = $it->getOptions();
                $it->setFinalPrice($depo);
                foreach ($options as &$option)
                {
                    if ($option->getCode() == 'bundle_selection_attributes')
                    {
                        $oo=$option->getValue();
                        $this->helper->log("created:  $subid deposi9teopt $oo");

                         $unserialized = json_decode($option->getValue(),true);
                         $unserialized['price'] = number_format($depo, 2, '.', ',');
                        $option->setValue(json_encode($unserialized));
                              
                    }
                }
                try
                {
                    $it->setOptions($options);
                    ///                    $item->setOptions($options)->save();
                }
                catch (\Exception $e)
                {}
            
            }
            
        }
            
            
            }
            }
            
           
           
        /*
        foreach($result->getItems() as $item ){
            
            
            $b=$item->getData('info_buyRequest');
            $co=$item->getCustomOption();
            $bo=$item->getBuyRequest();
            $bor="";
            $this->helper->log("adding item");
            
            if($bo){
             //   $bo=get_class($bo);
              
                $bor=$bo->getData('rental_option');
                
            }
            $parid=$item->getParentItemId();
            $ppid="";
            $bsku='';
            $itsku=$item->getProduct()->getSku();
            $itid=$item->getId();
            
            if($parid){
                $ppid=$item->getParentItem()->getId();
                $bsku=$item->getParentItem()->getProduct()->getSku();
            }
            
            file_put_contents('around.txt', "item $itid parient $parid ppid $ppid bundle $bundleid mainid $mainid itemsku $itsku busku $bsku \n ",FILE_APPEND);
            if($item->getProductId()==$mainid&&$bor==$opstr){
                
                
                if($item->getParentItem()&& $item->getParentItem()->getProduct()->getId()==$bundleid){
                
                $item->setCustomPrice($rental);
                $item->setOriginalCustomPrice($rental);
                $skux=$item->getProduct()->getSku();
                   file_put_contents('around.txt', "setting price of $mainid $skux parid $parid \n ",FILE_APPEND);
                $item->getProduct()->setIsSuperMode(true);
                }
                
            }
            if($item->getProductId()==$subid&&$bor==$opstr){
              //  $parid=$item->getParentItemId();
                if($item->getParentItem()&& $item->getParentItem()->getProduct()->getId()==$bundleid){
                $item->setCustomPrice($depo);
               $item->setOriginalCustomPrice($depo);
                $item->getProduct()->setIsSuperMode(true);
                }
                
            }else if($item->getProductId()==$bundleid){
///                $this->helper->log("dditem $dd rental $rental depo $depo bor $bor itemid ".$item->getProductId()." opstr $opstr");
    ///            $item->setCustomPrice($depo+$rental);
        ///        $item->setOriginalCustomPrice($depo+$rental);
            ///   $item->getProduct()->setIsSuperMode(true);
               $item->setIsSuperMode(true);
            }
            
            
        }
        */
        $this->helper->log("committing");
        
        $con->commit();
        $this->helper->log("added to cart");
        $con=false;
        }catch(LocalizedException $e){
            $this->helper->log("error in adding");
            $this->helper->log($e->getMessage());
            $this->helper->log($e->getTraceAsString());
            $this->msgmgr->addErrorMessage($e->getMessage()) ;
///            throw $e;
        }
        catch(\Exception $e){
            $this->helper->log("error in adding:main exception");
        $this->helper->log($e->getMessage());
      $this->msgmgr->addErrorMessage((string)__('Cannot add.')) ;     
///            throw $e;            
        }
        finally {
            if($con){
                $con->rollBack();
            }
        }
     
       return $result??'';        
        
    }
    /*
    public function afterAddProduct($subject, $result)
    {
        $quote = $result->getQuote();
        
        // do something
        // your code goes here
    }*/
    
    
}