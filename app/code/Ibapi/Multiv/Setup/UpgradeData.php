<?php 
/*
* Declare the namespace of the module to avoid conflicts around multiple modules and introduce more flexibility
*/


namespace Ibapi\Multiv\Setup;

 /*
*The below are the namespaces and and classes to be included inorder to create new custom attributes and custom attibute groups programatically
*/

use Magento\Eav\Setup\EavSetup; 
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Catalog\Api\Data\CategoryProductSearchResultInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\GroupFactory;
use Magento\Catalog\Model\Product\Type\Simple;

/*
* UpgradeDataInterface brings the ‘upgrade’ method which must be implemented
*/
class UpgradeData implements UpgradeDataInterface
{
 	private $eavSetupFactory;
	private $attributeSetFactory;
	private $attributeSet;
	private $categorySetupFactory;
    private $objectManager;   	
    private $groupFactory;

	public function __construct(EavSetupFactory $eavSetupFactory, AttributeSetFactory $attributeSetFactory, CategorySetupFactory $categorySetupFactory,        \Magento\Framework\ObjectManagerInterface $objectManager,GroupFactory $g)
    	{
        		$this->eavSetupFactory = $eavSetupFactory; 
        		$this->attributeSetFactory = $attributeSetFactory; 
        		$this->categorySetupFactory = $categorySetupFactory; 
        		$this->objectManager=$objectManager;
        		$this->groupFactory=$g;
    	} 
	
 public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
 {
      $setup->startSetup();    
     $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
     
     
     $eavSetup->addAttribute(
         Product::ENTITY, 'rental_bundle', [
             'input' => 'hidden',
             'type' => 'int',
             'label' => '',
             'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
             'group'=>'Default',
             'visible' => false,
             'required' => false,
             'user_defined' => false,
             'default' => '',
             'searchable' => false,
             'filterable' => true,
             'comparable' => false,
             'visible_on_front' => false,
             'used_in_product_listing' => true,
             'unique' => false,
             'apply_to' => 'bundle'
         ]
         );
     
     
     
     
         
         
         
     $setup->endSetup();
     return;
     
     
     
     
     
     $group=$this->groupFactory->create();
     
     $group->setCode('vip')->setTaxClassId(3)->save();
     echo "saved vip";
     $group=$this->groupFactory->create();
     
     $group->setCode('seller')->setTaxClassId(3)->save();
     echo "saved seller";
     
     
      //$eavSetup->updateAttribute('catalog_product', 'Deposit', array('attribute_code' => 'deposit'));
     
     $coll = $this->objectManager->create(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection::class);
     
     $coll->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ENTITY_TYPE_ID, 4);
     $attributes = $coll->load()->getItems();
     
     echo "getting attrs\n";
     
     
     
     foreach ($attributes as $val) {
         $attributeCode=$val->getName();
         
         
         $relatedProductTypes = explode(
             ',',
             $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeCode, 'apply_to')
             );
         
         
         if (count($relatedProductTypes)&&in_array( 'simple', $relatedProductTypes)) {
             $relatedProductTypes[] = \Ibapi\Multiv\Model\Type\DepType::TYPE_CODE;
             $relatedProductTypes[] = \Ibapi\Multiv\Model\Type\SubType::TYPE_CODE;
             $relatedProductTypes[] = \Ibapi\Multiv\Model\Type\AccessoryType::TYPE_CODE;
             $relatedProductTypes[] = \Ibapi\Multiv\Model\Type\ClothType::TYPE_CODE;
             
             echo "adding attribute code  $attributeCode to dependent\n";
             ;
         
         }else{
             continue;
         }
         
      
         
         $eavSetup->updateAttribute(
             \Magento\Catalog\Model\Product::ENTITY,
             $attributeCode,
             'apply_to',
             implode(',', $relatedProductTypes));
     }
     
     

     
     
     
     
     $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
     $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'exclusive', [
         'type'     => 'int',
         'label'    => 'Exclusive',
         'input'    => 'select',
         'source'   => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
         'default'  => '0',
         'sort_order' => 2,
         'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
         'group' => 'General Information',
         
         
     ]);
     
     
     
     $attributeSet = $this->attributeSetFactory->create();
     $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
     $defattributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
     echo "GOT SET ID $defattributeSetId \n";
     
     
     $data = [
         'attribute_set_name' => 'SubscriptionSet', // define custom attribute set name here
         'entity_type_id' => $entityTypeId,
         'sort_order' => 200,
     ];
     $attributeSet->setData($data);
     
     
     
     
     

     
     
     
     
     
     
      $attributeSet->setData($data);
      $attributeSet->validate();
     // $attributeSet->save();
      $attributeSet->initFromSkeleton($defattributeSetId);
      $attributeSet->save();
      echo "Created attributeset Subscriptionset ".$attributeSet->getAttributeSetId()."\n";
      
     $attributeSetId = $categorySetup->getAttributeSetId($entityTypeId, 'SubscriptionSet');
     
     echo "new set $attributeSetId\n"
     ;
     
     
     
     
     
     
     
     
    
     
     

          
          
          $attributeSet = $this->attributeSetFactory->create();
          $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        echo "DEFSET ID $attributeSetId \n";
        
          $data = [
              'attribute_set_name' => 'RentalSet', // define custom attribute set name here
              'entity_type_id' => $entityTypeId,
              'sort_order' => 200,
          ];
          $attributeSet->setData($data);
          $attributeSet->validate();
