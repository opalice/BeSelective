<?php 
namespace  Ibapi\Multiv\Block\Adminhtml\Review;

use Magento\Review\Block\Adminhtml\Edit\Form;

class Edit extends  \Magento\Review\Block\Adminhtml\Edit\Form {
    protected $imgup;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Customer\APi\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Review\Helper\Data $reviewData
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Review\Helper\Data $reviewData,
        \Ibapi\Multiv\Model\ImageUploader $imgup,
        array $data = []
        ) {
        
            parent::__construct($context, $registry, $formFactory, $systemStore, $customerRepository, $productFactory, $reviewData,$data);
            $this->imgup=$imgup;
    }
    
    
    protected  function _prepareForm(){
        $t=parent::_prepareForm();
        $form=$this->getForm();
        $fieldset = $form->addFieldset(
            'review_image',
            ['legend' => __('Review Image'), 'class' => 'fieldset-wide']
            );
        $review = $this->_coreRegistry->registry('review_data');
        /*@var $review \Magento\Review\Model\Review */
        $id=$review->getId();
        
        $rx=$review->getCollection()->addFieldToFilter('main_table.review_id',$id)->getFirstItem();
        $cid=$rx->getCustomerId();
        
        $img='<strong>n/a</a>';
        $url=$this->imgup->getImg('rev'.$id.'_'.$cid.'.jpg','review');
        if($url)
        $img="<img src='$url'>";
        
        $fieldset->addField('label', 'label', ['after_element_html'=>$img]);
      
        
        return $t;
    }

}