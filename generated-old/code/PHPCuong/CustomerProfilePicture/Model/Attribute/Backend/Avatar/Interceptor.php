<?php
namespace PHPCuong\CustomerProfilePicture\Model\Attribute\Backend\Avatar;

/**
 * Interceptor class for @see \PHPCuong\CustomerProfilePicture\Model\Attribute\Backend\Avatar
 */
class Interceptor extends \PHPCuong\CustomerProfilePicture\Model\Attribute\Backend\Avatar implements \Magento\Framework\Interception\InterceptorInterface
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
