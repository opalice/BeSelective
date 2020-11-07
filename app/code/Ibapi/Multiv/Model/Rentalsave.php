<?php

namespace Ibapi\Multiv\Model;


use Magento\Catalog\Model\Product\Interceptor;
use Magento\Framework\DataObject\Copy;
use PDepend\Util\FileUtil;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;
class Rentalsave implements ObserverInterface{
protected  $request;
protected $rentalf;

protected $session;
	public function __construct(
		\Magento\Framework\App\Request\Http $request,
		\Ibapi\Multiv\Model\RtableFactory $rentalf,
			\Magento\Backend\Model\Auth\Session $authSession
			
	) {
		$this->request = $request;
		$this->rentalf=$rentalf;
		$this->session=$authSession;
	}

	public function execute(Observer $observer){
		try{
		/*@var $product \Magento\Catalog\Model\Product */
		$product=$observer->getEvent()->getProduct();
		if($product->getTypeId()!==AccessoryType::TYPE_CODE&&$product->getTypeId()!==ClothType::TYPE_CODE){
			return $this;
		}
		$pid=$product->getId();
		/**@var $rtable \Ibapi\Multiv\Model\Rtable */
		$rtable=$this->rentalf->create();
		$r=$rtable->findRecord($product->getId());
		if(!$r){
		    $rtable->createRecord($pid);
		}
		$uid=$product->getData('uid');
		
		if(!$uid){
		    $product->setData('uid',1);
		}


            $size2Id=$rtable->getResource()->getConnection()->fetchOne('select attribute_id from '.$rtable->getResource()->getConnection()->getTableName('eav_attribute').' where attribute_code=\'size2\'');
   // $rtable->getResource()->getConnection()->insertOnDuplicate('ca')

   // echo " size $size2Id \n";

    if(!$product->getData('size2')){

             $rtable->getResource()->getConnection()->insertOnDuplicate('catalog_product_entity_varchar',[
               'entity_id'=>$product->getId(),
                'value'=>'',
                'attribute_id'=>$size2Id
            ]);

    }


    $rdt=$product->getData('rental_dt');
    
    $sku=$product->getData('sku');
    /*
    $skugs=explode('_', $sku);
    if(count($skugs)>1){
        
        $skug=$skugs[0];
        $product->setData('skugr',$skug);//->save();
        
    }else{
        $product->setData('skugr',$sku);//->save();
    }
    */
    
    if(!$rdt){
        return $this;
    }
    
    list($bdt,$ubdt)=explode(':', $rdt);
 //   file_put_contents('bdt.txt',"bdt $bdt udt $ubdt\n");
    $bdts=$ubdts=[];
    if($bdt)
    $bdts=explode(',', $bdt);
    if($ubdt)
    $ubdts=explode(',', $ubdt);
    
    
    $bdts=array_diff($bdts, $ubdts);
    asort($bdts);

    
    $pvd='';
    $sds=[];
    $lbd='';
    $sbd='';
    foreach($bdts as $bd){
        $bd=date('Y-m-d',strtotime($bd));
        if(!$sbd){
            $sbd=$bd;
        }
        
        if($pvd){
            $tbd=date('Y-m-d',strtotime($bd.' -1 day'));
            if($tbd==$pvd){
                $lbd=$bd;
            }else{ //gap
                if(!$lbd){
                    $lbd=$pvd;
                }
                $sds[]=[$sbd,$lbd];
                $lbd='';
                $sbd=$bd;
            }
            
        }
        $pvd=$bd;
       
        
    }
    if($lbd){
        $sds[]=[$sbd,$lbd];
    }else if(count($bdts)==1){
        $sds[]=[$pvd,$pvd];
    }else if($pvd){
        $sds[]=[$pvd,$pvd];
        
    }
    
    $pid=$product->getId();
    $sd=[];
    $ed=[];
    $i=0;

    foreach($sds as list($s,$e)){
        $sd[$i]=$s;
        $ed[$i++]=$e;
//        file_put_contents('bdt.txt',"block $s to $e \n",FILE_APPEND);
        if($s&&$e)
        $rtable->ablock($pid,$s,$e);
        
    }
    
    
    $pvd='';
    $sds=[];
    $lbd='';
    $sbd='';
    
    foreach($ubdts as $bd){
        $bd=date('Y-m-d',strtotime($bd));
        if(!$sbd){
            $sbd=$bd;
        }
        
        if($pvd){
            $tbd=date('Y-m-d',strtotime($bd.' -1 day'));
            if($tbd==$pvd){
                $lbd=$bd;
            }else{
                if(!$lbd){
                    $lbd=$pvd;
                }
                $sds[]=[$sbd,$lbd];
                $lbd='';
                $sbd=$bd;
            }
            
        }
        $pvd=$bd;
        
        
    }
    if($lbd){
        $sds[]=[$sbd,$lbd];
    }else if(count($bdts)==1){
        $sds[]=[$pvd,$pvd];
    }else if($pvd){
        $sds[]=[$pvd,$pvd];
    }

    $sd=[];
    $ed=[];
    $i=0;
    foreach($sds as list($s,$e)){
 //       file_put_contents('bdt.txt',"unblock $s to $e \n",FILE_APPEND);
        if($s&&$e)
        $rtable->aunblock($pid,$s,$e);
        
    }
    
	return $this;
	


///	$proceed();

		}catch(\Exception $e){
			print_r($e->getMessage());
			echo "<br>########################<br>";
			print_r($e->getTraceAsString());
			die();
		}

	}


}