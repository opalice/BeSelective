<?php 
namespace Ibapi\Multiv\Model;
class Reserve extends \Magento\Framework\Model\AbstractModel implements \Ibapi\Multiv\Api\Data\RtableInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'multiv_res';

    protected function _construct()
    {
        $this->_init('Ibapi\Multiv\Model\ResourceModel\Reserve');
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
        
        
    }
    
    public function getQid($qid){
        $table=$this->_getResource()->getMainTable();
        $con=$this->_getResource()->getConnection('core_read');
        $rows=$con->fetchOne("select concat(sd,'-',datediff(sd,ed)+1) from $table where  qid='$qid' ");
        return $rows;
    }
    
    public function addSt($st,$et,$cid){
        
        $table=$this->_getResource()->getMainTable();
        $con=$this->_getResource()->getConnection('core_write');
        $r=$con->fetchOne("select timediff(adddate(t,interval 20 minute),now()) as t from $table where uid=:uid and pid=:pid and s <2 limit  1  ",['uid'=>$cid,':pid'=>$pid]);
        
    }
    
    public function getAllTime(){
        $table=$this->_getResource()->getMainTable();
        $con=$this->_getResource()->getConnection('core_write');
        ///        $r=$con->fetchRow("select timediff(adddate(t,interval 20 minute),now()) as t,sd,datediff(ed,sd) as dt from $table where uid=:uid and pid=:pid and s <2 limit  1  ",['uid'=>$cid,':pid'=>$pid],Zend_Db::FETCH_OBJ);
        
    $rows=$con->fetchAll("select id,qid,oid,uid,t from $table where  s <2 and adddate(t,interval 25 minute) < now() order by id ",null,\PDO::FETCH_OBJ);


        return $rows;
        
    }
    public function getInfo($oid){
        $table=$this->_getResource()->getMainTable();
        $con=$this->_getResource()->getConnection('core_write');
        ///        $r=$con->fetchRow("select timediff(adddate(t,interval 20 minute),now()) as t,sd,datediff(ed,sd) as dt from $table where uid=:uid and pid=:pid and s <2 limit  1  ",['uid'=>$cid,':pid'=>$pid],Zend_Db::FETCH_OBJ);
        
        $r=$con->fetchRow("select id,sd,rd,ed from $table where oid='$oid'   ",null,\PDO::FETCH_OBJ);
        return $r;
        
    }


    
    public function getTime($cid,$pid){
        $table=$this->_getResource()->getMainTable();
        $con=$this->_getResource()->getConnection('core_write');
///        $r=$con->fetchRow("select timediff(adddate(t,interval 20 minute),now()) as t,sd,datediff(ed,sd) as dt from $table where uid=:uid and pid=:pid and s <2 limit  1  ",['uid'=>$cid,':pid'=>$pid],Zend_Db::FETCH_OBJ);




        $r=$con->fetchOne("select timediff(adddate(t,interval 20 minute),now()) as t from $table where uid=:uid and pid=:pid and s <2 order by id asc limit  1  ",['uid'=>$cid,':pid'=>$pid]);
        return $r;
        
    }

    
}
