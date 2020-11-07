<?php 
namespace Ibapi\Multiv\Model\ResourceModel\Reserve;

/* use required classes */
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';
    
    /**
     * @param EntityFactoryInterface $entityFactory,
     * @param LoggerInterface        $logger,
     * @param FetchStrategyInterface $fetchStrategy,
     * @param ManagerInterface       $eventManager,
     * @param StoreManagerInterface  $storeManager,
     * @param AdapterInterface       $connection,
     * @param AbstractDb             $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
        ) {
            $this->_init('Ibapi\Multiv\Model\Reserve', 'Ibapi\Multiv\Model\ResourceModel\Reserve');
            //Class naming structure
            // 'NameSpace\ModuleName\Model\ModelName', 'NameSpace\ModuleName\Model\ResourceModel\ModelName'
            parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
            $this->storeManager = $storeManager;
    }
    
    protected function _initSelect()
    {
        parent::_initSelect();
        
        $this->getSelect()->columns(explode(',', ' sd as start, rd as end,ed , p as price,oid as orderid, d as deposit ,o ,u,c '))->joinInner(
            ['oi' => $this->getTable('sales_order_item')], 
            'main_table.otid = oi.item_id ')->columns( 
           explode(',' ,'oi.name as product_name, oi.sku,oi.amount_refunded'))
            ->joinInner(['o'=>$this->getTable('sales_order')], 'o.entity_id= oi.order_id')
            ->columns( explode(',','o.store_id,o.state,o.status,o.total_refunded,o.entity_id as order'))
   //         ->joinInner(['pt'=>$this->getTable('sales_payment_transaction')],'o.entity_id = pt.order_id and pt.parent_id is null ')->columns(
///          explode(',',      'pt.txn_type,pt.payment_id ,pt.txn_id '))
        ->joinInner(['p'=>$this->getTable('sales_order_payment')], 'p.parent_id=o.entity_id')->columns(['p.method'])
        ->joinInner(['cu'=>$this->getTable('customer_entity')],'cu.entity_id=main_table.u')->columns(explode(',','cu.email as customer_email,cu.lastname as customer_lastname,cu.firstname as customer_firstname,cu.group_id'))
        ->joinInner(['co'=>$this->getTable('customer_entity')],'co.entity_id=main_table.o')->columns(explode(',','co.email as owner_email,co.lastname as owner_lastname,co.firstname as owner_firstname,cu.group_id'));
        
        
        ;
///        $this->load(1,1);
        
    }

    /**
     * addding ability to filter by column with customer name and email
     */
  /*
    protected function _renderFiltersBefore()
    {
        $wherePart = $this->getSelect()->getPart(\Magento\Framework\DB\Select::WHERE);
        foreach ($wherePart as $key => $cond) {
            $wherePart[$key] = str_replace('`firstname`', 'CONCAT(firstname," <",email,">")', $cond);
        }
        $this->getSelect()->setPart(\Magento\Framework\DB\Select::WHERE, $wherePart);
        parent::_renderFiltersBefore();
    }
*/
/*
 There is a factory method which you can use, and this is the addFilterToMap(). Where Magento 2 rendering filter just replace the subjects of the conditions based on the mapped fields

you can call it either in _initSelect or _renderFiltersBefore method.

for a simple column which already existing in the selection (good to resolve ambiguous errors)

$this
     ->addFilterToMap('customer_id', 'ce.entity_id');
but in your case need to map an expression as

$this
    ->addFilterToMap(
       'customer_name ', 
       new \Zend_Db_Expr('CONCAT(ce.firstname," <",ce.email,">")')
    ); 
so the condition part of the query will be  
... WHERE (CONCAT(ce.firstname," <",ce.email,">") LIKE '%@yippie.com%') ...
instead of  
... WHERE (customer_name LIKE '%@yippie.com%') ...

also you can use an other collection related factory method to use expressions in SELECT part of the query

$this
    ->addExpressionFieldToSelect(
        'firstname',
        new \Zend_Db_Expr('CONCAT(ce.firstname," <",ce.email,">")'),
        []
    )
instead of

$this->getSelect()->columns('CONCAT(ce.firstname," <",ce.email,">") as firstname');
 */

}




