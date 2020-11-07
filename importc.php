<?php


require __DIR__ . '/app/bootstrap.php';
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\Product;

/*
 *   'host' => '54.38.71.59',
        'dbname' => 'import_products',
        'username' => 'hOck3yplay3r',
        'password' => 'JV6@M2NMtp+Mgk',
 */

$sqlr=[
 'host' => '54.38.71.59',
        'dbname' => 'import_products',
        'username' => 'hOck3yplay3r',
    'password' => 'JV6@M2NMtp+Mgk',
];




$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

$obj = $bootstrap->getObjectManager();

$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
$defstid=1;
$imgpath='/var/www/hockeyplayer/zip/';
$mainimgp='/var/www/hockeyplayer/public_html/pub/media';
$saveopt=false;
$name1='';
$pcatid='';

/**
 * @param $added
 * @param $sizes
 * @param $objMgr
 * @param $repo
 */
function createConfig($name,$added , $sizes,$objMgr,$repo,$attribute,$attributeSetId,$datas,$wt,$scsr,$dsr,$limgs,$stores,$idcat){
 $resource = $objMgr->get('Magento\Framework\App\ResourceConnection');

    $connection = $resource->getConnection();

    unset($datas['code_barre']);

    logx(print_r($datas,1)." catid ".$idcat."\n","myconfig.txt");

    /** @var $product Product */
    $product = $objMgr->create(Product::class);
    /** @var Factory $optionsFactory */
    $optionsFactory = $objMgr->create(\Magento\ConfigurableProduct\Helper\Product\Options\Factory::class);

    $configurableAttributesData = [
        [
            'attribute_id' => $attribute->getId(),
            'code' => $attribute->getAttributeCode(),
            'label' => $attribute->getStoreLabel(),
            'position' => '0',
            'values' => $sizes,
        ],
    ];
    $configurableOptions = $optionsFactory->create($configurableAttributesData);
    $extensionConfigurableAttributes = $product->getExtensionAttributes();
    $extensionConfigurableAttributes->setConfigurableProductOptions($configurableOptions);
    $extensionConfigurableAttributes->setConfigurableProductLinks($added);
    $product->setExtensionAttributes($extensionConfigurableAttributes);
    $product->setTypeId(\Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE)
        ///->setId(1001)
        ->setAttributeSetId($attributeSetId)
        ->setWebsiteIds([0,1])
        ->setName($name)
        ->setMetaTitle($name)
        ->setMetaKeyword($name)
        ->setMetaDescription($name)
        ->setDescription($dsr)
        ->setWeight($wt)
        ->setShortDescription($scsr)
        ->setUrlKey($name.'-'.time())
        ->setSku(Md5('config-'.implode('-',$added)))
        ->setVisibility(Visibility::VISIBILITY_BOTH)
        ->setStatus(1);
       // ->setStockData(['use_config_manage_stock' => 1, 'is_in_stock' => 1]);

    $product->setCategoryIds([(int)$idcat]);
   $fut=[];
    foreach ($datas as $code=>$val){
        logx("code $code val $val\n","configattr.txt");
        if($code=='color'||$code=='gender'){
            $fut[$code]=$val;
            continue;
        }

        if($code=='size'){
            continue;
        }
        if($val) {
            //echo "setconfig $code $val\n";
            $product->setData($code, $val);
        }
    }


      $product->setWebsiteIds([0,1])->setStoreId(0)->setName($name);

    $entries = [];
    $i = 0;

    try {

        if (count($limgs)) {



            $entryFactory = $objMgr->create(\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterfaceFactory::class);
            $files = [];
            $image = [];
            $i=0;
            foreach($limgs as $img=>$imgo) {


                        $img=preg_replace('/[^a-z0-9\-]/i','',$img);

                        copy($imgo, 'pub/media/catalog/product/import/' . $img . '_config.jpg');

                        $imgt='/catalog/product/import/' . $img . '_config.jpg';

            ////  copy($imgo, 'pub/media/catalog/product/import/' . $img . '_config.jpg');


                //$s = $imgpath . $img.'.jpg';
                $files[$i] = $imgt;
                $image['label'] = $name;
                $mediaEntry = $entryFactory->create();
                $mediaEntry->setMediaType('image');
                $mediaEntry->setLabel($image['label']);
                $entries[$i++] = $mediaEntry;
            }
                addImagesToProductGallery($product, $entries, $files, $objMgr,$stores);

          //  $product = $prepo->save($product, true);
            echo "configimages saved ".$product->getSku()." file $imgt pimg  ".$product->getImage()." \n";
            /*
            if($product->getImage()&&$product->getImage()!=='no_selection') {
                $product->setThumbnail($product->getImage());
                $product->setSmallImage($product->getImage());
            }else{
                if($imgt){
                    echo "adding image thumb\n";
                    $product->setImage($imgt);
                    $product->setThumbnail($imgt);
                    $product->setSmallImage($imgt);
                }
            }
            */
        ///    $prepo->save($product,true);
        }

    }catch (\Exception $e){
        echo "error in image \n";
        echo $e->getMessage()."\n#####\n";


        $e->getTraceAsString();
       // exit(0);
        $img='';
   ///     $imgt='';
    }

    $product=$repo->save($product,false);

    echo "configsave1 ".$product->getId()."  \n";
try {
        foreach ($stores as $store) {
            $stid = $store['id'];
            foreach ($store['vars'] as $code => $value) {
                $product->addAttributeUpdate($code, $value, $stid);
            }
          //   $product=$this->repo->save($product);
        }
    }catch(\Exception $e){
        echo "error in store\n";
        echo $e->getMessage()."\n######\n";
        echo $e->getTraceAsString();
        exit(0);
    }

    $repo->save($product,false);

    $log=[];
    foreach ($fut as $code=>$v) {
        $log[]="$code: $v";

        $attrid=  $connection->fetchOne("select attribute_id from  eav_attribute where entity_type_id=4 and  attribute_code='$code'");

       // echo "save $code attr $attrid pid ".$product->getId()." val $v\n";

 $val= $connection->fetchOne("select value_id from  catalog_product_entity_int where attribute_id='$attrid' and store_id=0 and entity_id=".$product->getId());

 if($val){

               ////echo "update val $val v $v \n";
               $connection->update('catalog_product_entity_int',['value'=>$v],"value_id=".$val);

           } else {


               $connection->insert('catalog_product_entity_int', [
                   'attribute_id' => $attrid,
                   'value' => $v,
                   'store_id' => 0,
                   'entity_id' => $product->getId()
               ]);
           }

    }

    $log=implode(" ",$log);


    echo "savedconfig ".$product->getId()." dsr: $dsr  log: $log\n";
   /// echo $product->getId();
   // die("\nok\n");

}
function addImagesToProductGallery(ProductInterface $product, $entries, $files,$objMgr,$stores)
    {
        global $mainimgp;

        $data = [];
        $first=true;
 $last='';
        foreach ($files as $key => $file) {
$last=$key;
 }

///        $product->setMediaGallery (array('images'=>array (), 'values'=>array ()));


        foreach ($files as $key => $file) {
            $entry = $entries[$key];
            echo "getconfigcontent  ".$mainimgp.$file."\n";
            $imageData = file_get_contents($mainimgp.$file);
            $base64EncodedData = base64_encode($imageData);
            $imageContent=$base64EncodedData;

            if (!$imageContent) {
                unset($entries[$key]);
                unset($files[$key]);
                echo "no content $mainimgp$file\n";
              //  exit(0);
                continue;
            }

            $fileInfo = pathinfo($file);
 ///           $fileData = $this->remover->resolveFilePrefix($file['file']);
    ///        $filename = $fileData['prefix'] . $fileInfo['basename'];
            /**@var $product \Magento\Catalog\Model\Product */
            $imageType = exif_imagetype($mainimgp.$file);

            try {
                $pid=$product->getSku();
                if ($first) {
                    $first=false;
                        echo "adding image $imageType file $file prodduct  sku:$pid\n";
                    $product->addImageToMediaGallery($file,['image','small_image','thumbnail'], false, false);
                    // $product->setThumbnail($file);
                     //$product->setImage($file);
                     //$product->setSmallImage($file);



                } else {
                    echo "other image $file  sku: $pid\n";

                    $product->addImageToMediaGallery($file, null, false, false);

                }


                /**@var $ic \Magento\Framework\Api\Data\ImageContentInterface */
                $ic=$objMgr->create(\Magento\Framework\Api\Data\ImageContentInterface::class);
                $ic->setBase64EncodedData($base64EncodedData);
                $ic->setName(basename($file));

                $mimeType = image_type_to_mime_type($imageType);

                $ic->setType($mimeType);

                $entry->setPosition($key);
                $entry->setFile(basename($file));
                $entry->setContent($ic);

                $entries[$key] = $entry;

                foreach ($stores as  $storeId=>$store) {
                    $product->setStoreId($store['id'])
                        ->setImage($file)
                        ->setSmallImage($file)->setThumbnail($file);
                }

                logx($product->getSku()." $file\n","imgs.txt");

                ///echo "added entry $key \n";




            } catch (\Exception $ex) {

                file_put_contents('badimage.txt',$file."  ".$product->getSku()."\ns",FILE_APPEND);
                echo $ex->getTraceAsString()."\n\n";
                //echo $ex->getMessage();
                    ///exit(0);
            }


        }

    $product->setMediaGalleryEntries($entries);


    }



