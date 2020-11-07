<?php
namespace PHPCuong\CustomerProfilePicture\Controller\Avatar\View;

/**
 * Interceptor class for @see \PHPCuong\CustomerProfilePicture\Controller\Avatar\View
 */
class Interceptor extends \PHPCuong\CustomerProfilePicture\Controller\Avatar\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\Url\DecoderInterface $urlDecoder, \Magento\Framework\App\Response\Http\FileFactory $fileFactory)
    {
        $this->___init();
        parent::__construct($context, $resultRawFactory, $urlDecoder, $fileFactory);
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
