<?php
namespace  Ibapi\Multiv\Model;

use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;

class CustomerLogin  implements  ObserverInterface
{
    var $sess;
    var $helper;
    var $cart;
    /**
     * @var QuoteRepository
     */
    private $quoteRepo;

    public  function  __construct(

    \Magento\Checkout\Model\Session $session,
\Magento\Checkout\Model\Cart $cart,

    QuoteRepository $quoteRepo,
                              \Ibapi\Multiv\Model\RtableFactory $rtfact,\Ibapi\Multiv\Helper\Data $helper
)
{

    $this->rtfact=$rtfact;
    $this->sess=$session;
    $this->helper=$helper;
    $this->cart=$cart;
    $this->quoteRepo = $quoteRepo;
}


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
/**@var $rt \Ibapi\Multiv\Model\Rtable */

        $rt=$this->rtfact->create();

       $cust=$observer->getEvent()->getCustomer();

       $cid=$cust->getId();
      $quotes=  $rt->unreserveByCust($this->helper->getCustomerId());
      $this->helper->log('customerlogin:'."quotestodelete ".print_r($quotes,1)." event ".$observer->getEvent()->getName()." cid $cid");
      foreach ($quotes as $quote_id) {
          $this->helper->log("customerlogin:will delete ".$quote_id.' OF '.$this->helper->getCustomerId());

          try {
              $qote = $this->quoteRepo->get($quote_id);
              if ($qote) {
                  $this->quoteRepo->delete($qote);
                  $this->helper->log("customerlogin: deleted ".$qote->getId());

              }
          }catch (\Exception $e){
              $this->helper->log('customerlogin: error in dlete'.$e->getMessage());
          }
      }




   }

}