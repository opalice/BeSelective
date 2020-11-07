<?php
namespace Netbaseteam\Faq\Controller\Index\Contact;

/**
 * Interceptor class for @see \Netbaseteam\Faq\Controller\Index\Contact
 */
class Interceptor extends \Netbaseteam\Faq\Controller\Index\Contact implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Captcha\Observer\CaptchaStringResolver $captchaStringResolver, \Magento\Captcha\Helper\Data $captchaHelper, \Netbaseteam\Faq\Helper\Data $faqHelper)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $dateTime, $timezone, $resultJsonFactory, $captchaStringResolver, $captchaHelper, $faqHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
