<?php
namespace Fooman\PdfCustomiser\Controller\Adminhtml\Invoice\Pdfinvoices;

/**
 * Interceptor class for @see \Fooman\PdfCustomiser\Controller\Adminhtml\Invoice\Pdfinvoices
 */
class Interceptor extends \Fooman\PdfCustomiser\Controller\Adminhtml\Invoice\Pdfinvoices implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Fooman\PdfCore\Model\PdfFileHandling $pdfFileHandling, \Fooman\PdfCore\Model\PdfRenderer $pdfRenderer, \Fooman\PdfCustomiser\Block\InvoiceFactory $invoiceDocumentFactory, \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $invoiceCollectionFactory)
    {
        $this->___init();
        parent::__construct($context, $filter, $pdfFileHandling, $pdfRenderer, $invoiceDocumentFactory, $invoiceCollectionFactory);
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
