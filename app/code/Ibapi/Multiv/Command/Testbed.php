<?php
namespace Ibapi\Multiv\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Customer\Model\Customer;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ScopeInterface;
use Magento\Framework\Cache\ConfigInterface;
use Ibapi\Multiv\Model\Checkout\ReportOrderPlaced;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Magento\Catalog\Model\ProductRepository;
class Testbed extends Command
{
    var $attrcl;
    var $rtfact;
    var $scopeconfig;
    var $helper;
    var $eavConfig;
    var $productC;
    var $pi;
    var $onlineFactory;
    
    public  function  __construct(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection $cl,
        \Ibapi\Multiv\Model\RtableFactory $rtfact,\Ibapi\Multiv\Helper\Data $helper,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        \Magento\Eav\Model\Config $eavConfig,
        ProductRepository $pi,\Magento\Customer\Model\ResourceModel\Online\Grid\CollectionFactory $onlineCustomerCollectionFactory


    ){
        $this->pi=$pi;
        parent::__construct('testbed');
        $this->rtfact=$rtfact;
        $this->helper=$helper;
       $this->eavConfig=$eavConfig;
        $this->productC=$productCollection;

        $this->onlineFactory=$onlineCustomerCollectionFactory;
        
        
        
        
        $this->attrcl=$cl;
        
    }
    
    protected function configure()
    {
        $this->setName("testbed");
        $this->setDescription("A command the programmer was too lazy to enter a description for.");
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $storemgr=$objectManager->create(\Magento\Store\Model\StoreManagerInterface::class);

        $attr=$objectManager->create(\Magento\Catalog\Model\ResourceModel\Product\Action::class);

        $stores=$storemgr->getStores();

        foreach($stores as $store){

            echo "store ".$store['store_id']."\n";
        }




        foreach($this->productC->getItems() as $prod){
            /**@var $prod \Magento\Catalog\Model\Product */
            $prod=$this->pi->getById($prod->getId());
            if(!in_array($prod->getTypeId(),[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE]))
                 continue;
            if(!$prod->getData('uid')) {
                echo $prod->getId() . "nouid  \n";
                 foreach ($stores as $s) {
                    $attr->updateAttributes([$prod->getId()], ['uid' => 1], $s['store_id']);
                }

            }
            else{
             //   echo $prod->getId().":    ".$prod->getData('uid')."\n";

           //     $uid=$prod->getData('uid')-1;

                /**@var $attr \Magento\Catalog\Model\ResourceModel\Product\Action  */


                //echo "has uid".$prod->getId();
            }


        }





        return;

        $online=$this->onlineFactory->create();
        /**@var $online \Magento\Customer\Model\ResourceModel\Online\Grid\Collection  */


        $custs=$online->getItems();
        $online->load(1);

$customers=[];
        foreach($custs as $c){

            if($c->getCustomerId()>0)
            $customers[]=$c->getCustomerId();
        }
        print_r($customers);

       return;
        
        
        
        /*
         * --  Actual parameter values may differ, what you see is a default string representation of values
UPDATE m21_1.catalog_eav_attribute
SET apply_to='simple,virtual,bundle,downloadable,configurable,cloth,subscription,accessory,deposit'
WHERE attribute_id=133;
         */
        
        
        
        
        
        
        
        
        
        
        /*
        
        $eavSetupf=$objectManager->create(\Magento\Eav\Setup\EavSetupFactory::class);
        
        
        $eavSetupf->removeAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'profile_picture'
            );
        
        echo "done\n";
        return;
       */
        $aSetupf=$objectManager->create(\Magento\Eav\Model\Entity\Attribute\SetFactory::class);
        //https://github.com/magento/magento2/issues/11252
        /*
         * PHP Exception:  Notice: Undefined index: path in magento/module-customer/Model/FileUploader.php on line 113
         */

        $eavSetupf=$objectManager->create(\Magento\Eav\Setup\EavSetupFactory::class);
       $setup = $eavSetupf->create();
        /** @var $setup \Magento\Eav\Setup\EavSetup */
        
      $setup=$setup->getSetup();

      /*
      $ids=$this->productC->addFieldToFilter('type_id',[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])->getAllIds();
      
      foreach($ids as $id){
          
         $pr=$this->pi->getById($id);
         
         if(!$pr->getData('uid')){
             $pr->setData('uid',1);
             $this->pi->save($pr);
             echo "saved ".$pr->getId()."\n";
         }
          
      }
      */
        
        
        
        $quoteTable = 'quote';
///        $quoteAddressTable = 'quote_address';
        $orderTable = 'sales_order';
        $invoiceTable = 'sales_invoice';
        $creditmemoTable = 'sales_creditmemo';
        
        $setup->getConnection()
        ->addColumn(
            $setup->getTable($quoteTable),
            'tiscount',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'tiscount'
            ]
            );
        /*
        $setup->getConnection()
        ->addColumn(
            $setup->getTable($quoteAddressTable),
            'viscount',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'viscount'
            ]
            );
        
        $setup->getConnection()
        ->addColumn(
            $setup->getTable($quoteAddressTable),
            'base_viscount',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'Base vipdiscount'
            ]
            );
        */
        
