<?php
/**
* Copyright © 2015 PlazaThemes.com. All rights reserved.

* @author PlazaThemes Team <contact@plazathemes.com>
*/
namespace Ibapi\Multiv\Controller\Adminhtml\Rental;


use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Index extends \Ibapi\Multiv\Controller\Adminhtml\Rental
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected  $pi;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Ibapi\Multiv\Helper\Data $helper,
        \Magento\Framework\Controller\ResultFactory $resultPageFactory,
        ProductRepositoryInterface $pi
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry,$helper);
        $this->pi=$pi;
    }

	/**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $jsonpage= $this->resultPageFactory->create(ResultFactory::TYPE_JSON);
        
        $dt=$this->getRequest()->getParam('date');
        $pid=$this->getRequest()->getParam('pid');
        $n=$this->getRequest()->getParam('next');
        list($y,$m,$d)=explode('-',$dt);
        $st=strtotime($dt);
        
        
        
        if($n!=-1&&$n!=1){
            return $jsonpage->setData(['error'=>1]);
        }
        if($n==1){
            $n="+1";
        }
        $stt=strtotime("$n month" ,$st);
        $sel=$sd=date('Y-m-01',$stt);
        $ed=date('Y-m-t',$stt);
        
        
        $t=$time=time();
        
        $schs=[];
        
        
        if($stt<$t){
            list(,$M1,)=explode('-', $sel);
            list(,$M2,)=explode('-', date('Y-m-d',$t));
            
            if($M1<$M2)
                return $jsonpage->setData(['error'=>1]);
                
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
        
        
        $product=$this->pi->getById($pid);
        
        $schs=$this->helper->getForRange($product, $sd, $ed);
        
        $blcks=$this->helper->getGenBlocks($sd,$ed);
        
        $ablcks=$this->helper->getAGenBlocks($sd, $ed,$pid);
        
        
        
        
        
        
        $dtp=['m'=>[__('Jan'),__('Feb'),__('Mar'),__('Apr'),__('May'),__('Jun'),__('Jul'),__('Aug'),__('Sep'),__('Oct'),__('Nov'),__('Dec')   ],
            'w'=>[__('Sun'),__('Mon'),__('Tue'),__('Wed'),__('Thur'),__('Fri'),__('Sat')],
            'r'=>$schs,'b'=>$blcks,'a'=>$ablcks ,'now'=>date('Y-m-d',$time),'sd'=>$sd,'ed'=>$ed,'sel'=>$sel,'fd'=>$fd,'mxd'=>$mxd,'pm'=>$pm,'py'=>$py,'nm'=>$nm,'ny'=>$ny,'cm'=>$M,'cy'=>$Y];
        
        
        
        
        return $jsonpage->setData(['dateparam'=>$dtp]);
        
        

        
    }
}