//$quote = $obj->get('Magento\Checkout\Model\Session')->getQuote()->load(1);
//print_r($quote->getOrigData());


/** @var \Magento\Framework\App\Http $app */
//$app = $bootstrap->createApplication('TestApp');
//$bootstrap->run($app);
/*
 * serial, field, table , attribute
 */



$c=mysqli_connect($sqlr['host'],$sqlr['username'], $sqlr['password'],$sqlr['dbname']);
 $resource = $obj->get('Magento\Framework\App\ResourceConnection');

    $connection = $resource->getConnection();

    $connection->query("set FOREIGN_KEY_CHECKS=0;")->execute();

    $connection->query("truncate catalog_product_entity_media_gallery")->execute();

    $connection->query("truncate catalog_product_entity_media_gallery_value")->execute();

    $connection->query("truncate catalog_product_entity_media_gallery_value_to_entity")->execute();

$map=[];
$bmap=[];


$result=mysqli_query($c,"select id,size,color, name_fr from import_products.csv_to_import order by name_fr ,color,size ") or die('You need to add id auto increment column to this table ');

while($line= mysqli_fetch_assoc($result)) {
$s=[];
    $s['color']=trim($line['color']);
$id=$line['id'];

$name1=trim($line['name_fr']);

$s['size']=trim($line['size']);

$colors=[];
$sizes=[];
$msizes=[];
foreach ($s as $code=>$value) {
    $attrid = $connection->fetchOne("select attribute_id from  eav_attribute where entity_type_id=4 and  attribute_code='$code'");

    $value=preg_replace('/["\']/','',$value);
    ///echo  " code $code id $attrid\n";
    $value=trim($value);
echo "select eo.option_id from eav_attribute_option eo inner  join  eav_attribute_option_value ev  on ( eo.option_id= ev.option_id ) and ev.store_id=0 and ev.value='$value'  and eo.attribute_id='$attrid' \n";
    $v = $connection->fetchOne("select eo.option_id from eav_attribute_option eo inner  join  eav_attribute_option_value ev  on ( eo.option_id= ev.option_id ) and ev.store_id=0 and ev.value='$value'  and eo.attribute_id='$attrid' ");
    if (!$v) {

        $connection->insert('eav_attribute_option', [
            'attribute_id' => $attrid,
            'sort_order' => 101


        ]);
        $optid = $connection->lastInsertId();

        echo "created $code: $value  : $optid \n";
        $connection->insert('eav_attribute_option_value', [
            'store_id' => 0,
            'option_id' => $optid,
            'value' => $value
        ]);
        $v = $optid;
        if($code=='color')
        $colors[$value]=$optid;
        else if($code=='size')
         $msizes[$value]=$optid;

    }


}

 $name1 = str_replace($s['color'],'',$name1);
    $name1= str_replace($s['size'], '',$name1);
   mysqli_query($c,"update import_products.csv_to_import set name_fr= '".mysqli_escape_string($c,trim($name1))."'   where id=".$id) or die(mysqli_error($c));

}





