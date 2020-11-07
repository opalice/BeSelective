<?php 
namespace  Ibapi\Multiv\Plugin\Cart;

class CartGet {
    
    /**
     * @var ProductExtensionFactory
     */
    private $extensionFactory;
    
    /**
     * @param ProductExtensionFactory $extensionFactory
     */
    public function __construct(\Magento\Quote\Api\Data\CartExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    /*
    public function afterGetExtensionAttribute(
        \Magento\Quote\Api\Data\CartInterface $cart
        ) {
        
            
    }
    */
/**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magento\Quote\Api\Data\CartExtensionInterface|null
     */
    
    public function afterGetExtensionAttribute(
        \Magento\Quote\Api\Data\CartInterface $cart     
        ) {
        
            $exts=$cart->getExtensionAttributes();                
            if($exts==null){
                $exts=$this->extensionFactory->create();
                $cart->setExtensionAttributes($exts);
            }
            
          
            
            return $exts;
        
            }
    
    private function getAttribute(\Magento\Sales\Api\Data\OrderInterface $order)
    {
        
        try {
            // The actual implementation of the repository is omitted
            // but it is where you would load your value from the database (or any other persistent storage)
            $foomanAttributeValue = $this->foomanExampleRepository->get($order->getEntityId());
        } catch (\Exception $e) {
            return $order;
        }
        
        $extensionAttributes = $order->getExtensionAttributes();
        $orderExtension = $extensionAttributes ? $extensionAttributes : $this->orderExtensionFactory->create();
        $foomanAttribute = $this->foomanAttributeFactory->create();
        $foomanAttribute->setValue($foomanAttributeValue);
        $orderExtension->setFoomanAttribute($foomanAttribute);
        $order->setExtensionAttributes($orderExtension);
        
        return $order;
    }
    
}
