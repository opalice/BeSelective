<?php 
namespace  Ibapi\Multiv\Model\Layer\Filter;
class SizeFactory extends \Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $_size;
//    private $_objectManager;
    
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }
    
    /**
     * Create config model
     * @param string|\Magento\Framework\Simplexml\Element $sourceData
     * @return \Magento\Framework\App\Config\Base
     */
    public function create(array $sourceData = [])
    {
//        return $this->_objectManager->create(\Magento\Framework\App\Config\Base::class, ['sourceData' => $sourceData]);
  
        return $this->_objectManager->create(\Ibapi\Multiv\Model\ResourceModel\Layer\Filter\Size::class, ['sourceData' => $sourceData]);
    }
}