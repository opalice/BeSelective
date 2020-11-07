<?php
namespace Ibapi\Multiv\Controller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;



abstract  class ControllerInterface extends Action
{
    
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
        ) {
            $this->resultPageFactory = $resultPageFactory;
            parent::__construct($context);
    }
    
    
}
