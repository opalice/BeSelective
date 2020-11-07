<?php
namespace Ibapi\Multiv\Controller\Index;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;

class Product extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected  $repo;
    protected  $helper;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        ProductRepository $productRepo,
        \Ibapi\Multiv\Helper\Data $helper,
        
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;        
        parent::__construct($context);
        $this->repo=$productRepo;
        $this->helper=$helper;    
    }
    
    public function dispatch(RequestInterface $request){
        
        $uid=$this->helper->getCustomerId();
        
        if(!$uid){
            $this->_actionFlag->set('' ,Action::FLAG_NO_DISPATCH,1);
            $this->_actionFlag->set('', Action::FLAG_NO_DISPATCH_BLOCK_EVENT,1);
        }
        
        return  parent::dispatch($request);
    }
    
    
    public function execute()
    {
        
        
        
        
    	
        return $this->resultPageFactory->create();  
    }
}
