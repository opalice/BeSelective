<?php
namespace Ibapi\Regq\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;

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
    protected $resultRawFactory;

    protected $customerSession;
    protected $customerAccountManagement;
    protected $helper;
    /**
     * @var PageFactory
     */
    private $factory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        PageFactory $factory

    )

    {
        parent::__construct($context);
        $this->factory = $factory;
    }


    public  function  execute()
    {


return $this->factory->create();


    }


}