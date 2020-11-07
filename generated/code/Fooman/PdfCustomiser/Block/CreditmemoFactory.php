<?php
namespace Fooman\PdfCustomiser\Block;

/**
 * Factory class for @see \Fooman\PdfCustomiser\Block\Creditmemo
 */
class CreditmemoFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Fooman\\PdfCustomiser\\Block\\Creditmemo')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Fooman\PdfCustomiser\Block\Creditmemo
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
