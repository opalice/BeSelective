<?php
namespace Mageplaza\Smtp\Controller\Adminhtml\Index\Index;

/**
 * Interceptor class for @see \Mageplaza\Smtp\Controller\Adminhtml\Index\Index
 */
class Interceptor extends \Mageplaza\Smtp\Controller\Adminhtml\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Framework\Encryption\EncryptorInterface $encryptor, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Mail\Template\SenderResolverInterface $senderResolver, \Mageplaza\Smtp\Helper\Data $smtpDataHelper)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $jsonHelper, $encryptor, $logger, $senderResolver, $smtpDataHelper);
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
