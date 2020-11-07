<?php
namespace Ibapi\Multiv\Controller\Adminhtml\Reserve\Action;

/**
 * Interceptor class for @see \Ibapi\Multiv\Controller\Adminhtml\Reserve\Action
 */
class Interceptor extends \Ibapi\Multiv\Controller\Adminhtml\Reserve\Action implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\ResultFactory $resultFactory, \Magento\Sales\Model\OrderRepository $orderRepo, \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder, \Ibapi\Multiv\Model\ReserveFactory $reserveFactory, \Magento\Sales\Model\Service\InvoiceService $invoiceService, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Sales\Api\RefundInvoiceInterface $refunder, \Magento\Sales\Api\InvoiceRepositoryInterface $invRepo, \Magento\Sales\Model\Order\ItemRepository $itrepo, \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender $orderSender, \Magento\Framework\Registry $registry, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Ibapi\Multiv\Helper\Data $helper, \Magento\Directory\Model\PriceCurrency $priceCurrency, \Magento\Catalog\Api\ProductRepositoryInterface $pi)
    {
        $this->___init();
        parent::__construct($context, $resultFactory, $orderRepo, $searchCriteriaBuilder, $reserveFactory, $invoiceService, $customerRepositoryInterface, $scopeConfig, $refunder, $invRepo, $itrepo, $orderSender, $registry, $transportBuilder, $inlineTranslation, $helper, $priceCurrency, $pi);
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
