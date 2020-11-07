<?php
namespace Magento\Catalog\Model\Indexer\Product\Flat\System\Config\Mode;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Indexer\Product\Flat\System\Config\Mode
 */
class Interceptor extends \Magento\Catalog\Model\Indexer\Product\Flat\System\Config\Mode implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\App\Config\ScopeConfigInterface $config, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList, \Magento\Catalog\Model\Indexer\Product\Flat\Processor $productFlatIndexerProcessor, \Magento\Indexer\Model\Indexer\State $indexerState, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $config, $cacheTypeList, $productFlatIndexerProcessor, $indexerState, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterSave');
        if (!$pluginInfo) {
            return parent::afterSave();
        } else {
            return $this->___callPlugins('afterSave', func_get_args(), $pluginInfo);
        }
    }
}
