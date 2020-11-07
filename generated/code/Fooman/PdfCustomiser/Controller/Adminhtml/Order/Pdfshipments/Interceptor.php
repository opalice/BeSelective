<?php
namespace Fooman\PdfCustomiser\Controller\Adminhtml\Order\Pdfshipments;

/**
 * Interceptor class for @see \Fooman\PdfCustomiser\Controller\Adminhtml\Order\Pdfshipments
 */
class Interceptor extends \Fooman\PdfCustomiser\Controller\Adminhtml\Order\Pdfshipments implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Fooman\PdfCore\Model\PdfFileHandling $pdfFileHandling, \Fooman\PdfCore\Model\PdfRenderer $pdfRenderer, \Fooman\PdfCustomiser\Block\ShipmentFactory $shipmentDocumentFactory, \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory, \Fooman\PdfCustomiser\Block\OrderShipmentFactory $orderShipmentDocumentFactory, \Fooman\PdfCustomiser\Model\ControllerConfig $config)
    {
        $this->___init();
        parent::__construct($context, $filter, $pdfFileHandling, $pdfRenderer, $shipmentDocumentFactory, $orderCollectionFactory, $orderShipmentDocumentFactory, $config);
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
