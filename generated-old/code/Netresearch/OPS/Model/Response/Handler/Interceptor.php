<?php
namespace Netresearch\OPS\Model\Response\Handler;

/**
 * Interceptor class for @see \Netresearch\OPS\Model\Response\Handler
 */
class Interceptor extends \Netresearch\OPS\Model\Response\Handler implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->___init();
        parent::__construct($objectManager);
    }

    /**
     * {@inheritdoc}
     */
    public function processResponse($responseArray, \Netresearch\OPS\Model\Payment\PaymentAbstract $paymentMethod, $shouldRegisterFeedback = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'processResponse');
        if (!$pluginInfo) {
            return parent::processResponse($responseArray, $paymentMethod, $shouldRegisterFeedback);
        } else {
            return $this->___callPlugins('processResponse', func_get_args(), $pluginInfo);
        }
    }
}
