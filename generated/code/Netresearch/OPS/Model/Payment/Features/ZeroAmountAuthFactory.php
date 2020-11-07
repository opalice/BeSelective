<?php
namespace Netresearch\OPS\Model\Payment\Features;

/**
 * Factory class for @see \Netresearch\OPS\Model\Payment\Features\ZeroAmountAuth
 */
class ZeroAmountAuthFactory
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Netresearch\\OPS\\Model\\Payment\\Features\\ZeroAmountAuth')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Netresearch\OPS\Model\Payment\Features\ZeroAmountAuth
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
