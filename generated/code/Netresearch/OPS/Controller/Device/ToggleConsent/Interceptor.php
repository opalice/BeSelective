<?php
namespace Netresearch\OPS\Controller\Device\ToggleConsent;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Device\ToggleConsent
 */
class Interceptor extends \Netresearch\OPS\Controller\Device\ToggleConsent implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Json\EncoderInterface $jsonEncoder, \Magento\Customer\Model\Session $customerSession, \Netresearch\OPS\Model\Config $oPSConfig)
    {
        $this->___init();
        parent::__construct($context, $jsonEncoder, $customerSession, $oPSConfig);
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