$pfactory = $obj->create(ProductFactory::class );
$prepo= $obj->create(ProductRepositoryInterface::class);

$eavConfig = $obj->get('Magento\Eav\Model\Config');
$sattribute = $eavConfig->getAttribute('catalog_product', 'size');

$options = $sattribute->getOptions();
array_shift($options); //remove the first option which is empty

$attributeValues = [];
//print_r($options);
echo "attribute ".$sattribute->getId()."\n";


foreach ($options as $option) {
    $attributeValues[] = [
        'label' => 'Size',
        'attribute_id' => $sattribute->getId(),
        'value_index' => $option->getValue(),
    ];
}


$installer = $obj->create('Magento\Catalog\Setup\CategorySetup');

/** @var ProductRepositoryInterface $productRepository */
$productRepository = $obj->create(ProductRepositoryInterface::class);
/** @var $installer CategorySetup */
///$installer = $ob->create(CategorySetup::class);
$set = $installer->getAttributeSetId('catalog_product', 'Default');

echo "SET $set\n";

//exit(0);


$ll=0;
$nocats=[];
$i=0;

$storevars=[];
/*
ID categorie
Product title FR
Product title NL
Product title EN
SKU
Supplier 1
Code supplier 1
Size
Color
Gender
EAN code1
Dealerprice
Retailprice
Retailprice ex,VAT
VAT
Coef
Long Description FR
Short Description FR
Long Description NL
Short Description NL
Long Description EN
Short Description EN
Image Name
Weight (Kg)
 */

