<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Catalog layered navigation view block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Ibapi\Multiv\Block;




/**
 * @api
 * @since 100.0.2
 */
class Navigation extends \Magento\LayeredNavigation\Block\Navigation
{
        var $helper;
        var $_currency;
        private $_filters;
        /**
     * @param Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Model\Layer\FilterList $filterList
     * @param \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag
     * @param array $data
     */

      
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag,
        \Magento\Directory\Model\Currency $currency,
        \Ibapi\Multiv\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $layerResolver, $filterList, $visibilityFlag,$data);
        $this->helper=$helper;
        $this->_currency = $currency;
        $this->_filters=null;
    }
    
    public function getCurrencySymbol(){
        return $this->_currency->getCurrencySymbol();
    }

    /**
     * Apply layer
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        
        foreach ($this->filterList->getFilters($this->_catalogLayer) as $filter) {
//            $filter->apply($this->getRequest());
        }
//        $this->getLayer()->apply();
        return parent::_prepareLayout();
    }
    public function getModUrl($name,$val='YYYYY'){
    
        return $this->getUrl('*/*/*', array('_current' => true,'_use_rewrite' => true, '_query' => [$name=>$val]));
        

    }
    public function canShowSize(){
        $cat=$this->getLayer()->getCurrentCategory()->getId();

        $cats=explode(',',$this->_scopeConfig->getValue("rentals/procat/ccats"));
        $cats=array_filter($cats,function($c,$v){
            if($c){
                return true;
            }
            return false;
        },ARRAY_FILTER_USE_BOTH);
            
       return     in_array($cat,$cats);
    }
    public function getPriceRequest(){
        
        
    }
    public function getRentDays(){
        list($sd,$d,)=$this->helper->getSearchCookies();
        return $d!=8?4:8;        
    }
    
    public function getPriceSelect(){
        
        list($sd,$d,)=$this->helper->getSearchCookies();
        $code=$d!=8?'rent4':'rent8';
        file_put_contents("rent8.txt", "D $d code $code ",FILE_APPEND);
        $pr=$this->getRequest()->getParam($code);
        
        if($pr){
            list($min,$max)=explode(',', $pr);
            
        }
      
        return json_encode([$min??10,$max??200]);
        
        
    }
    
    public function getPriceUrl(){
    
        list($sd,$d,)=$this->helper->getSearchCookies();
        $code=$d!=8?'rent4':'rent8';
        return $this->getUrl('*/*/*', array('_current' => true,'_use_rewrite' => true, '_query' => [$code=>'XXXXX,ZZZZZ']));
        
        
    }
    
    public function createCatUrl($cid){
        
        if(!is_numeric($cid)){
            
            $url=$cid;
            $qs=$_SERVER['QUERY_STRING'];
            
            if(strstr($url,'?')){
                return $url.'&'.$qs;
            }
            return $url.'?'.$qs;
            
            
        }
        
        return $this->getUrl('*/*/*', array('_current' => true,'_use_rewrite' => true, '_query' => ['cat'=>$cid]));
        
    }
    public function getFilterValue($name){
        
        return $this->getRequest()->getParam($name,'');
        
    }
    
    
    
    public function getModVal($name){
     return $this->getRequest()->getParam($name,'');
     
        
    }
    public function getAttUrls(){
        
        return json_encode(['brand'=>$this->getModUrl('brand'),'color'=>$this->getModUrl('color'),'length'=>$this->getModUrl('length'),'size'=>$this->getModUrl('size')]);
    }
    public function getAttVals(){
        
        return json_encode(['brand'=>$this->getModVal('brand'),'color'=>$this->getModVal('color'),'length'=>$this->getModVal('length'),'size'=>$this->getModVal('size')]);
    }
    public function makeRent(){
      
        $filters=$this->filterList->getFilters($this->getLayer());
        $val=[];
        $var='';
        foreach($filters as $filter){
            
            
        }
        
    }
    public function getFilterOptions($name){
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if(is_null($this->_filters)){
            
        /*
        

        switch($name){
            case 'brand':
            $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Brand::class);
            break;
            case 'color':
                $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Color::class);
                break;
            case 'size':
                $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Size::class);
                break;
            case 'length':
                $obj=$objectManager->create(\Ibapi\Multiv\Model\Attribute\Source\Length::class);
                break;
                
                
        
        }
        

        $opts= $obj->getAllOptions();
        array_shift($opts);
        */
///        $hs=array_map(function($a){ return $a['value'];}, $opts);
        
        $fn=false;
        $filters=$this->filterList->getFilters($this->getLayer());
        $val=[];
        $var='';
        $opts=[];
        foreach($filters as $filter){
            $n=$filter->getName();
            
            $items=$filter->getItems();
            $var=$filter->getRequestVar();
            $ci=count($items);
            $data="recie $ci name $var name $name \n";
            
            
            $vals=$filter->getValue();
            
            if(!is_array($vals)){
                $vals=explode(',', $vals);
            }
            $opts=[];
            $i=0;
            foreach($items as $item){
              $nm=$item->getLabel();
              $vl=$item->getValue();
              $cnt=$item->getCount();
              $data="item $nm vl $vl cnt $cnt \n";
              file_put_contents("navx.txt", $data,FILE_APPEND);
              
              
              $d=array_search($vl, $vals);
              $sel='';
              if($d!==false){
                    $fn=true;
                  $sel='YES';
              }

              //$d=array_search($vl, $opvals);
              //if($item->getCount()){
               //   $fn=true;
              //}
            
                  $opts[$i]['label']=$nm;
                  $opts[$i]['value']=$vl;
                  $opts[$i]['sel']=$sel;
                  $opts[$i++]['cnt']=(int)$item->getCount();
              
            }
            
            /*s            
            foreach($opts as &$opt){
                if(!isset($opt['sel'])){
                    $opt['sel']=false;
                    if(!isset($opt['cnt'])){
                        $opt['cnt']=0;
                    }
                    
                }
                
            }*/
            $this->_filters[$var]=$opts;
            ///break;
        }
    }
            
            
       /// $val=$this->getRequest()->getParam($name);
        ///$val=explode(',', $val);
          //  $fn=$fn||!count($filters);
        return $this->_filters[$name]??[];
    }
    

    /**
     * Check availability display layer block
     *
     * @return bool
     */
    public function canShowBlock()
    {
        
        return true;
    }
    
    
}
