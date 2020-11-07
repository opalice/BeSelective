<?php
namespace Ibapi\Multiv\Controller\Index\Form;

/**
 * Interceptor class for @see \Ibapi\Multiv\Controller\Index\Form
 */
class Interceptor extends \Ibapi\Multiv\Controller\Index\Form implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\ResultFactory $resultPageFactory, \Magento\Catalog\Api\CategoryLinkManagementInterface $catmgmt, \Ibapi\Multiv\Helper\Data $helper, \Magento\Catalog\Api\ProductRepositoryInterface $repo, \Magento\Catalog\Model\ProductFactory $factory, \Ibapi\Multiv\Model\ImageUploader $uploader)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $catmgmt, $helper, $repo, $factory, $uploader);
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