$pname='';
$added=[];
$medata=[];
$dedata=[];
$dimgs=[];
$pcolor1='';
$pg1='';
$c_dsr='';
$c_sdsr='';
$c_wt='';
function logx($str,$fl){
    file_put_contents($fl,$str,FILE_APPEND);
}

$result=mysqli_query($c,"select * from import_products.csv_to_import order by name_fr ,color,gender,size ");
 $resource = $obj->get('Magento\Framework\App\ResourceConnection');
    /**@var $resource \Magento\Framework\App\ResourceConnection */
    $connection = $resource->getConnection();
$cdata=[];

while($line= mysqli_fetch_assoc($result)) {
    $gd='';
   /// $cdata=[];
    $stores = [];
    $datas = [];
    $gen=['size','color','gender','coef'];
    $sites=['code_barre'];

    $stores['fr'] = ['id' => 1, 'vars' => []];
    $stores['en'] = ['id' => 10, 'vars' => []];
    $stores['nl'] = ['id' => 9, 'vars' => []];
    $spds=[];
    $sdata=[];
    $i = 0;
    $img = '';
    $idcat = '';
    $sku = '';
    $br = [];
    $wt = 0;
    $price = 0;
    $sizes=[];
    $dscr1=$sdscr1='';
    $cntt=0;
    $color='';
    $size='';
    $imgto='';
    foreach ($line as $k=>$ln) {
        $lno=$ln;
        //$size=$sku=$color=$price=$wt=$idcat=$img='';
        $ln=preg_replace('/"/','',$ln);
        $ln=trim($ln);
       // $ln=tri($ln);
        $ln = preg_replace('/\\s+$|^\\s+/', '', $ln);

        if(preg_match("/^x_/i",$k)){
            continue;
        }
        if($k=='size'){

            if(!$ln){

                $sizes[]='size_'.$cntt;
            }else{
                $sizes[]=$ln;
            }
            $size=$ln;
        }
        if($k=='color'){
            $color=$ln;
        }
        else if($k=='gender'){
            $gd=$ln;
        }

       else if(preg_match("/^(.*)_(fr|nl|en)$/i",$k,$mat)){

           $stores[$mat[2]]['vars'][$mat[1]]=$lno;

           if($mat[1]=='name'&&$mat[2]=='fr'){
               $name1=$lno;
           }
            if($mat[1]=='description'&&$mat[2]=='fr'){
               $dscr1=$lno;
           }
            if($mat[1]=='short_description'&&$mat[2]=='fr'){
               $sdscr1=$lno;
           }




        }
        else if(in_array($k,$gen)){

            $datas[$k]=$ln;


        }else if( preg_match('/^sp_/',$k)){
                $br[$k]=$ln;

        }else if(in_array($k,$sites)){
                $sdata[$k]=$ln;

        }else if($k=='price'){
            $price=floatval($ln);
        }
        else if($k=='sku'){
            $sku=trim($ln);
        }
        else if($k=='weight'){
            $wt = $ln;
        }else if($k=='image'){
           $img=$ln;
        }
        else if($k=='ID_cat'){
            $idcat=$ln;
        }
       // https://mage2.pro/t/topic/2165/3
//https://github.com/magento/magento2/blob/2.1/dev/tests/integration/testsuite/Magento/ConfigurableProduct/_files/product_configurable.php

    }
    $datas['color']=$color;
    $datas['gender']=$gd;


    if(!$name1||!$sku){

        echo "no name for sku $sku name $name1 \n";
        continue;
    }

    $imgo = $imgpath . $img.'.jpg';
    if(!file_exists($imgo)){
        $img=false;
    }
    $imgto='';
    $imgt='';
    if($img) {
        $img=preg_replace('/[^a-z0-9\-]/i','',$img);
        copy($imgo, 'pub/media/catalog/product/import/' . $img . '.jpg');

        $imgt = '/catalog/product/import/' . $img . '.jpg';
        copy($imgo, 'pub/media/catalog/product/import/' . $img . '_config.jpg');
        $imgto = '/catalog/product/import/' . $img . '_config.jpg';
        if(!file_exists('pub/media/catalog/product/import/' . $img . '_config.jpg')){
            $o=file_exists('pub/media/catalog/product/import/' . $img . '.jpg')?"y":'N';

            logx( "$imgto   $imgt O $o\n" ,"noimg.txt");
            $imgto='';
        }
        else {
        ///    echo "not copied configimg\n";

        }

    }

    try{

     $product=   $prepo->get($sku);
     if(!is_null($product)&&$product->getId()){
         echo "exist $sku \n";
         continue;
     }

    }catch (\Exception $e)
    {

        ///echo "error ".$e->getMessage()."\n";

    }
 if($img&&!file_exists($mainimgp.$imgt)){
        $img='';
        file_put_contents('badimage.txt', "$imgt $sku\n",FILE_APPEND);
        echo "no image $imgt\n";
       /// exit(0);
    }

    $category = $obj->create('Magento\Catalog\Model\Category')
        ->load($idcat);

    if(is_null($category)||!$category->getName()){

        $idcat=false;
        echo "NO CATEGORY $idcat line $ll \n";
        file_put_contents("nocats.txt","line:  productline $ll sku $sku\n ",FILE_APPEND);
       /// continue;
    }
    $ll++;



    $product = $pfactory->create();


    $product->setStatus(1); // Status on product enabled/ disabled 1/0


    $storeManager = $obj->create(\Magento\Store\Model\StoreManagerInterface::class);

    /** @var $storeManager  \Magento\Store\Model\StoreManagerInterface */


    /**
     * name,
     * short_description
     * description
     *
     */


    /** @var $product \Magento\Catalog\Model\Product */
    $product->setSku($sku);


///        $product->setData('uid',$uid);
    //$product->setName($name); // Name of Product
    ///echo "\####attrset $set\n";
    $product->setAttributeSetId($set);
//    $product->setUrlKey($sku);
    $product->setWeight($wt); // weight of product
    $product->setVisibility(Visibility::VISIBILITY_NOT_VISIBLE); // visibilty of product (catalog / search / catalog, search / Not visible individually)
    $product->setCustomAttribute('tax_class_id', 2);



    $product->setTypeId('simple'); // type of product (simple/virtual/downloadable/configurable)
    $product->setPrice($price); // price of product

    //$product->setData('size',$map2['f_7']); // price of product
    //$product->setData('color',$map2['f_8']); // price of product
    //$product->setData('gender',$map2['f_9']); // price of product

    $product->setCustomAttribute('tax_class_id', 4);

    $product->setName($name1)->setMetaTitle($name1)->setUrlKey($name1.'-'.time())
        ->setMetaKeyword($name1)
        ->setMetaDescription($name1)
        ->setDescription($dscr1)
        ->setShortDescription($sdscr1);


    $product->setData('price_view', 0);


    $fut=[];
    foreach ($datas as $code => $d) {

            $code=strtolower(trim($code));

            if(in_array($code,['color','size','gender'])){

                $fut[$code]=$d;
                continue;
            }
            if(trim($d)) {
            logx("$code: $d\n","pdata.txt");

            $product->setData($code, trim($d));
            $datas[$code]=trim($d);
            }else{

                $datas[$code]='';
            }
        }
        //141,93,164
    /*
     * eav_attribute => att_id
     * eav_attribute_option => att_id, option_ids
     * eav_attrubute_option_swatch => swatch_id,value,option_id,store_id
     * eav_attribute_option_value  value_id, option_id, store_id,value
     *
     */

    //$product->setData('color',$color); // price of product

    /** @var \Magento\CatalogInventory\Api\Data\StockItemInterface $stockItem */
    //  $stockItem = $objectManager->create(\Magento\CatalogInventory\Api\Data\StockItemInterface::class);
    //$stockItem->setUseConfigManageStock(false);
    //$stockItem->setManageStock(false);
    //$stockItem->setIsInStock(true);
    //$stockItem->setQty(1);

    /** @var \Magento\Catalog\Api\Data\ProductExtensionInterface $productExtension */
    //$productExtension =        $product->getExtensionAttributes();
    ///$objectManager->create(\Magento\Catalog\Api\Data\ProductExtensionInterface::class);
    //$productExtension->setStockItem($stockItem);

    //  $product->setWebsiteIds([1])->setStoreId(0)->setName($name1);

if($idcat)
    $product->setCategoryIds([(int)$idcat]);
    $flags = ['image', 'thumbnail', 'small_image'];
    $files = [];
    $labels = [];
    $gfiles = [];
    $fim = false;
    ///$product->setMediaGalleryEntries(null);

    $product->setWebsiteIds([0,1])->setStoreId(0)->setName($name1);

    $entries = [];
    $i = 0;
    try {

        if ($img) {
            $entryFactory = $obj->create(\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterfaceFactory::class);
            $files = [];
            $image = [];
            //$s = $imgpath . $img.'.jpg';
            $files[0] =$imgt ;
            $image['label'] = $name1;
            $mediaEntry = $entryFactory->create();
            $mediaEntry->setMediaType('image');
            $mediaEntry->setLabel($image['label']);
            $entries[0] = $mediaEntry;

            addImagesToProductGallery($product, $entries, $files, $obj,$stores);
           /// $product = $prepo->save($product, true);
            echo "image saved ".$product->getSku()." file $imgt pimg  ".$product->getImage()." \n";
            if($product->getImage()&&$product->getImage()!=='no_selection') {
                $product->setThumbnail($product->getImage());
                $product->setSmallImage($product->getImage());
            }else{
                if($imgt){
                    echo "adding image thumb\n";
                    $product->setImage($imgt);
                    $product->setThumbnail($imgt);
                    $product->setSmallImage($imgt);
                }
            }
        ///    $prepo->save($product,true);
        }

    }catch (\Exception $e){
        echo "error in image \n";
        echo $e->getMessage()."\n#####\n";


        $e->getTraceAsString();
       // exit(0);
        $img='';
   ///     $imgt='';
    }

      /**@var $prepo ProductRepositoryInterface */
  ///  echo "saving";
 $resource = $obj->get('Magento\Framework\App\ResourceConnection');
    /**@var $resource \Magento\Framework\App\ResourceConnection */
    $connection = $resource->getConnection();




   try {

        $product=        $prepo->save($product, false);

        echo "productsaved1 $sku gender: $gd, color $color, size $size, name $name1 \n";
   }catch (\Exception $e){

        echo $e->getMessage();
        echo "\n#@#####\n";
        echo $e->getTraceAsString();
        exit(0);
    }

$saveopt=false;


    try {
        foreach ($stores as $store) {
            $stid = $store['id'];

            if ($stid == $defstid) {

                  //    $product->save();


            }
            foreach ($store['vars'] as $code => $value) {
                $product->addAttributeUpdate($code, $value, $stid);

            }
            foreach ($sdata as $code=>$value){
                $product->addAttributeUpdate($code,$value,$stid);

            }
    ///        $prepo->save($product, $saveopt);


            //   $product=$this->repo->save($product);

        }
    }catch(\Exception $e){
        echo "error in store\n";
        echo $e->getMessage()."\n######\n";
        echo $e->getTraceAsString();
        exit(0);
    }
   /// $prepo->save($product,false);
    $sz='';
  foreach ($fut as $code=>$value){

      $value=preg_replace('/["\']/','',$value);
     $attrid=  $connection->fetchOne("select attribute_id from  eav_attribute where entity_type_id=4 and  attribute_code='$code'");

            ///echo  " code $code id $attrid\n";

            $v=    $connection->fetchOne("select eo.option_id from eav_attribute_option eo inner  join  eav_attribute_option_value ev  on ( eo.option_id= ev.option_id ) and ev.store_id=0 and ev.value='$value'  and eo.attribute_id='$attrid' ");
             if($code=='size'){
                 $sz=$value;
             }

            if(!$v){

                echo "no option for $code: $value\n";
                logx("no option for $code: $value\n",'noptions.txt');

                if($code=='color'){
                    $color='';
                }
                continue;

                   }


         ///   echo "code $code saving v $v\n";

           $val= $connection->fetchOne("select value_id from  catalog_product_entity_int where attribute_id='$attrid' and store_id=0 and entity_id=".$product->getId());
           if($val){

               ////echo "update val $val v $v \n";
               $connection->update('catalog_product_entity_int',['value'=>$v],"value_id=".$val);

           } else {

               $connection->insert('catalog_product_entity_int', [
                   'attribute_id' => $attrid,
                   'value' => $v,
                   'store_id' => 0,
                   'entity_id' => $product->getId()
               ]);
               $val=$connection->lastInsertId();

           }
            echo "code $code option_id: $v value_id $val\n";
           logx("code $code option_id: $v value_id $val\n","insertoptions.txt");
           $datas[$code]=$v;
   }

    $br['sp_product_id']=$product->getId();

    $name1 = str_replace($color,'',$name1);
    $name1= str_replace($sz, '',$name1);
    $name1=trim($name1);

    if($name1==$pname&&$color==$pcolor1&&$pg1==$gd) {
        $added[] = $product->getId();
        $addedcp[$product->getId()]=$sz;
        if($img&&$imgto) {
            $limgs[$img] = $imgo;
        }
    }else{

        $ct=count($added);
        echo " Trying new group $pname  color $pcolor1 total: $ct \n";
        if(count($added)>1){
              $szss=[];
              $sadded=[];
            foreach ($addedcp as $p=>$s){

                if(in_array($s,$szss)){

                    echo "duplicate $name1: $p : s $sz\n";

                    file_put_contents("dupl.txt","$name1 , $p :  $s\n",FILE_APPEND);
                    continue;
                }
                $szss[]=$s;
                $sadded[]=$p;

            }
            echo " will add ".count($sadded)." as configurable\n";

            if(count($sadded)>1) {
                createConfig($pname, $sadded, $attributeValues, $obj, $prepo, $sattribute, $set,$cdata,$c_wt,$c_sdsr,$c_dsr,$limgs,$medata,$pcatid);
            }

                  }
        $pname=$name1;
        $pcolor1=$color;
        $pg1=$gd;
        $addedcp=[];
        $limgs=[];

        $c_dsr=$dscr1;
        $c_sdsr=$sdscr1;
        $c_wt=$wt;
        $cdata=$datas;
        logx(print_r($cdata,1)."\n","cdata.txt");

        $medata=$stores;

        if($img&&$imgto){
            $limgs[$img]=$imgo;
        }
        $pcatid=$idcat;
        $addedcp[$product->getId()]=$sz;
        $added=[$product->getId()];
    }

    ///print_r($br);

    ///$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
    $resource = $obj->get('Magento\Framework\App\ResourceConnection');
    /**@var $resource \Magento\Framework\App\ResourceConnection */
    $connection = $resource->getConnection();
    ///$tableName = $resource->getTableName('employee'); //gives table name with prefix
    $connection->insert('bms_supplier_product',$br);


   /// print(" wei $wt img $img pri $price name $name1 sku $sku");
    ///print_r($stores);
 ///   print_r($datas);

    echo "  ".$product->getId()." sku $sku \n#####\n";





}




/*
       CategoryLinkManagementInterface $catmgmt,
        \Ibapi\Multiv\Helper\Data $helper,
        ProductRepositoryInterface $repo,
        ProductFactory $factory,
        \Ibapi\Multiv\Model\ImageUploader $uploader

*/



