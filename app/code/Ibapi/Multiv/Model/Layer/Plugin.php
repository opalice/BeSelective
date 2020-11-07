<?php 
namespace  Ibapi\Multiv\Model\Layer;

class Plugin {
    
    /**
     * Before prepare product collection handler
     *
     * @param \Magento\Catalog\Model\Layer $subject
     * @param \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection $collection
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundPrepareProductCollection(
        \Magento\Catalog\Model\Layer $subject,Callable $proceed,
        \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection $collection
        ) {
            
            $result=$proceed($collection);
            
            
            
            file_put_contents('prepx.txt',' col'.get_class($collection). ' '.__FILE__."    ".date('Y-m-d H:i:s')."\n",FILE_APPEND);
           
              
            return $result;
            
            
    }
}