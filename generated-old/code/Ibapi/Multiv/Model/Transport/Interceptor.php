<?php
namespace Ibapi\Multiv\Model\Transport;

/**
 * Interceptor class for @see \Ibapi\Multiv\Model\Transport
 */
class Interceptor extends \Ibapi\Multiv\Model\Transport implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Mail\MessageInterface $message)
    {
        $this->___init();
        parent::__construct($message);
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'sendMessage');
        if (!$pluginInfo) {
            return parent::sendMessage();
        } else {
            return $this->___callPlugins('sendMessage', func_get_args(), $pluginInfo);
        }
    }
}
