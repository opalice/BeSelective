<?php
namespace Ibapi\Multiv\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Model\ProductRepository;

class  Rental extends \Magento\Framework\App\Action\Action
{
    protected $helper;
    protected $resultPageFactory;
    protected $pi;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultPageFactory,
        ProductRepository $pi,
        \Ibapi\Multiv\Helper\Data $helper
        
        )
    {
        $this->resultPageFactory = $resultPageFactory;        
        parent::__construct($context);
        $this->helper=$helper;
        $this->pi=$pi;
    }
    private function _prodate($dt,$n,$pid){
        $t=time();
        list($y,$m,$d)=explode('-',$dt);
        $st=mktime(0,0,null,$m,$d,$y);
        
        
        if($n!=-1&&$n!=1){
            return ['error'=>1];
        }
        if($n==1){
            $n="+1";
        }
        $stt=strtotime("$n month" ,$st);
        $sel=$sd=date('Y-m-01',$stt);
        $ed=date('Y-m-t',$stt);
        if($stt<$t){
            list(,$M1,)=explode('-', $sel);
            list(,$M2,)=explode('-', date('Y-m-d',$t));
            
            if($M1<$M2)
            return ['error'=>1];
            
        }
        list($Y,$M,$D)=explode('-', $sel);
        $pm=(int)$M-1;
        $py=$Y;
        $nm=(int)$M+1;
        $ny=$Y;

        if($nm==13){
            $nm=1;
            $ny=$Y+1;
        }
        if($pm==0){
            $pm=12;
            $py=$Y-1;
        }
        
        
        $fd=date('w',strtotime($sel))-1;
        if($fd<0) $fd=6;
        
        $mxd=cal_days_in_month(CAL_GREGORIAN, $M, $Y);
        
        
        
        
        $blcks=$this->helper->getGenBlocks($sd,$ed);
        $product=$this->pi->getById($pid);
        
        $schs=$this->helper->getForRange($product, $sel, $ed);
        
        
        $attrs=[
            'pid'=>$pid,
            
            'dateparam'=>['m'=>[__('Jan'),__('Feb'),__('Mar'),__('Apr'),__('May'),__('Jun'),__('Jul'),__('Aug'),__('Sep'),__('Oct'),__('Nov'),__('Dec')   ],
                'w'=>[__('Mon'),__('Tue'),__('Wed'),__('Thur'),__('Fri'),__('Sat'),__('Sun')],
                'r'=>$schs,'b'=>$blcks,'now'=>date('Y-m-d',$t),'sel'=>$sel,'fd'=>$fd,'mxd'=>$mxd,'pm'=>$pm,'py'=>$py,'nm'=>$nm,'ny'=>$ny,'cm'=>$M,'cy'=>$Y]
            
            /*
            
            'dateparam'=>['m'=>[__('Jan'),__('Feb'),__('Mar'),__('Apr'),__('May'),__('Jun'),__('Jul'),__('Aug'),__('Sep'),__('Oct'),__('Nov'),__('Dec')   ],
            'w'=>[__('Sun'),__('Mon'),__('Tue'),__('Wed'),__('Thur'),__('Fri'),__('Sat')],
            'r'=>$schs,'b'=>$blcks,'now'=>date('Y-m-d',time()),'sd'=>$sd,'ed'=>$sed,'sel'=>$sel,'g'=>$r,'ed'=>$ed]
            */
            
            /*
             //  'template'=>'Ibapi_Multiv/rental.html',
             'dateparam'=>['nd'=>$nd,'m'=>[__('Jan'),__('Feb'),__('Mar'),__('Apr'),__('May'),__('Jun'),__('Jul'),__('Aug'),__('Sep'),__('Oct'),__('Nov'),__('Dec')   ],
             'w'=>[__('Sun'),__('Mon'),__('Tue'),__('Wed'),__('Thur'),__('Fri'),__('Sat')],
             'r'=>$schs,'b'=>$blcks,'now'=>$cd,'sd'=>$sd,'ed'=>$sed,'ssd'=>$ssd,'sed'=>$ed]
             */
        ];
        
        
    return $attrs;        
        
        
        
        
    }
    
    private function _catdate($sd,$n){

        
        $time=time();
        $now=$time;
        
        $ctime=strtotime('+1 day',$time);
        
        
        //list($sd,$d,$sz)=$this->helper->getSearchCookies();
        
        if(strtotime($sd)<$ctime){
            if($n<0){
                return ['error'=>1];
            }
            $sd=date('Y-m-d',$ctime);
        }
        
        
        
        
        
        /*
         list( $sdd,  $rr)=$this->helper->getSearchCookies();
         
         if(!$sd){
         $alter=true;
         $sd=$sdd;
         }
         if(!$r){
         if(!$rr){
         $rr=4;
         }
         $r=$rr;
         
         }*/
        
        
        
        
        
        if($n>0){
            $n=" +1 ";
        }
        else{
            $n=" -1 ";
        }
        $mtime=strtotime($sd." $n month");
        
        $ed='';
        $ssd='';
            
        $sel=date('Y-m-01',$mtime);
        $sed=date('Y-m-t',$mtime);
        
        
        list($Y,$M,$D)=explode('-', $sel);
        $pm=(int)$M-1;
        $py=$Y;
        $nm=(int)$M+1;
        $ny=$Y;
        if($nm==13){
            $nm=12;
            $ny=$Y+1;
        }
        if($pm==0){
            $pm=12;
            $py=$Y-1;
        }
        
        $fd=date('w',strtotime($sel))-1;
        if($fd<0){
            $fd=6;
        }
        $mxd=cal_days_in_month(CAL_GREGORIAN, $M, $Y);
        
        
        
        
        ///list($ssd,$sed)=$this->helper->getCalPair($cd);
        $blcks=$this->helper->getGenBlocks($sel,$sed);
        
        $attrs=[
            //  'template'=>'Ibapi_Multiv/rental.html',
            'dateparam'=>['m'=>[__('Jan'),__('Feb'),__('Mar'),__('Apr'),__('May'),__('Jun'),__('Jul'),__('Aug'),__('Sep'),__('Oct'),__('Nov'),__('Dec')   ],
                'w'=>[__('Mon'),__('Tue'),__('Wed'),__('Thur'),__('Fri'),__('Sat'),__('Sun')],
                'r'=>[],'b'=>$blcks,'now'=>date('Y-m-d',$time),'sd'=>'','ed'=>'','sel'=>$sel,'d'=>'','fd'=>$fd,'mxd'=>$mxd,'pm'=>$pm,'py'=>$py,'nm'=>$nm,'ny'=>$ny,'cm'=>$M,'cy'=>$Y]
        ];
        
        
            return $attrs;
            
            
    }
    
    public function execute()
    {
    $time=time();   	
      $jsonpage= $this->resultPageFactory->create(ResultFactory::TYPE_JSON);
      
      
      
       $dt=$this->getRequest()->getParam('date');
       $n=$this->getRequest()->getParam('next');
       $pid=$this->getRequest()->getParam('pid');
       $sel=$this->getRequest()->getParam('sel');
       $sd=$this->getRequest()->getParam('sd');
       if(!$pid){
           
return          $jsonpage->setData( $this->_catdate($dt, $n));
       }else{
           return          $jsonpage->setData( $this->_prodate($dt, $n,$pid));
           
           
       }
       
       
       /*
        get date, next prev.
        
        
        * 
        * 
        */
       
       if($sel){
       list($ys,$ms,$ds)=explode('-', $sel);
       if($ms<10){
           $ms='0'.$ms;
       }
       if($ds<10){
           $ds='0'.$ds;
       }
        $sel="$ys-$ms-$ds";
       
       }
       else{
           $sel=date('Y-m-d',$time);
       }
       
       
       list($yo,$mo,$do)=explode('-', $dt);

       if($mo<10){
           $mo='0'.$mo;
       }
       if($do<10){
           $do='0'.$do;
       }
       $dtn="{$yo}{$mo}{$do}";
       $td=date('Ymd');
       if($dtn<$td&&$n==-1){
           return            $jsonpage->setData(['error'=>1]);
           
       }
       
       
       $dt="$yo-$mo-$do";
       
       
       
       

       $nyr=$yo;
       
       list($cy,$cm,)=explode('-',$dt);
       if($n==-1){
            $nm=(int)$cm-1;
            
       }else{
           $nm=(int)$cm+1;
       }
       
       
       if($nm>12){
           $nyr=$yo+1;
           $nm=1;
       }
       else if($nm<1){
           $nyr=$yo-1;
           $nm=12;
       }
       
       
       if($nm<$cm&&$cy>=$nyr){
 return          $jsonpage->setData(['error'=>1]);
           
       }
       if($nm<10){
           $nm='0'.$nm;
       }
       if($do<10){
           $do='0'.$do;
       }
       
       $cd="$nyr-$nm-01";
       
       list($ssd,$sed)=$this->helper->getCalPair($cd);
       $blcks=$this->helper->getGenBlocks($ssd,$sed);
       $s='';
       $dis=false;
       $schs=[];
       
       $attrs=[
           's'=>$s,
           'disable'=>$dis,
      ///     'url'=>$this->getUrl('multiv/index/rental'),
           'pid'=>'',
         //  'cid'=>$cid,
           'cd'=>$cd,
            'ssd'=>$ssd,
           'sed'=>$sed,
           //  'template'=>'Ibapi_Multiv/rental.html',
           'dateparam'=>['nd'=>$nd,'m'=>[__('Jan'),__('Feb'),__('Mar'),__('Apr'),__('May'),__('Jun'),__('Jul'),__('Aug'),__('Sep'),__('Oct'),__('Nov'),__('Dec')   ],
               'w'=>[__('Sun'),__('Mon'),__('Tue'),__('Wed'),__('Thur'),__('Fri'),__('Sat')],
               'r'=>$schs,'b'=>$blcks,'now'=>$cd,'sd'=>$sd,'ed'=>$sed]
       ];
       
       
       
      $jsonpage->setData($attrs);

      
      
      return $jsonpage;
      
    }
}
