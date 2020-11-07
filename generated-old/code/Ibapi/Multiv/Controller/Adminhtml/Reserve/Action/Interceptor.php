<?php
namespace Ibapi\Multiv\Controller\Adminhtml\Reserve\Action;

/**
 * Interceptor class for @see \Ibapi\Multiv\Controller\Adminhtml\Reserve\Action
 */
class Interceptor extends \Ibapi\Multiv\Controller\Adminhtml\Reserve\Action implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\ResultFactory $resultFactory, \Magento\Sales\Model\OrderRepository $orderRepo, \Ibapi\Multiv\Model\ReserveFactory $reserveFactory, \Magento\Sales\Model\Service\InvoiceService $invoiceService, \Magento\Sales\Api\RefundInvoiceInterface $refunder)
    {
        $this->___init();
        parent::__construct($context, $resultFactory, $orderRepo, $reserveFactory, $invoiceService, $refunder);
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
