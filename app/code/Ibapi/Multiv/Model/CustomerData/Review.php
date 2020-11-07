<?php 
namespace  Ibapi\Multiv\Model\CustomerData;

class Review extends  \Magento\Review\CustomerData\Review{
    
    protected $_ratingFactory;
    protected $_reviewFactory;
    protected $_voteFactory;
    public function __construct(\Magento\Framework\Session\Generic $reviewSession
        ,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\Rating\Option\VoteFactory $voteFactory,
        \Ibapi\Multiv\Helper\Data $helper
        
        ){
    parent::__construct($reviewSession);
    
        $this->_voteFactory=$voteFactory;
        $this->_ratingFactory=$ratingFactory;
        $this->_reviewFactory=$reviewFactory;
        
    }
 
    public function getSectionData()
    {
        
        
        
        
        return (array)$this->reviewSession->getFormData(true) + ['nickname' => '','title' => '', 'detail' => '','ratings'=>$ratings];
    }
    
}