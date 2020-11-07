<?php
namespace Ibapi\Multiv\Controller\Index\Image;

/**
 * Interceptor class for @see \Ibapi\Multiv\Controller\Index\Image
 */
class Interceptor extends \Ibapi\Multiv\Controller\Index\Image implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\ResultFactory $resultPageFactory, \Ibapi\Multiv\Helper\Data $data, \Ibapi\Multiv\Model\ImageUploader $uploader)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $data, $uploader);
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
