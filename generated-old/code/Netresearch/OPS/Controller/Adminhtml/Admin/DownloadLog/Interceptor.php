<?php
namespace Netresearch\OPS\Controller\Adminhtml\Admin\DownloadLog;

/**
 * Interceptor class for @see \Netresearch\OPS\Controller\Adminhtml\Admin\DownloadLog
 */
class Interceptor extends \Netresearch\OPS\Controller\Adminhtml\Admin\DownloadLog implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Netresearch\OPS\Helper\Data $oPSHelper, \Netresearch\OPS\Model\File\DownloadFactory $oPSFileDownloadFactory, \Magento\Framework\App\Response\Http\FileFactory $fileFactory)
    {
        $this->___init();
        parent::__construct($context, $oPSHelper, $oPSFileDownloadFactory, $fileFactory);
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
