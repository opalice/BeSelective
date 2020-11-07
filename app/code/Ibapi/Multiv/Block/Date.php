<?php
namespace Ibapi\Multiv\Block;
use Magento\Catalog\Block\Product\View\AbstractView;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;

class Date extends \Magento\Catalog\Block\Product\View\Type\Simple
{
    protected $helper;
    protected  $colfact;
    protected $cart;
    
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        \Ibapi\Multiv\Helper\Data $helper,
        \Magento\Checkout\Model\Cart $cart,

        array $data = []
        ) {
        parent::__construct($context, $arrayUtils,$data);
        $this->helper=$helper;
        $this->cart=$cart;
    }
    function getFormattedRent(){
        
     return  $this->helper->getFormattedAmt($this->getProduct()->getData('rent'));        
    }
    function getRent(){
        
        return preg_replace('/[^\d\.]/','',$this->getProduct()->getData('rent'));
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
    
    function  getUserData(){
        
        return json_encode([]);
        
    }
    function  getConfig(){
        $pid=$this->getProduct()->getId();
        $product=$this->getProduct();
        
        $time=time();
        $ctime=strtotime('+1 day',$time);
        
        $schs=[];
        
        $cid=$this->helper->getCustomerId();
        if($cid){
        $quote=$this->cart->getQuote();
        
        foreach ($quote->getAllItems() as $item){
            
            if($item->getProductType()!==AccessoryType::TYPE_CODE&&$item->getProductType()!==ClothType::TYPE_CODE){
                continue;
            }
            $v=$item->getOptionByCode('rental_option');
            
            if($v){
                $v=$v->getValue();
                $s=$this->helper->canreserve($v, $pid, $product->getData('wash'));
                if($s){
                return json_encode(['rental_option'=>$v,'sd'=>$s[0],'ed'=>$s[1]]);
                }else{
                    return json_encode(['rental_option'=>'x','msg'=>__('Date not available for this product.')]);
                }
            }
            
        break;        
        }
        }
        
        list($sd,$d,$sz)=$this->helper->getSearchCookies();

        if(strtotime($sd)<$ctime){
            $sd='';
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
            
            $dx=$d-1;
            $ed=date('Y-m-d',strtotime("+$dx days",strtotime($sd)));
            
            if(! $this->helper->isFree($pid,$sd, $ed)){
                $sd='';
            }
        }
        
        if($sd){
            
            
            $sel=date('Y-m-01',strtotime($sd));
            //list($ssd,$ed)=$this->helper->getCalPair($sd);
            $sed=date('Y-m-t',strtotime($sd));          
            
            
//            list(,$sed)=$this->helper->getDatePair($sd,$d);
            
            
            
            
        }else{
            $sel=date('Y-m-01');
            $sed=date('Y-m-t');
            $ed='';
    //            list($ssd,$ed)=$this->helper->getCalPair(date('Y-m-d',$ctime));
          
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
        if($fd<0){
            $fd=6;
        }
        $mxd=cal_days_in_month(CAL_GREGORIAN, $M, $Y);
        
        
        
        $dis='';        
        
        
       $blcks=$this->helper->getGenBlocks($sel,$sed);

        $wsh=(int)$product->getData('wash');              
       $schs=$this->helper->getForRange($product, $sel, $sed);
        $rent4=$this->getProduct()->getData('rent4');
        $rent8=$this->getProduct()->getData('rent8');
        
        $depo=$this->getProduct()->getData('deposit');
        if(!$depo){
            $depo=$this->getProduct()->getData('Deposit');
            
        }
        if(!$depo){
            $depo=0;
        }
        
        $sze=$this->getProduct()->getData('size');
        
        $sizes=[];
        
        /*@var $item \Magento\Catalog\Model\Product  */
        /*
        array_map(function($item) use($sze){
           
            return [$item->getId() ,$item->getData('size'),$item->getProductUrl()];
            
        }, $this->helper->getSizes($this->getProduct()->getData('skugr')));
        */
        
        
        
        
       $attrs=[
           's'=>$sz,
           'size'=>$sze,
           'disable'=>$dis,
           ///     'url'=>$this->getUrl('multiv/index/rental'),
           'pid'=>$pid,
           'sizes'=>array_values($sizes),
           //  'cid'=>$cid,
           'cd'=>'',
           'rent4'=>$rent4,
           'wash'=>$wsh,
           
            'rent8'=>$rent8,
           'deposit'=>$depo,
           'ssd'=>'',
           'sed'=>$sed,
           'url'=>$this->getUrl('multiv/index/rental'),
           
           'dateparam'=>['m'=>[__('Jan'),__('Feb'),__('Mar'),__('Apr'),__('May'),__('Jun'),__('Jul'),__('Aug'),__('Sep'),__('Oct'),__('Nov'),__('Dec')   ],
               'w'=>[__('Mon'),__('Tue'),__('Wed'),__('Thur'),__('Fri'),__('Sat'),__('Sun')],
               'r'=>$schs,'b'=>$blcks,'now'=>date('Y-m-d',$time),'sd'=>$sd,'ed'=>$ed,'sel'=>$sel,'d'=>$d,'fd'=>$fd,'mxd'=>$mxd,'pm'=>$pm,'py'=>$py,'nm'=>$nm,'ny'=>$ny,'cm'=>$M,'cy'=>$Y]
       
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
       
       
       
       
       
       
       
       

       
       
       return json_encode($attrs);
        
    }
}
