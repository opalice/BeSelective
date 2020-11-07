<?php
namespace Ibapi\Multiv\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Data\Form\FormKey;

class  Cart extends \Magento\Framework\App\Action\Action
{
    protected $helper;
    protected $resultPageFactory;
    protected  $pri;
    protected $_cart;
    protected $formKey;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultPageFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository, \Magento\Checkout\Model\Cart $cart,
        \Ibapi\Multiv\Helper\Data $helper,
        FormKey $formKey

        )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->helper=$helper;
        $this->_cart=$cart;
        $this->pri=$productRepository;
        $this->formKey=$formKey;
    }

    public function execute(){

        $jsonpage= $this->resultPageFactory->create(ResultFactory::TYPE_JSON);
        $rg=$this->getRequest()->getParam('key');
        if($rg){
            $key=$this->formKey->getFormKey();
            
            return $jsonpage->setData(['ok'=>'1','key'=>$key]);
            
            
        }
        
        $rg=$this->getRequest()->getParam('range');
        if($rg){
            $st=$this->getRequest()->getParam('st');
            $et=$this->getRequest()->getParam('et');
            $qid=$this->_cart->getQuote()->getId();
            $this->helper->log("adding  st $st ed $et qid $qid");
            
            $this->helper->addSt($st,$et,$this->_cart->getQuote()->getId());
            return $jsonpage->setData(['ok'=>'1','message'=>__('Done')]);
            
            
        }
        
        
        try{
            $pid=$this->helper->getValue('rentals/procat/subid');

            $params = array(
                'product' => $pid,
                'qty' => 1
            );

            if(in_array($pid,$this->_cart->getProductIds())){
                $this->messageManager->addNotice(__('Subscription already in cart'));
                return $jsonpage->setData(['ok'=>'1','message'=>__('Subscription already in cart.')]);
            }

            if( $this->helper->getCustomerGroup()==5){

                $this->messageManager->addNotice(__('Already Subscribed'));
                return $jsonpage->setData(['ok'=>'1','message'=>__('Already Subscribed')]);

            }

            $_product = $this->pri->getById($pid);
            $this->_cart->addProduct($_product,$params);
            $this->_cart->save();
            $this->messageManager->addSuccess(__('Subscription added'));

            return $jsonpage->setData(['ok'=>'1','message'=>__('Subcription added')]);


        }catch(\Exception $e){
            throw new LocalizedException(__('Cannot subscribe now ') );
          }




    }


    }
