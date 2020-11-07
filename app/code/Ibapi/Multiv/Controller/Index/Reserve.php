<?php
namespace  Ibapi\Multiv\Controller\Index;
use Magento\Catalog\Model\ProductRepository;

class  Reserve extends \Magento\Framework\App\Action\Action
{
    protected $helper;
    protected $resultPageFactory;
    protected $pi;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultPageFactory,
        ProductRepository $pi,
        \Ibapi\Multiv\Helper\Data $helper
        
        )
    {
        $this->resultPageFactory = $resultPageFactory;        
        parent::__construct($context);
        $this->helper=$helper;
        $this->pi=$pi;
    }
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

    
    
}