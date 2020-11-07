<?php
namespace Ibapi\Multiv\Model;
class Rtable extends \Magento\Framework\Model\AbstractModel implements \Ibapi\Multiv\Api\Data\RtableInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'multiv_rtable';

    protected function _construct()
    {
        $this->_init('Ibapi\Multiv\Model\ResourceModel\Rtable');
    }
    
    public function isFree($pid,$sd,$ed){
    $dst=    $this->_getResource()->getConnection('core_read')->fetchOne("select dt from ".$this->_getResource()->getTable('multiv_block')." where dt='$sd' " );
    if($dst){
        return false;
    }
    $table=$this->_getResource()->getMainTable();
    $row=    $this->_getResource()->getConnection('core_read')->fetchOne("select id from ".$table." where sd<='$sd' and ed>='$ed' and  pid=".$pid."   ",null,\PDO::FETCH_OBJ);
    if($row){
        return true;
    }
    return false;
    
    
    }
    public function getPrice($pid,$uid){
        return    $this->_getResource()->getConnection('core_read')->fetchRow("select * from ".$this->_getResource()->getTable('multiv_res')." where uid='$uid' and pid='$pid' and s <2  order by id desc ",null,\PDO::FETCH_OBJ);
    }

    public function getGenBlock($sd,$ed){
    
            
        
        
        return    $this->_getResource()->getConnection('core_read')->fetchAll("select dt from ".$this->_getResource()->getTable('multiv_block')." where dt>='$sd' and dt<='$ed'   ",null,\PDO::FETCH_OBJ);
    }
    public function getSelect($optid){
        $t=$this->_getResource()->getTable('catalog_product_bundle_selection');
        return $this->_getResource()->getConnection('core_read')->fetchOne("select selection_id from $t where option_id=".$optid);
    }
    
    public function getSchedule($product,$sd,$ed){
        
        $wsh=(int)$product->getData('wash');
        $pid=$product->getId();
        
///        file_put_contents('range.txt', "select sd,ed,type from ".$this->_getResource()->getMainTable()." where ((sd<='$sd' and ed>adddate('$sd',INTERVAL    1 DAY)) or ( sd<'$ed' and ed >'$ed' ) or(ed<='$ed' and sd>=adddate('$sd',interval $wsh day)) )and  pid=".$pid,FILE_APPEND);
      $schs=    $this->_getResource()->getConnection('core_read')->fetchAll("select sd,ed,type from ".$this->_getResource()->getMainTable()." where ((sd<='$sd' and ed>='$sd') 
                                                                                                                                                 or  (sd<='$ed' and ed>='$ed') 
                                                                                                                                                 or  (ed<='$ed' and sd>='$sd') 
                                                                                                                                                 or  (sd<='$sd' and ed>='$ed') ) and  pid=".$pid."  ",null,\PDO::FETCH_OBJ);
       return $schs;
       
    
    }
    public function getAGenBlock($pid,$sd,$ed){
        
        return    $this->_getResource()->getConnection('core_read')->fetchAll("select dt from ".$this->_getResource()->getTable('multiv_ablock')." where dt>='$sd' and dt<='$ed'   ",null,\PDO::FETCH_OBJ);
        
        
    }
    
    
    public function createRecord($pid){

        return  $this->_getResource()->getConnection('core_write')->insert($this->_getResource()->getMainTable(), [
            'sd'=>date('Y-m-d'),
            'ed'=>date('Y-m-d',strtotime('+5 years')),
            'pid'=>$pid,
            'type'=>'C'
            
            
        ]);
    }
    public function findRecord($pid){

        return  $this->_getResource()->getConnection('core_read')->fetchOne("select id from ".$this->_getResource()->getMainTable()." where   pid=".$pid."  ",null,\PDO::FETCH_OBJ);
        
    }
    
    public function deleteSchedule($id){
        
        $this->_getResource()->getConnection('core_write')->delete($this->_getResource()->getMainTable(),['id'=>$id]);
        
    }
    public function unreserveByCid($pid,$sd,$ed,$qid,$ws=0){
        $table=$this->_getResource()->getTable('multiv_res');
        $con=$this->_getResource()->getConnection('core_write');
        $con->beginTransaction();

        $row=$con->fetchRow("select * from $table where pid='$pid' and   qid='$qid' and  s=0 limit 1 for update",null,\PDO::FETCH_OBJ);

///        $row=$con->fetchRow("select * from $table where pid='$pid' and ed='$ed' and sd='$sd' and  qid='$qid' and  s=0 limit 1 for update",null,\PDO::FETCH_OBJ);
        if($row&&$row->id){
          ///  file_put_contents('removecart.txt', "row  select * from $table where pid='$pid' and " ."\n",FILE_APPEND);
            $this->unreserve($row->pid,$row->sd, $row->ed,$con);
            $con->delete($table,"uid=".$row->uid." and s<2");
            
        }
       $con->commit();
        
    }
    public function unreserveByQid($oid){
        $table=$this->_getResource()->getTable('multiv_res');
        $con=$this->_getResource()->getConnection('core_write');
        $con->beginTransaction();
        
        $row=$con->fetchRow("select * from $table where qid='$oid' and s<2 limit 1 for update",null,\PDO::FETCH_OBJ);
        if($row&&$row->id) {
            $this->unreserve($row->pid, $row->sd, $row->ed, $con);

            $con->delete($table, "id=" . $row->id);
        }
        $con->commit();

        }
     public function unreserveByCust($cid){
            try{
        $table=$this->_getResource()->getTable('multiv_res');
        $con=$this->_getResource()->getConnection('core_write');
        $con->beginTransaction();
        $quotes=[];

        $rows=$con->fetchAll("select * from $table where uid='$cid'  and s <2 for update  ",null,\PDO::FETCH_OBJ);
        foreach($rows as $row) {
            if ($row && $row->id) {
                $quotes[]=$row->qid;
                $this->unreserve($row->pid, $row->sd, $row->ed, $con);
            }


            $con->delete($table, "id=" . $row->id);

        }


        $con->commit();
        return $quotes;
        }catch (\Exception $e){
                file_put_contents('login.txt',"invalid ".$e->getMessage(),FILE_APPEND);
                if(isset($con)&&$con){
                    $con->rollBack();
                }
            }
            return [];

    }


    
    public function unreserveByOid($oid){
        $table=$this->_getResource()->getTable('multiv_res');
        $con=$this->_getResource()->getConnection('core_write');
        $con->beginTransaction();
        
        $row=$con->fetchRow("select * from $table where oid='$oid' limit 1 for update",null,\PDO::FETCH_OBJ);
        if($row&&$row->id){
            $this->unreserve($row->pid,$row->sd, $row->ed,$con);
        }
        $con->delete($table,"id=".$row->id);
        $con->commit();
    }

    public function unreserve2($pid,$sd,$ed,$ws,$con=null){
        $table=$this->_getResource()->getMainTable();
        $cl=false;
        if($ws){
            $ed=strtotime("+$ws day",strtotime($ed));
        }
        if(!$con){
            $cl=true;
            $con=$this->_getResource()->getConnection('core_write');
            
            $con->beginTransaction();
        }
        $wss=$ws+1;
        
        $rows=    $con->fetchAll("
            
select id,adddate(ed,interval 1 day) ped,ed,sd,
subdate(sd,interval 1 day) psd  from multiv_rtable
where ( adddate('$ed',interval 1 day)=sd or subdate('$sd',interval 1 day)= ed )
  and  pid=$pid  order by sd limit 2    for update
            
",null,\PDO::FETCH_OBJ);
        
        
        $cnt=count($rows);
        
        if($cnt){
            $r=[];
            $row=$rows[0];
            
            if($row->ped==$sd){
                $r['sd']=$row->sd;
                $r['ed']=$ed;
            }else{
                $r['sd']=$sd;
                $r['ed']=$row->ed;
                
            }
            
            if($cnt==2){
                if($rows[1]->psd==$ed){
                    $r['ed']=$rows[1]->ed;
                }
                
                $con->delete($table,"id=".$rows[1]->id);
            }
            $con->update($table,$r,"id=".$row->id);
            
            
        }else{
            
            $con->insert($table, ['sd'=>$sd,'ed'=>$ed,'pid'=>$pid]);
        }
        if($cl){
            $con->commit();
        }
        
    }
    
    public function unreserve($pid,$sd,$ed,$con=null){
        $table=$this->_getResource()->getMainTable();
        $cl=false;
        if(!$con){
         $cl=true;
        $con=$this->_getResource()->getConnection('core_write');

        $con->beginTransaction();
        }
        
        $rows=    $con->fetchAll("
        
select id,adddate(ed,interval 1 day) ped,ed,sd, 
subdate(sd,interval 1 day) psd  from multiv_rtable 
where ( adddate('$ed',interval 1 day)=sd or subdate('$sd',interval 1 day)= ed ) 
  and  pid=$pid  order by sd limit 2    for update

\n",null,\PDO::FETCH_OBJ);
        

        $cnt=count($rows);
        
        if($cnt){
            $r=[];
            $row=$rows[0];
            
            if($row->ped==$sd){
                $r['sd']=$row->sd;
                $r['ed']=$ed;
            }else{
                $r['sd']=$sd;
                $r['ed']=$row->ed;
                
            }

            if($cnt==2){
                if($rows[1]->psd==$ed){
                    $r['ed']=$rows[1]->ed;
                }
                
                $con->delete($table,"id=".$rows[1]->id);
            }
            $con->update($table,$r,"id=".$row->id);
            
            
        }else{
            
            $con->insert($table, ['sd'=>$sd,'ed'=>$ed,'pid'=>$pid]);
        }
        if($cl){
        $con->commit();
        }
        //file_put_contents('removecart.txt', "deleted\n",FILE_APPEND);
    }
    public function addSt($st,$et,$qid,$cid){
        $table=$this->_getResource()->getTable('multiv_res');
        
        $con=$this->_getResource()->getConnection('core_write');
//        $con->beginTransaction();
        $con->query("update $table set st='$st',et='$et' where qid='".$qid."' and u =".(int)$cid);
  ///      $con->commit();
        
        
        
    }
    
    public function isreserved($pid,$qid,$sd,$ed,$oid='x',$itid=0){
        $table=$this->_getResource()->getTable('multiv_res');
        
        $con=$this->_getResource()->getConnection('core_write');
        $con->beginTransaction();
        //file_put_contents('sql.txt', "select * from ".$table." where   pid=".$pid."  and sd='$sd'  and qid='$qid' and s<=1 limit 1 for update ");        
        $row=    $con->fetchRow("select * from ".$table." where   pid=".$pid."  and sd='$sd'  and qid='$qid' and s<=1 limit 1 for update ",null,\PDO::FETCH_OBJ);
        if(!$row||!$row->id){
            $con->rollBack();
            return false;
        }
        $con->query("update $table set oid='$oid',itid='$itid',s=1 where id=".$row->id);
        $con->commit();
        
        return true;
        
    }
    public function confirm($oid,$items=[]){
        $table=$this->_getResource()->getTable('multiv_res');
        
        $con=$this->_getResource()->getConnection('core_write');
        $con->beginTransaction();
        $rows=    $con->fetchAll("select * from ".$table." where  oid  ='".$oid."' and s>=1  for update",null,\PDO::FETCH_OBJ);
        $otids=[];
        foreach($rows as $row){

            if(isset($items[$row->itid])){
                $otid=$items[$row->itid];
                $otids[]=$otid;
               
            $con->update($table, ['s'=>2,'otid'=>$otid], "id=".$row->id);
            }
        }
        
        
        
        
        $con->commit();
        return $otids;
    }
    
    public function canreserve($pid,$sd,$ed){
        
        $table=$this->_getResource()->getMainTable();
        $con=$this->_getResource()->getConnection('core_write');
        $row=    $con->fetchRow("select * from ".$table." where sd<='$sd' and ed>'$ed'  and  pid=".$pid."  ",null,\PDO::FETCH_OBJ);
        return $row&&$row->id;
    }
    
    public function reserve($pid,$uid,$sd,$ed,$type=1,$oid='',$oe=0,$ue=0,$pe=0,$de=0,$wash=0,$dis=0){
      
        $bd=date('Y-m-d',strtotime($sd));
        
        $bsd=date('Y-m-d',strtotime($bd.' -1 day'));
        $sset=strtotime($ed);
        
            $ed1=$ed;   
            $ed=date('Y-m-d',strtotime("+$wash day ",$sset));
            $bd=date('Y-m-d',strtotime($ed));
        
        $bed=date('Y-m-d',strtotime($bd.' +1 day'));
        $table=$this->_getResource()->getMainTable();
        $con=$this->_getResource()->getConnection('core_write');
        $con->beginTransaction();
        $row=    $con->fetchRow("select * from ".$table." where sd<='$sd' and ed>'$ed'  and  pid=".$pid."   for update",null,\PDO::FETCH_OBJ);
        $ret=false;
        
        
        if($row&&$row->id){
            if($row->sd==$bsd||$row->sd==$sd){
                $con->delete($table,"id=".$row->id);
            }else{
            $con->update($table,['ed'=>$bsd,'type'=>'U'],"id=".$row->id);
            }
            if($row->ed==$bed||$row->ed==$ed){
                
            }else{
        
            $con->insert($table, [
                'sd'=>$bed,
                'ed'=>$row->ed,
                'pid'=>$pid,
                'type'=>'r'
                
            ]
                );                
                
            }
            
            if($oid){
             $rt=$this->_getResource()->getTable('multiv_res');   
            $rx=[];
             $rx['pid']=$row->pid;
             $rx['sd']=$sd;
             $rx['ed']=$ed;
             $rx['qid']=$oid;
             $rx['o']=(int)$oe;
             $rx['u']=(int)$ue;
             $rx['p']=(float)$pe;
             $rx['d']=(float)$de;
             $rx['rd']=$ed1;
             $rx['dis']=$dis;
             $rx['w']=$wash;
             
///             $rx['qid']=$oid;
             
             $rx['uid']=$uid;
             $rx['itid']=0;
             $rx['t']=date('Y-m-d H:i:s');
          $x=  $con->insert($rt, $rx);
          if(!$x){
              $con->rollBack();
              return false;
          }
            }
        $ret=true;
            
        }else{
            //file_put_contents('reserve.txt', " cannot reserve\n",FILE_APPEND);
        }
            if(!$ret){
                $con->rollBack();
            }
            return $ret?$con:false;
    }
    
    
    public function deletePast($ss=20){
        $ed=date('Y-m-d');
        $con=$this->_getResource()->getConnection('core_write');
        
        $con->beginTransaction();
        
///        $this->_getResource()->getConnection('core_write')->delete($this->_getResource()->getTable('multiv_res'),"where adddate(t,interval $ss minutes)<now() and status<2");
        $con->commit();
        
    }
    
    public function unblock($pid,$sd,$ed,$con=false){
        
        $table=$this->_getResource()->getMainTable();
        $cl=false;
        if(!$con){
            $cl=true;
            $con=$this->_getResource()->getConnection('core_write');
            
            $con->beginTransaction();
        }
        
        
        $rows=    $con->fetchAll("
            
select id,adddate(ed,interval 1 day) ped,ed,sd,
subdate(sd,interval 1 day) psd  from multiv_rtable
where ( adddate('$ed',interval 1 day)=sd or subdate('$sd',interval 1 day)= ed )
  and  pid=$pid  order by sd limit 2    for update
            
",null,\PDO::FETCH_OBJ);
        
        
        $cnt=count($rows);
        
        if($cnt){
            $r=[];
            $row=$rows[0];
            
            if($row->ped==$sd){
                $r['sd']=$row->sd;
                $r['ed']=$ed;
            }else{
                $r['sd']=$sd;
                $r['ed']=$row->ed;
                
            }
            $r['type']='U';
            
            if($cnt==2){
                if($rows[1]->psd==$ed){
                    $r['ed']=$rows[1]->ed;
                }
                
                $con->delete($table,"id=".$rows[1]->id);
            }
            $con->update($table,$r,"id=".$row->id);
            
            
        }else{
            
            $con->insert($table, ['sd'=>$sd,'ed'=>$ed,'pid'=>$pid,'type'=>'U']);
        }
        if($cl){
            $con->commit();
        }
        
        
        
        
    }
    
    public function aunblock($pid,$sd,$ed,$con=false){
        
        $table=$this->_getResource()->getMainTable();
        $cl=false;
        if(!$con){
            $cl=true;
            $con=$this->_getResource()->getConnection('core_write');
            
            $con->beginTransaction();
        }
       $table=$this->_getResource()->getTable('multiv_rtable');
        
        
        $rows=    $con->fetchAll("
            
select id,adddate(ed,interval 1 day) ped,ed,sd,
subdate(sd,interval 1 day) psd  from $table
where ( adddate('$ed',interval 1 day)=sd or subdate('$sd',interval 1 day)= ed )
  and  pid=$pid  order by sd limit 2    for update
            
",null,\PDO::FETCH_OBJ);
        
        
        $cnt=count($rows);
        
        if($cnt){
            $r=[];
            $row=$rows[0];
            
            if($row->ped==$sd){
                $r['sd']=$row->sd;
                $r['ed']=$ed;
            }else{
                $r['sd']=$sd;
                $r['ed']=$row->ed;
                
            }
            $r['type']='U';
            
            if($cnt==2){
                if($rows[1]->psd==$ed){
                    $r['ed']=$rows[1]->ed;
                }
                
                $con->delete($table,"id=".$rows[1]->id);
            }
            $con->update($table,$r,"id=".$row->id);
            
            
        }else{
            
            $con->insert($table, ['sd'=>$sd,'ed'=>$ed,'pid'=>$pid,'type'=>'U']);
        }
        
        
        $table=$this->_getResource()->getTable('multiv_ablock');
     
        
        
        $table=$this->_getResource()->getTable('multiv_ablock');
        $tsd=strtotime($sd);
        $ted=strtotime($ed);
        
        $dt=$sd;
        while($tsd<=$ted){
            
            $con->delete($table,"dt='$dt' and pid=".$pid);
            
            
            $tsd=strtotime('+1 day',$tsd);
            $dt=date('Y-m-d',$tsd);
            
        }
        
        
        
        
        
        if($cl){
            $con->commit();
        }
        
        
        
        
    }
    
    
    
    public function ablock($pid,$sd,$ed){
        
        
        
        $bd=date('Y-m-d',strtotime($sd));
        
        $bsd=date('Y-m-d',strtotime($bd.' -1 day'));
        
        $bd=date('Y-m-d',strtotime($ed));
        $bed=date('Y-m-d',strtotime($bd.' +1 day'));
        $table=$this->_getResource()->getMainTable();
        $con=$this->_getResource()->getConnection('core_write');
        $con->beginTransaction();
        $row=    $con->fetchRow("select * from ".$table." where sd<='$sd' and ed>='$ed' and  pid=".$pid."   for update",null,\PDO::FETCH_OBJ);
        $ret=false;
        
        if($row&&$row->id){
            if($row->sd==$sd){
                $con->delete($table,"id=".$row->id);
            }else{
                $con->update($table,['ed'=>$bsd],"id=".$row->id);
            }
            if($row->ed==$ed){
                
            }else{
                
                $con->insert($table, [
                    'sd'=>$bed,
                    'ed'=>$row->ed,
                    'pid'=>$pid,
                    'type'=>'A'
                    
                ]
                    );
                
            }
            
            $ret=true;
            
        }
        
        $table=$this->_getResource()->getTable('multiv_ablock');
        $tsd=strtotime($sd);
        $ted=strtotime($ed);
        
        $dt=$sd;
        while($tsd<=$ted){
        
            ///$con->delete($table,"where dt='$dt' and pid=".$pid);
            
           $con->insert($table,['dt'=>$dt,'pid'=>$pid]);
            
           $tsd=strtotime('+1 day',$tsd);
           $dt=date('Y-m-d',$tsd);
           
        }
        
        
        
        
        $con->commit();
        return $ret;
        
        
        
    }
    
    public function block($pid,$sd,$ed,$type='a'){
        
        
        
        $bd=date('Y-m-d',strtotime($sd));
        
        $bsd=date('Y-m-d',strtotime($bd.' -1 day'));
        
        $bd=date('Y-m-d',strtotime($ed));
        $bed=date('Y-m-d',strtotime($bd.' +1 day'));
        $table=$this->_getResource()->getMainTable();
        $con=$this->_getResource()->getConnection('core_write');
        $con->beginTransaction();
        $row=    $con->fetchRow("select * from ".$table." where sd<='$sd' and ed>='$ed' and  pid=".$pid."   for update",null,\PDO::FETCH_OBJ);
        $ret=false;
        
        if($row&&$row->id){
            if($row->sd==$bsd||$row->sd==$sd){
                $con->delete($table,"id=".$row->id);
            }else{
                $con->update($table,['ed'=>$bsd],"id=".$row->id);
            }
            if($row->ed==$bed||$row->ed==$ed){
                
            }else{
                
                $con->insert($table, [
                    'sd'=>$bed,
                    'ed'=>$row->ed,
                    'pid'=>$pid,
                    'type'=>$type
                    
                ]
                    );
                
            }
            
            $ret=true;
            
        }
        $con->commit();
        return $ret;
        
        
        
    }
    
    
    
    

    public function getIdentities()
    {
        
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
