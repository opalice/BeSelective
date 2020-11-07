<?php

namespace Ibapi\Multiv\Controller\Adminhtml\Reserve;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action
{
	protected $resultPageFactory = false;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
	    
	    $a=$this->getRequest()->getParam('isAjax');
	
	    if($a){
	         $this->_forward('action');
	        
	        


	    }
	    
	    
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->prepend((__('Reservations')));
		
		return $resultPage;
	}


}