//          $attributeSet->save();
          $attributeSet->initFromSkeleton($defattributeSetId);
          $attributeSet->save();
            echo "Created attributeset ".$attributeSet->getAttributeSetId()."\n";
          $attributeSetId = $categorySetup->getAttributeSetId($entityTypeId, 'RentalSet');
          $categorySetup->addAttributeGroup($entityTypeId, $attributeSet->getAttributeSetId(),  'RentalGroupAdmin', 60);
          
          
          echo "new rentalset $attributeSetId =".$attributeSet->getAttributeSetId()."\n"
              ;
          
          
          
          $categorySetup->addAttributeGroup($entityTypeId, $attributeSetId,  'RentalGroupAdmin', 60);
          echo "added group rentalgrp\n";
          
         
          
          $eavSetup->addAttribute(
              Product::ENTITY, 'wash', [
                  'input' => 'text',
                  'type' => 'int',
                  'label' => 'Wash Days',
                  'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                  'group'=>'RentalGroupAdmin',
                  'visible' => true,
                  'required' => true,
                  'user_defined' => false,
                  'default' => '',
                  'searchable' => false,
                  'filterable' => false,
                  'comparable' => false,
                  'visible_on_front' => false,
                  'used_in_product_listing' => true,
                  'unique' => false,
                  'apply_to' => 'accessory,cloth'
              ]
              );
          
          $eavSetup->addAttribute(
              Product::ENTITY, 'rent', [
                  'input' => 'text',
                  'type' => 'decimal',
                  'label' => 'Rent',
                  'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                  'group'=>'Prices',
                  'visible' => true,
                  'required' => true,
                  'user_defined' => false,
                  'default' => '',
                  'searchable' => false,
                  'filterable' => false,
                  'comparable' => false,
                  'visible_on_front' => false,
                  'used_in_product_listing' => true,
                  'unique' => false,
                  'apply_to' => 'accessory,cloth'
              ]
              );
          
          
          $eavSetup->addAttribute(
              Product::ENTITY, 'size', [
                  'input' => 'text',
                  'type' => 'varchar',
                  'label' => 'Size',
                  'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                  'group'=>'RentalGroupAdmin',
                  'visible' => true,
                  'required' => true,
                  'user_defined' => false,
                  'default' => '',
                  'searchable' => true,
                  'filterable' => true,
                  'comparable' => false,
                  'visible_on_front' => true,
                  'used_in_product_listing' => true,
                  'unique' => false,
                  'apply_to' => 'cloth'
              ]
              );
          /*
          $eavSetup->addAttribute(
              Product::ENTITY, 'color', [
                  'input' => 'text',
                  'type' => 'varchar',
                  'label' => 'Color',
                  'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                  'group'=>'RentalGroupAdmin',
                  'visible' => true,
                  'required' => false,
                  'user_defined' => false,
                  'default' => '',
                  'searchable' =>true,
                  'filterable' => true,
                  'comparable' => false,
                  'visible_on_front' => true,
                  'used_in_product_listing' => true,
                  'unique' => false,
                  'apply_to' => 'accessory,cloth'
              ]
              );
          
          $eavSetup->addAttribute(
              Product::ENTITY, 'brand', [
                  'input' => 'text',
                  'type' => 'varchar',
                  'label' => 'Brand',
                  'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                  'group'=>'RentalGroupAdmin',
                  'visible' => true,
                  'required' => false,
                  'user_defined' => false,
                  'default' => '',
                  'searchable' => true,
                  'filterable' => true,
                  'comparable' => false,
                  'visible_on_front' =>true,
                  'used_in_product_listing' => true,
                  'unique' => false,
                  'apply_to' => 'accessory,cloth'
              ]
              );
          
          */
          $eavSetup->addAttribute(
              Product::ENTITY, 'uid', [
                  'input' => 'text',
                  'type' => 'int',
                  'label' => 'UserID',
                  'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                  'group'=>'RentalGroupAdmin',
                  'visible' => true,
                  'required' => false,
                  'user_defined' => false,
                  'default' => '',
                  'searchable' => false,
                  'filterable' => false,
                  'comparable' => false,
                  'visible_on_front' => false,
                  'used_in_product_listing' => true,
                  'unique' => false,
                  'apply_to' => 'accessory,cloth'
              ]
              );
          
          
          $eavSetup->addAttribute(
              Product::ENTITY, 'deposit', [
                  'input' => 'text',
                  'type' => 'decimal',
                  'label' => 'Deposit',
                  'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                  'group'=>'RentalGroupAdmin',
                  'visible' => true,
                  'input'=>'price',
                  'required' => true,
                  'user_defined' => false,
                  'default' => 0,
                  'searchable' => false,
                  'filterable' => false,
                  'comparable' => false,
                  'visible_on_front' => true,
                  'used_in_product_listing' => true,
                  'unique' => false,
                  'apply_to' => 'cloth,accessory'
              ]
              );
          
          
          $eavSetup->addAttribute(
              Product::ENTITY, 'vip_discount', [
                  'input' => 'text',
                  'type' => 'decimal',
                  'label' => 'Vip Discount',
                  'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
                  'group'=>'Prices',
                  'visible' => true,
                  'input'=>'price',
                  'required' => false,
                  'user_defined' => false,
                  'default' => '',
                  'searchable' => false,
                  'filterable' => true,
                  'comparable' => false,
                  'used_in_product_listing' => true,
                  'unique' => false,
                  'apply_to' => 'accessory,cloth,simple,virtual,downloadable,bundle,grouped,configurable'
              ]
              );
          
          
          $setup->endSetup();
          echo "DONE\n";
          
          
          
          
          
          

} 
} 