        $setup->getConnection()
        ->addColumn(
            $setup->getTable($quoteTable),
            'base_tiscount',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'Base tax'
            ]
            );

        
        $setup->getConnection()
        ->addColumn(
            $setup->getTable($orderTable),
            'tiscount',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'tipdiscount'
            ]
            );
        
        $setup->getConnection()
        ->addColumn(
            $setup->getTable($orderTable),
            'base_tiscount',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'Base tiscount'
            ]
            );
        
        $setup->getConnection()
        ->addColumn(
            $setup->getTable($invoiceTable),
            'tiscount',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'tiscount'
            ]
            );
        
        $setup->getConnection()
        ->addColumn(
            $setup->getTable($invoiceTable),
            'base_tiscount',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'Base tiscount'
            ]
            );
        
        $setup->getConnection()
        ->addColumn(
            $setup->getTable($creditmemoTable),
            'tiscount',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'tiscount'
            ]
            );
        
        $setup->getConnection()
        ->addColumn(
            $setup->getTable($creditmemoTable),
            'base_tiscount',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'Base tiscount'
            ]
            );
        
       echo "done\n";
       
       return;
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        $customerEntity = $customerSetup->getEntityTypeId('customer');
        $attributeSetId = $customerSetup->getDefaultAttributeSetId($customerEntity);
        
        /** @var $attributeSet AttributeSet */
    /*
        $attributeSet = $aSetupf->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->removeAttribute(Customer::ENTITY, 'profile_image');
        $customerSetup->addAttribute(Customer::ENTITY, 'profile_image', [
            'type' => 'varchar',
            'label' => 'Profile Image',
            'input' => 'image',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0,
        ]);
        
        $attribute = $this->eavConfig->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'profile_image')
        ->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => ['adminhtml_customer','customer_account_edit'],
        ]);
        $attribute->save();
        
        
        echo "\ndone\n";
        return;
        
        
        */
        
        
        
        
        
        
        
        
        
        
        
        /** @var $eavSetup  \Magento\Eav\Setup\EavSetupFactory */
        
        $eavSetupf=$objectManager->create(\Magento\Eav\Setup\EavSetupFactory::class);
       
        $eavSetup=$eavSetupf->create();
        ///                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY , 'skugr');
       
        
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'size2',
            [
                'group' => 'RentalGroupAdmin',
                'type' => 'varchar',
                'label' => 'Size2',
                'input' => 'select',
                'source' => 'Ibapi\Multiv\Model\Attribute\Source\Size',
                
                //                  'source' => 'Learning\ClothingMaterial\Model\Attribute\Source\Material',
                //                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
                //              'backend' => 'Learning\ClothingMaterial\Model\Attribute\Backend\Material',
                'required' => false,
                'sort_order' => 50,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'visible' => true,
                'is_html_allowed_on_front' => false,
                'visible_on_front' => true,
                'user_defined' => false,
                'default' => 0,
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'used_in_product_listing' => true,//false
                
                
            ]
            );
        
        /*
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'skugr',
            [
                'group' => 'RentalGroupAdmin',
                'type' => 'static',
                'label' => 'skugroup',
                'input' => 'hidden',
                //                  'source' => 'Learning\ClothingMaterial\Model\Attribute\Source\Material',
                //                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
                //              'backend' => 'Learning\ClothingMaterial\Model\Attribute\Backend\Material',
                'required' => false,
                'sort_order' => 50,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'visible' => true,
                'is_html_allowed_on_front' => false,
                'visible_on_front' => true,
                'user_defined' => false,
                'default' => 0,
                'searchable' => true,
                'filterable' => false,
                'comparable' => false,
                'used_in_product_listing' => true,//false
                
                
            ]
            );
        */
        
        echo "done\n";
        return;
        
        
       
        
        
        
        
       $attrs=['brand','size','length','rent4','rent8','color','deposit'];
       
       //$attrs=['brand','size','length','color'];
       /** @var $eavSetup  \Magento\Eav\Setup\EavSetup */

       $entityTypeId=\Magento\Catalog\Model\Product::ENTITY;
       
       $code='vip_discount8';

       
       
       
       $eavSetup->addAttribute(
           \Magento\Catalog\Model\Product::ENTITY,
           'vip_discount8',
           [
               'group' => 'RentalGroupAdmin',
               'type' => 'decimal',
               'label' => 'ViP Discount(8)',
               'input' => 'price',
               //                  'source' => 'Learning\ClothingMaterial\Model\Attribute\Source\Material',
               //                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
               //              'backend' => 'Learning\ClothingMaterial\Model\Attribute\Backend\Material',
               'required' => false,
               'sort_order' => 50,
               'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
               'is_used_in_grid' => true,
               'is_visible_in_grid' => true,
               'is_filterable_in_grid' => true,
               'visible' => true,
               'is_html_allowed_on_front' => true,
               'visible_on_front' => true,
               'user_defined' => false,
               'default' => 0,
               'searchable' => true,
               'filterable' => false,
               'comparable' => false,
               'used_in_product_listing' => true,//false
               
               
           ]
           );
       
       
       echo "done";
       return;
       
       
       
       foreach($attrs as $code){

           $attribute = $eavSetup->getAttribute($entityTypeId, $code);
           
           $eavSetup->updateAttribute(\Magento\Catalog\Model\Product::ENTITY, $attribute['attribute_id'],'required',false);
           
           
//           $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY , $code);
           
       }
       echo "done";
       return;
     
       $entityTypeId=\Magento\Catalog\Model\Product::ENTITY;
       
      
       /*
       foreach($attrs as $code){
           
           $attribute = $eavSetup->getAttribute($entityTypeId, $code);
           
           echo "id ".$attribute['attribute_id']."\n";
           $eavSetup->updateAttribute(\Magento\Catalog\Model\Product::ENTITY, $attribute['attribute_id'], 
                'frontend','Ibapi\Multiv\Model\Attribute\Frontend\Filter'
               
           
               );
           $eavSetup->updateAttribute(\Magento\Catalog\Model\Product::ENTITY, $attribute['attribute_id'],'filterable',true);
               
///           $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY , $code);
       
       echo "update $code \n";
       }
       $attrs=['rent4','rent8'];
       
       foreach($attrs as $code){
           $attribute = $eavSetup->getAttribute($entityTypeId, $code);
           echo "id ".$attribute['attribute_id']."\n";
           
       $eavSetup->updateAttribute(\Magento\Catalog\Model\Product::ENTITY, $attribute['attribute_id'],'filterable',true);
       
       
       echo "update $code \n";
       
       }
       return;
       */
       
