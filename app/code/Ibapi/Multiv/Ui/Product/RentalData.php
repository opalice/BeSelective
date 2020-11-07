<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Multiv\Ui\Product;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;
use \Ibapi\Multiv\Model\Type\RentalType;
use Magento\Ui\Component\Form;
use Magento\Ui\Component\Form\Element\Hidden;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Catalog\Model\ProductRepository;
use \Ibapi\Multiv\Model\Type\AccessoryType;
use \Ibapi\Multiv\Model\Type\ClothType;


/**
 * Class StockData hides unnecessary fields in Advanced Inventory Modal
 */
class RentalData extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    protected $locator;

    protected $urlBuilder;

/**
 * @var ProductRepository
 *
 */
   protected $productRepo;

   /**
    * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
    */
   protected $collectionFactory;

   protected $assetRepo;


   protected  $urlHelper;
   
   protected  $session;
   
   protected  $rtablefactory;
   
   protected $helper;

/**
 *
 * @param LocatorInterface $locator
 * @param UrlInterface $urlBuilder
 * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
 * @param \Magento\Framework\View\Asset\Repository $assetRepo
 */
    public function __construct(LocatorInterface $locator,UrlInterface $urlBuilder,\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
    		,\Magento\Framework\View\Asset\Repository $assetRepo,\Magento\Framework\Url $urlHelper,
    		\Magento\Backend\Model\Auth\Session $authSession,
        \Ibapi\Multiv\Model\RtableFactory $rfactory,
        \Ibapi\Multiv\Helper\Data $helper
        

    		)
    {
        
        $this->rtablefactory=$rfactory;
        $this->locator = $locator;
        $this->urlBuilder=$urlBuilder;
        $this->collectionFactory=$collectionFactory;
        $this->assetRepo=$assetRepo;
        $this->urlHelper=$urlHelper;
		$this->session=$authSession;
		$this->helper=$helper;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }
    
    public function modifyMeta(array $meta)
    {
        if(!in_array($this->locator->getProduct()->getTypeId(),[ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])){
            return $meta;
        }
        
        $data=$this->customiseCustomAttrField($meta);
        return $data;
    }

    protected function customiseCustomAttrField(array $meta)
    {
     
        $pid=$this->locator->getProduct()->getId();
        $time=time();
        
        $schs=[];

        $sel=date('Y-m-01');
        $sed=date('Y-m-t');
        $sd=$sel;
        $ed=$sed;
        
        if(!$pid){
            $pid=0;
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
        $schs=[];
        $ablcks=[];
        try{
        
        if($pid&&$this->locator->getProduct()->getId()){
           
        $schs=$this->helper->getForRange($this->locator->getProduct(), $sd, $ed);
        
        
        $ablcks=$this->helper->getAGenBlocks($sd, $ed,$pid);

        }else{
            $schs=[];
            $ablcks=[];
        }
        }catch(\Exception $e){
            
        }
        
        $blcks=$this->helper->getGenBlocks($sd,$ed);
        /*
        
        if($pid){
    
            $rtable=$this->rtablefactory->create();
            $schs=$rtable->getSchedule($pid,$sd,$ed);
        }
        */
        $meta['rental_dt']['arguments']['data']['config']=[
            'dataType' => \Magento\Ui\Component\Form\Element\DataType\Text::NAME,
            'formElement' => \Magento\Ui\Component\Form\Element\Hidden::NAME,
            'componentType' => \Magento\Ui\Component\Form\Field::NAME,
            'dataScope' => 'data.product.rental_dt',
            'provider' => 'product_form.product_form_data_source',
            'imports' => [
                'state' => '!index=product_attribute_add_form:responseStatus'
            ],
            
            'provider' => 'product_form.product_form_data_source',
            
            
        ];
        

        
        
      
            
            $dtp=['m'=>[__('Jan'),__('Feb'),__('Mar'),__('Apr'),__('May'),__('Jun'),__('Jul'),__('Aug'),__('Sep'),__('Oct'),__('Nov'),__('Dec')   ],
                'w'=>[__('Mon'),__('Tue'),__('Wed'),__('Thur'),__('Fri'),__('Sat'),__('Sun')],
                'r'=>$schs,'b'=>$blcks,'a'=>$ablcks ,'now'=>date('Y-m-d',$time),'sd'=>$sd,'ed'=>$ed,'sel'=>$sel,'fd'=>$fd,'mxd'=>$mxd,'pm'=>$pm,'py'=>$py,'nm'=>$nm,'ny'=>$ny,'cm'=>$M,'cy'=>$Y];
            
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
       
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        $meta['rental']['arguments']['data']['config'] = [
            
///            'isTemplate' => false,
            'componentType'=>\Magento\Ui\Component\Container::NAME,
            'component' => 'Ibapi_Multiv/js/rental',
///            'dataScope' => 'data.product',
   ///         'provider' => 'product_form.product_form_data_source',
      ///      'imports' => [
         //       'state' => '!index=product_attribute_add_form:responseStatus'
           /// ],
            'formElement'   => 'container',
            'visible'=>true,
            'label'=>'Rental Dates',
             'url'=>$this->urlBuilder->getUrl('multiv/rental'),
            'pid'=>$pid,
          //  'template'=>'Ibapi_Multiv/rental.html',
            'attr'=>[1,2,3],
            'dateparam'=>$dtp
            
            /*
            ['date'=>time()*1000,'m'=>[__('Jan'),__('Feb'),__('Mar'),__('Apr'),__('May'),__('Jun'),__('Jul'),__('Aug'),__('Sep'),__('Oct'),__('Nov'),__('Dec')   ],
                'w'=>[__('Sun'),__('Mon'),__('Tue'),__('Wed'),__('Thur'),__('Fri'),__('Sat')],
                'r'=>$schs]
            */
            
            
            
        ];
        
        
        
        
        return $meta;
        
    }
    
    protected function customiseCustomAttrField1(array $meta)
    {}
    
    public function modifyMeta2(array $meta)
    {}
    /**
     * {@inheritdoc}
     */
    public function modifyMeta1(array $meta)
    {}

    private function modalChild(){




    }
}
