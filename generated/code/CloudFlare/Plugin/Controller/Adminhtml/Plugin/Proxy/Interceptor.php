<?php
namespace CloudFlare\Plugin\Controller\Adminhtml\Plugin\Proxy;

/**
 * Interceptor class for @see \CloudFlare\Plugin\Controller\Adminhtml\Plugin\Proxy
 */
class Interceptor extends \CloudFlare\Plugin\Controller\Adminhtml\Plugin\Proxy implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \CloudFlare\Plugin\Backend\DataStore $dataStore, \CloudFlare\Plugin\Backend\MagentoIntegration $integrationContext, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Psr\Log\LoggerInterface $logger, \CloudFlare\Plugin\Backend\MagentoAPI $magentoAPI)
    {
        $this->___init();
        parent::__construct($context, $dataStore, $integrationContext, $resultJsonFactory, $logger, $magentoAPI);
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
