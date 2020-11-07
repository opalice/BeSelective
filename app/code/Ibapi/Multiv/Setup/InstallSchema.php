<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ibapi\Multiv\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ExternalFKSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;



/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /*
    public function __construct(
        MetadataPool $metadataPool,
        ExternalFKSetup $externalFKSetup,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $setup,
        AttributeManagement $am,
        QuoteSetupFactory $quoteSetupFactory,
        SalesSetupFactory $salesSetupFactory
        
        ) {
            $this->quoteSetupFactory = $quoteSetupFactory;
            $this->salesSetupFactory = $salesSetupFactory;
            
            $this->metadataPool = $metadataPool;
            $this->externalFKSetup = $externalFKSetup;
            $this->eavSetupFactory=$eavSetupFactory;
            $this->setup=$setup;
            $this->am=$am;
    }
    
    */
    
    /**
     * {@inheritdoc}
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        echo "install\n";
        $setup->startSetup();
        $setup->endSetup();
        return;
        $installer = $setup;
        $installer->startSetup();
        $installer = $setup;
        
        $salesSetup = $this->salesSetupFactory->create(['setup' => $this->setup]);
        
        $options = ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 'visible' => false, 'required' => false,'default'=>0];
        
        $salesSetup->addAttribute('order', 'rental', $options);
        
        $table=$setup->getTable('sales_order_item');
        
        
        $setup->getConnection()->addColumn($table, 'multiv_s',
        		
        		[
        				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
        				'nullable' => true,
        				'comment' => 'start',
        				'default'=>null
        		]);
        
        $setup->getConnection()->addColumn($table, 'multiv_e',
            
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                'nullable' => true,
                'comment' => 'end',
                'default'=>null
            ]);
        
        $setup->getConnection()->addColumn($table, 'multiv_ch',
            
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'LENGTH'=>255,
                'comment' => 'otherinfo',
                'default'=>null
            ]);
        
        $setup->getConnection()->addColumn($table, 'multiv_o',
            
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => false,
                'comment' => 'owner',
                'default'=>0
            ]);
        

        $installer->endSetup();

    }
}