/*
 * 
 ALTER TABLE `m21_1`.`catalog_product_entity` 
ADD COLUMN `rent` FLOAT NOT NULL DEFAULT 0 AFTER `updated_at`,
ADD COLUMN `rent4` FLOAT NOT NULL DEFAULT 0 AFTER `rent`,
ADD COLUMN `rent8` FLOAT NOT NULL DEFAULT 0 AFTER `rent4`,
ADD COLUMN `brand` VARCHAR(45) NULL AFTER `rent8`,
ADD COLUMN `color` VARCHAR(45) NULL AFTER `brand`,
ADD COLUMN `size` VARCHAR(45) NULL AFTER `color`,
ADD COLUMN `length` VARCHAR(45) NULL AFTER `size`,
ADD COLUMN `deposit` FLOAT NOT NULL DEFAULT 0 AFTER `rent8`;
 
 ALTER TABLE `m21_1`.`catalog_product_entity` 
ADD COLUMN `deposit` FLOAT NOT NULL DEFAULT 0 AFTER `length`;

 
 */       
       
           
           $eavSetup->addAttribute(
               \Magento\Catalog\Model\Product::ENTITY,
               'rent4',
               [
                   'group' => 'RentalGroupAdmin',
                   'type' => 'decimal',
                   'label' => 'Rent-4d',
                   'input' => 'price',
 //                  'source' => 'Learning\ClothingMaterial\Model\Attribute\Source\Material',
   //                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
     //              'backend' => 'Learning\ClothingMaterial\Model\Attribute\Backend\Material',
                   'required' => false,
                   'sort_order' => 50,
                   'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                   'is_used_in_grid' => true,
                   'is_visible_in_grid' => true,
                   'is_filterable_in_grid' => true,
                   'visible' => true,
                   'is_html_allowed_on_front' => true,
                   'visible_on_front' => true,
                   'user_defined' => false,
                   'default' => 0,
                   'searchable' => true,
                   'filterable' => false,
                   'comparable' => false,
                   'used_in_product_listing' => true,//false
                   
                   
               ]
               );
           
           $eavSetup->addAttribute(
               \Magento\Catalog\Model\Product::ENTITY,
               'rent8',
               [
                   'group' => 'RentalGroupAdmin',
                   'type' => 'decimal',
                   'label' => 'Rent-8d',
                   'input' => 'price',
                   //                  'source' => 'Learning\ClothingMaterial\Model\Attribute\Source\Material',
                   //                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
                   //              'backend' => 'Learning\ClothingMaterial\Model\Attribute\Backend\Material',
                   'required' => false,
                   'sort_order' => 50,
                   'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                   'is_used_in_grid' => true,
                   'is_visible_in_grid' => true,
                   'is_filterable_in_grid' => true,
                   'visible' => true,
                   'is_html_allowed_on_front' => true,
                   'visible_on_front' => true,
                   'user_defined' => false,
                   'default' => 0,
                   'searchable' => true,
                   'filterable' => false,
                   'comparable' => false,
                   'used_in_product_listing' => true,//false
                   
                   
               ]
               );
           
           
           $eavSetup->addAttribute(
               \Magento\Catalog\Model\Product::ENTITY,
               'deposit',
               [
                   'group' => 'RentalGroupAdmin',
                   'type' => 'decimal',
                   'label' => 'deposit',
                   'input' => 'price',
                   //                  'source' => 'Learning\ClothingMaterial\Model\Attribute\Source\Material',
                   //                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
                   //              'backend' => 'Learning\ClothingMaterial\Model\Attribute\Backend\Material',
                   'required' => false,
                   'sort_order' => 50,
                   'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                   'is_used_in_grid' => true,
                   'is_visible_in_grid' => true,
                   'is_filterable_in_grid' => true,
                   'visible' => true,
                   'is_html_allowed_on_front' => true,
                   'visible_on_front' => true,
                   'user_defined' => false,
                   'default' => 0,
                   'searchable' => false,
                   'filterable' => false,
                   'comparable' => false,
                   'used_in_product_listing' => true,//false
                   
                   
               ]
               );
           
           
         
           
       
           $eavSetup->addAttribute(
               \Magento\Catalog\Model\Product::ENTITY,
               'brand',
               [
                   'group' => 'RentalGroupAdmin',
                   
                   //                   'group' => 'RentalGroupAdmin',
                   'type' => 'varchar',
                   'label' => 'Brand',
                   'input' => 'select',
                   'source' => 'Ibapi\Multiv\Model\Attribute\Source\Brand',
                   //                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
                   //'backend' => '\Ibapi\Multiv\Model\Attribute\Backend\Rent',
                   
                   'visible' => true,
                   'required' => true,
                   'user_defined' => false,
                   'default' => '',
                   'searchable' => true,
                   'filterable' => false,
                   'comparable' => false,
                   'visible_on_front' => true,//in more information
                   'used_in_product_listing' => true,//false
                   'unique' => false
                   
               ]
               );
           
       
           $eavSetup->addAttribute(
               \Magento\Catalog\Model\Product::ENTITY,
               'size',
               [
                   'group' => 'RentalGroupAdmin',
                   
                   //                   'group' => 'RentalGroupAdmin',
                   'type' => 'varchar',
                   'label' => 'Size',
                   'input' => 'select',
                   'source' => 'Ibapi\Multiv\Model\Attribute\Source\Size',
                   //                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
                   //'backend' => '\Ibapi\Multiv\Model\Attribute\Backend\Rent',
 
                   'visible' => true,
                   'required' => true,
                   'user_defined' => false,
                   'default' => '',
                   'searchable' => true,
                   'filterable' => false,
                   'comparable' => false,
                   'visible_on_front' => true,//in more information
                   'used_in_product_listing' => true,//false
                   'unique' => false
                   
               ]
               );
           
           
           $eavSetup->addAttribute(
               \Magento\Catalog\Model\Product::ENTITY,
               'length',
               [
                   'group' => 'RentalGroupAdmin',
                   
                   //                   'group' => 'RentalGroupAdmin',
                   'type' => 'varchar',
                   'label' => 'Length',
                   'input' => 'select',
                   'source' => 'Ibapi\Multiv\Model\Attribute\Source\Length',
                   //                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
                   //'backend' => '\Ibapi\Multiv\Model\Attribute\Backend\Rent',
                   
                   'visible' => true,
                   'required' => true,
                   'user_defined' => false,
                   'default' => '',
                   'searchable' => true,
                   'filterable' => false,
                   'comparable' => false,
                   'visible_on_front' => true,//in more information
                   'used_in_product_listing' => true,//false
                   'unique' => false
                   
               ]
               );
           
           $eavSetup->addAttribute(
               \Magento\Catalog\Model\Product::ENTITY,
               'color',
               [
                   'group' => 'RentalGroupAdmin',
                   
                   'type' => 'varchar',
                   'label' => 'Color',
                   'input' => 'select',
                   'source' => 'Ibapi\Multiv\Model\Attribute\Source\Color',
                   //                'frontend' => 'Learning\ClothingMaterial\Model\Attribute\Frontend\Material',
                   //'backend' => '\Ibapi\Multiv\Model\Attribute\Backend\Rent',
                   
                   'visible' => true,
                   'required' => true,
                   'user_defined' => false,
                   'default' => '',
                   'searchable' => true,
                   'filterable' => false,
                   'comparable' => false,
                   'visible_on_front' => true,//in more information
                   'used_in_product_listing' => true,//false
                   'unique' => false
                   
               ]
               );

           
           $eavSetup->cleanCache();
        
            echo "\ndone\n";
        
        return;
          
        
        
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        
        $cathelper=$objectManager->create(\Magento\Catalog\Helper\Category::class);
        
        /**@var $categories \Magento\Framework\Data\Tree\Node\Collection */


        
        print_r($this->helper->getCatTree());
        exit;
        
        echo $this->helper->getValue("rentals/procat/subid");exit;
        
        $eavSetupf=$objectManager->create(\Magento\Eav\Setup\EavSetupFactory::class);
        $eavSetup=$eavSetupf->create();
        
        $coll = $objectManager->create(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection::class);
        
        $coll->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ENTITY_TYPE_ID, 4);
   
        $attrs=$coll->load(1)->getItems();
        echo "getting attrs\n";
        $alls=[];
        
        foreach($attrs as $attribute){
            $relatedProductTypes = explode(
                ',',
                $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attribute->getName(), 'apply_to')
                );
           $alls=array_unique(array_merge($alls,$relatedProductTypes));
        }
        $arr=join(',',array_filter($alls,function($v){
            if($v){
                return true;
            }
            return false;
        }));
       print($arr);
        die('ok');
        
        $rtable=$this->rtfact->create();
        
        $sd='2018-04-08';
        $ed='2018-04-10';
        $pid=26;
//        $rtable->reserve($pid,$sd, $ed,1,333);
      $oid=334;
        $rtable->unreserveByOid($oid);
        
        
        $output->write("OK");
        return;
        $coll=$this->attrcl;
        
        /** @var  $coll \Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection */

        
        
        $coll->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ATTRIBUTE_SET_ID, 10);
        $attrAll = $coll->load()->getItems();
        echo count($attrAll);
        
        
        
        
        
        $output->writeln("Hello World");  
    }
} 