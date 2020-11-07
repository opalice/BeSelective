<?php


namespace Ibapi\Multiv\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;

class VipConfigProvider implements ConfigProviderInterface
{
    protected $helper;

    protected $checkoutSession;


    /**
     * @param \Ibapi\Multiv\Helper\Data $helper
     * @param Session $checkoutSession
     * @param CalculatorInterface $calculator
     */
    public function __construct(\Ibapi\Multiv\Helper\Data $helper, Session $checkoutSession) {
        $this->helper = $helper;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $Config = [];
        $quote = $this->checkoutSession->getQuote();
        $d = $this->helper->getDiscount($quote)*-1;

        $Config['vipdiscount_title'] = __('Vip Discount');
        $Config['vipdiscount_amount'] = $d;
        $Config['show_hide_vipdiscount'] = $d > 0.0;
        return $Config;
    }
}
