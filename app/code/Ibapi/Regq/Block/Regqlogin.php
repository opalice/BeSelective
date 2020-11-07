<?php
namespace  Ibapi\Regq\Block;

use Magento\Framework\View\Element\Template;

class Regqlogin extends  \Magento\Framework\View\Element\Template {
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
     //   $this->setTemplate('Ibapi_Regq::login.phtml');
    }


}
