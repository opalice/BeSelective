<?php
namespace Ibapi\Multiv\Setup;
use Magento\Catalog\Model\Entity\Attribute;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Catalog\Model\Product;
use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Http\Headers;
use Magento\Sales\Setup\SalesSetupFactory;

/**
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    
    private $salesSetupFactory;
    
    
/**
     * Init
     *
     *
     * @param EavSetupFactory $eavSetupFactory
     */
  /*
    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    		,    		SalesSetupFactory $salesSetupFactory
    		
    		
    		)
    {
    	$this->eavSetupFactory = $eavSetupFactory;
    	$this->salesSetupFactory=$salesSetupFactory;
    }
*/
    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        echo "install data\n";

    	/* @var $eavSetup \Magento\Eav\Setup\EavSetup  */

        $setup->startSetup();
        $setup->endSetup();
        
    return;
    	
    	
    }
}



