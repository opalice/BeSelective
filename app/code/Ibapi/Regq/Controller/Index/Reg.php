<?php 
namespace Ibapi\Regq\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;

class Reg extends \Magento\Framework\App\Action\Action
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
    protected $resultRawFactory;
    
    protected  $customerSession;
    protected $customerAccountManagement;
    protected $helper;
    
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
        \Magento\Framework\Json\Helper\Data $helper,
        
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
        
        
        )
        
     {
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;
       //// $this->resultPageFactory=$resultPageFactory;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->customerSession = $customerSession;
        $this->resultJsonFactory=$resultJsonFactory;
        $this->resultRawFactory=$resultRawFactory;
        $this->helper=$helper;
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
    
    
    public function login($email,$pw){
        
        
        
        $response = [
            'errors' => false,
            'message' => __('Login successful.'),
        ];
        
        try {
            $customer = $this->customerAccountManagement->authenticate(
                $email,
                $pw
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
    public function execute(){
        $websiteId  = $this->storeManager->getDefaultStoreView()->getWebsiteId();
        
        
        $credentials = null;
        $httpBadRequestCode = 400;
        
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        try {
            $credentials = $this->helper->jsonDecode($this->getRequest()->getContent());
        } catch (\Exception $e) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        if (!$credentials || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        
        $response = [
            'errors' => false,
            'message' => __('Registration successful.')
        ];
        try {


            $customer   = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId); /* changed line */
            
            $c=$customer->loadByEmail($credentials['username']);
            if($c&&$c->getId()){
                $this->messageManager->addErrorMessage(__('Username already exist. login to your account.'));
                
                $response = [
                    'errors' => true,
                    'message' => __('Username exist.')
                ];
                
                /** @var \Magento\Framework\Controller\Result\Json $resultJson */
                $resultJson = $this->resultJsonFactory->create();
                return $resultJson->setData($response);
                
                
            }
            
            $customer->setWebsiteId($websiteId);
            
            // Preparing data for new customer
            $customer->setEmail($credentials['username']);
            $customer->setFirstname($credentials['fname']);
            $customer->setLastname($credentials['lname']);
            $customer->setPassword($credentials['password']);
            
            // Save data
            $customer->save(false);
            $customer->setConfirmation(null);
            $customer->setWebsiteId($websiteId); /* changed line */
            $customer->sendNewAccountEmail();
            $this->messageManager->addSuccessMessage(__('Registered and logged in.'));
            return $this->login($credentials['username'], $credentials['password']);
            
            
            
            
            
            
            
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
                'type'=>1,
                'trace'=>$e->getTraceAsString(),
                'message' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            $response = [
                'errors' => true,
                'type'=>2,
                
                'message' => __('Invalid login or password.')
            ];
        }
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
        
    }
    

    public function execute1()
    {
        // Get Website ID
        $websiteId  = $this->storeManager->getDefaultStoreView()->getWebsiteId();

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
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        
        // Instantiate object (this is the most important part)
        $customer   = $this->customerFactory->create();
        $c=$customer->loadByEmail($credentials['username']);
        if($c&&$c->getId()){
            $this->messageManager->addErrorMessage(__('Username already exist. login to your account.'));

            $response = [
                'errors' => true,
                'message' => __('Username exist.')
            ];
        
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
        
        
        }
        
        $customer->setWebsiteId($websiteId);

        // Preparing data for new customer
        $customer->setEmail($credentials['username']); 
        $customer->setFirstname($credentials['fname']);
        $customer->setLastname($credentials['lname']);
        $customer->setPassword($credentials['password']);

        // Save data
        $customer->save();
        $customer->sendNewAccountEmail();
        $this->messageManager->addSuccessMessage(__('Registered and logged in.'));
        return $this->login($credentials['username'], $credentials['password']);
        
///        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['id'=>$id]);
        
    }
}