<?php 
namespace Ibapi\Multiv\Plugin;


class AfterReview {
    
    protected  $request;
    protected $imageUploader;
    
    public function __construct(
        \Magento\Framework\App\Request\Http $request
        ) {
            $this->request = $request;
    }
    
    
    public function aroundSave(
        Interceptor $interceptor,
        \Closure $closure,
        $productIds,
        $attrData,
        $storeId
        ) {
        
            //execute the original method and remember the result;
            $result = $closure($productIds, $attrData, $storeId);
            //do something with $productIds here
            return $result;
    }
    
    
}