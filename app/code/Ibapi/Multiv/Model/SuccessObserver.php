<?php 
namespace  Ibapi\Multiv\Model;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;

class SuccessObserver implements ObserverInterface
{
    protected $_productRepository;
    protected $_cart;
    protected $helper;
    protected $_request;
    public function __construct(\Ibapi\Multiv\Helper\Data $helper, \Magento\Checkout\Model\Session $cart){
        $this->_cart = $cart;
        $this->helper=$helper;
    }
    /*
     * https://github.com/magento/magento2/pull/5807/files#diff-1
     *     \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Request\Http $request        
    ) {$this->_quote = null;
        $this->setQuoteId(null);
        $this->setLastSuccessQuoteId(null);
        $this->setLoadInactive(false);
        $this->replaceQuote($this->getQuote()->save());
        $this->quote = $checkoutSession->getQuote();
        $this->request = $request;
     */
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**@var $item \Magento\Quote\Model\Quote\Item\AbstractItem */

        $this->_cart->setCustomerData(null)->clearQuote()->clearStorage();
        $this->_cart->setLoadInactive(false);
        $this->_cart->replaceQuote($this->_cart->getQuote()->save());


        $this->helper->log("checkoutsuccess");



        }
}