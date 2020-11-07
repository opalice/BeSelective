<?php
namespace Ibapi\Multiv\Block;
use Magento\Framework\View\Element;
use Magento\Framework\View\Element\Template;
class Category extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Ibapi\Multiv\Helper\Data
     */
    protected  $helper;
    
    public function __construct(Template\Context $context,\Ibapi\Multiv\Helper\Data $helper, array $data = [])
    {
        parent::__construct($context, $data);
        $this->helper=$helper;
        
    }
    function validate(&$date, $format = 'Y-m-d')
    {
        try{
            list($y,$m,$d)=explode('-',$date);
            $m=(int)$m;
            $d=(int)$d;
            if($m<10){
                $m='0'.$m;
            }
            if($d<10){
                $d='0'.$d;
            }
            $date="$y-$m-$d";
            
            
            $d = \DateTime::createFromFormat($format, $date);
            return [$d && $d->format($format) == $date ,$d->getTimestamp()];
        }catch(\Exception $e){
            return [false,false];
        }
    }
    function getSizes(){
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Size::class);
        return $obj->getAllOptions();
        
    }
    
    function  getConfig(){
       $time=time();
       $now=$time;
       
       $ctime=strtotime('+1 day',$time);
       $cday=date('Ymd',$ctime);
      
       list($sd,$d,$sz)=$this->helper->getSearchVal();
       if($sd){
           $stt=strtotime($sd);
           if(date('Ymd',$stt)<$cday){
           $sd='';
       }else{
           $sd=date('Y-m-d',$stt);
       }
       }
       
       if(!in_array($d, [8,4])){
           $d=4;
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
        
        
        

        
        
        
        
        $ed='';
        $ssd='';
        if($sd){
        
            $sel=date('Y-m-01',strtotime($sd));
            
            ///list($ssd,$ed)=$this->helper->getCalPair($sd);
        
            list(,$sed)=$this->helper->getDatePair($sd,$d);
        
        
        }else{
            $sel=date('Y-m-01');
            $ed='';
            $sed='';
//            list($ssd,$ed)=$this->helper->getCalPair(date('Y-m-d',$ctime));
        
        }
        
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
        
        
        
        
        $blcks=$this->helper->getGenBlocks($ssd,$ed);
        
        $attrs=[
           's'=>$sz,
         'disable'=>'',
        'url'=>$this->getUrl('multiv/index/rental'),
        'pid'=>'',
         'cid'=>'',
        //  'template'=>'Ibapi_Multiv/rental.html',
        'dateparam'=>['m'=>[__('Jan'),__('Feb'),__('Mar'),__('Apr'),__('May'),__('Jun'),__('Jul'),__('Aug'),__('Sep'),__('Oct'),__('Nov'),__('Dec')   ],
            'w'=>[__('Mon'),__('Tue'),__('Wed'),__('Thur'),__('Fri'),__('Sat'),__('Sun')],
            'r'=>[],'b'=>$blcks,'now'=>date('Y-m-d',$time),'sd'=>$sd,'ed'=>$sed,'sel'=>$sel,'d'=>$d,'fd'=>$fd,'mxd'=>$mxd,'pm'=>$pm,'py'=>$py,'nm'=>$nm,'ny'=>$ny,'cm'=>$M,'cy'=>$Y]
            ];
        
        return json_encode($attrs);
        
    }
}
