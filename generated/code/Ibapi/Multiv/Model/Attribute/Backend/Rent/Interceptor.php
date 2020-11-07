<?php
namespace Ibapi\Multiv\Model\Attribute\Backend\Rent;

/**
 * Interceptor class for @see \Ibapi\Multiv\Model\Attribute\Backend\Rent
 */
class Interceptor extends \Ibapi\Multiv\Model\Attribute\Backend\Rent implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($object);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
