<?php 
namespace  Ibapi\Multiv\Block\Product;
use Magento\Customer\Model\Context;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Url;
use Magento\Review\Model\ResourceModel\Rating\Collection as RatingCollection;
use Magento\Review\Block\Form;
use Magento\Review\Model\Review;


class ReviewForm extends  \Magento\Review\Block\Form{
    
    private $_helper;
    private $_reviewFactory;
    private $_voteFactory;
    private $_imgUploader;
    private $review;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Review\Helper\Data $reviewData,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Model\Url $customerUrl,
        array $data = [],
        \Magento\Framework\Serialize\Serializer\Json $serializer = null,
        \Magento\Review\Model\Rating\Option\VoteFactory $voteFactory,
        \Ibapi\Multiv\Helper\Data $helper,
        \Ibapi\Multiv\Model\ImageUploader $imgUp
        )
    
    {
        parent::__construct($context, $urlEncoder, $reviewData, $productRepository, $ratingFactory, $messageManager, $httpContext, $customerUrl,$data,$serializer);
        $this->_helper=$helper;
        $this->_voteFactory=$voteFactory;
        $this->_reviewFactory=$reviewFactory;
        $this->_imgUploader=$imgUp;
        $this->review=null;
    }
    
    protected function _construct()
    {
        $this->setAllowWriteReviewFlag(
            $this->httpContext->getValue(Context::CONTEXT_AUTH)
            
            );
        if (!$this->getAllowWriteReviewFlag()) {
            $queryParam = $this->urlEncoder->encode(
                $this->getUrl('*/*/*', ['_current' => true]) 
                );
            $this->setLoginLink(
                $this->getUrl(
                    'customer/account/login/',
                    [Url::REFERER_QUERY_PARAM_NAME => $queryParam]
                    )
                );
        }
        
        $this->setTemplate('reviewform.phtml');
        
    }
    
    
    /**
     * Retrieve current product model from registry
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProductData()
    {
        return parent::getProductInfo();
//        return $this->_coreRegistry->registry('current_product');
    }
    public function getReview(){
        if($this->review==null){

            $cid=$this->_helper->getCustomerId();
            if(!$cid){
                return false;
            }
            
        
            $product=$this->getProductData();
            $rcol=$this->_reviewFactory->create()->getCollection();
            /*@var $rcol \Magento\Review\Model\ResourceModel\Review\Collection */
            $storeId=(int)$this->_storeManager->getStore()->getId();
            //
            $review=$rcol->addCustomerFilter($cid)->addStoreFilter($storeId)->addStatusFilter(Review::STATUS_PENDING)->addEntityFilter('product',
                $product->getId())->getLastItem();
            
                $this->review=$review;
        
        }

        
        
        return $this->review;
        
    }
    
    public function getDetail(){
        
        $review=$this->getReview();
           if(!$review){
               return json_encode([]);
           }

           $storeId=$this->_storeManager->getStore()->getId();
           /*@var $review \Magento\Review\Model\Review */
           
           $ratings= $this->_voteFactory->create()->getResourceCollection()->setReviewFilter(
               $review->getId()
               )->addRatingInfo(
                       $storeId
                       )->getItems();
                      
       $url=$this->_imgUploader->getImg('rev'.$review->getId().'.jpg','review');
        
                       
           /**@var $ratings \Magento\Review\Model\ResourceModel\Rating[] */            

                       $rats=[];
                       foreach($ratings as $r){
                           $rats[]=[$r->getId(),$r->getRatingCode(),$r->getPercent()];
                       }
       return json_encode(['id'=>$review->getId(),'url'=>$url,'title'=>$review->getTitle(),'detail'=>$review->getDetail(),'nickname'=>$review->getNickname(),'ratings'=>$rats]);               
                       
           /*getRatingCode */
/*getPercent() *//* review->getDetail() */
                       
           
    }
    public function getUploadConfig(){
        
        return json_encode(
            
            [
                'url'=>$this->getUploadUrl(),
                 'photo'=>$this->getViewFileUrl('images/no-photo.jpg')
                
                
            ]);
        
    }
    
    
    public function getUploadUrl(){
        
        return $this->_urlBuilder->getRouteUrl('multiv/index/image');
    }
    public function getConfig(){
        return json_encode([]);
    }
    
    
}