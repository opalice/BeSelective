<?php
namespace Ibapi\Multiv\Block\Date;

/**
 * Interceptor class for @see \Ibapi\Multiv\Block\Date
 */
class Interceptor extends \Ibapi\Multiv\Block\Date implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Stdlib\ArrayUtils $arrayUtils, \Ibapi\Multiv\Helper\Data $helper, \Magento\Checkout\Model\Cart $cart, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $arrayUtils, $helper, $cart, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        if (!$pluginInfo) {
            return parent::getImage($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('getImage', func_get_args(), $pluginInfo);
        }
    }
}
