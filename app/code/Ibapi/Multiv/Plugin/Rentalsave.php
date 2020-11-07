<?php
namespace Ibapi\Multiv\Plugin;

use Magento\Catalog\Model\Product\Interceptor;
use Magento\Framework\DataObject\Copy;

class Rentalsave{
protected  $request;
protected $imageUploader;

	public function __construct(
		\Magento\Framework\App\Request\Http $request
	) {
		$this->request = $request;
	}

	public function aroundSave(\Magento\Catalog\Model\Product $product, callable $proceed){


$allattr=$product->getData('rental_dt');
file_put_contents('productplug.txt', "allattr $allattr");


if(!$allattr){
    
    $product->setData('skugr',$product->getData('sku'));//->save();
    $proceed();
	return;
//	return $product;
}

$sku=$product->getData('sku');

$skugs=explode('_', $sku);

if(count($skugs)>2){
    
}

 if(count($skugs)>1){
 
 $skug=$skugs[0];
 $product->setData('skugr',$skug);//->save();
 
 }else{
 $product->setData('skugr',$sku);//->save();
 }


	$proceed();






	}


}