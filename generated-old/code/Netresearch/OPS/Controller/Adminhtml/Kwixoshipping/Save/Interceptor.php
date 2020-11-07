<?php
namespace Netresearch\OPS\Controller\Adminhtml\Kwixoshipping\Save;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Adminhtml\Kwixoshipping\Save
 */
class Interceptor extends \Netresearch\OPS\Controller\Adminhtml\Kwixoshipping\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Backend\Model\SessionFactory $backendSessionFactory, \Magento\Shipping\Model\Config $shippingConfig, \Netresearch\OPS\Model\Validator\Kwixo\Shipping\SettingFactory $oPSValidatorKwixoShippingSettingFactory, \Netresearch\OPS\Model\Kwixo\Shipping\SettingFactory $oPSKwixoShippingSettingFactory, \Magento\Backend\Model\Auth\Session $backendAuthSession, \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->___init();
        parent::__construct($context, $backendSessionFactory, $shippingConfig, $oPSValidatorKwixoShippingSettingFactory, $oPSKwixoShippingSettingFactory, $backendAuthSession, $pageFactory);
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
