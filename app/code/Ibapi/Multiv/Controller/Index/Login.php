<?php 
namespace Ibapi\Multiv\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;


class Login extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    protected $resultJsonFactory;
    protected  $resultRawFactory;
    
    /**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;
    protected  $customerSession;
    
    
    
    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\CustomerFactory    $customerFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
        
        
      )
        
     {
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;
        $this->customerSession = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        
        $this->resultJsonFactory=$resultJsonFactory;
        $this->resultRawFactory=$resultRawFactory;
        parent::__construct($context);
    }
    
    public function dispatch(RequestInterface $request){
        $uid=$this->customerSession->getCustomerId();
        
        if($uid){
            $this->_actionFlag->set('' ,Action::FLAG_NO_DISPATCH,1);
            $this->_actionFlag->set('', Action::FLAG_NO_DISPATCH_BLOCK_EVENT,1);
        }
        
        return  parent::dispatch($request);
    }

    public function execute()
    {
        
        $credentials = null;
        $httpBadRequestCode = 400;
        
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        try {
            $credentials = json_decode(file_get_contents("php://input"),true);
//            print_r($credentials);
        } catch (\Exception $e) {
///            echo "bad1";
            
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        if (!$credentials || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            echo "bad2";
            print_r($credentials);
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        $response = [
            'errors' => false,
            'message' => __('Login successful.'),
        ];
        
        try {
            $customer = $this->customerAccountManagement->authenticate(
                $credentials['username'],
                $credentials['password']
                );
            $this->customerSession->setCustomerDataAsLoggedIn($customer);
            $this->customerSession->regenerateId();
            $id=$this->customerSession->getCustomerId();
        
            $response = [
                'errors' => false,
                'message' => __('Login successful.'),
                'id'=>$id
            ];
            
            
        ///    $redirectRoute = $this->getAccountRedirect()->getRedirectCookie();
        } catch (EmailNotConfirmedException $e) {
            $response = [
                'errors' => true,
                'message' => $e->getMessage()
            ];
        } catch (InvalidEmailOrPasswordException $e) {
            $response = [
                'errors' => true,
                'message' => $e->getMessage()
            ];
        } catch (LocalizedException $e) {
            $response = [
                'errors' => true,
                'message' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            $response = [
                'errors' => true,
                'message' => __('Invalid login or password.')
            ];
        }
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
        
        
        
    }
}