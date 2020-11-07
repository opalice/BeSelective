<?php
namespace Netbaseteam\Faq\Controller\Adminhtml\Index\Save;

/**
 * Interceptor class for @see \Netbaseteam\Faq\Controller\Adminhtml\Index\Save
 */
class Interceptor extends \Netbaseteam\Faq\Controller\Adminhtml\Index\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Netbaseteam\Faq\Controller\Adminhtml\Index\PostDataProcessor $dataProcessor, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone)
    {
        $this->___init();
        parent::__construct($context, $dataProcessor, $dateTime, $timezone);
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
