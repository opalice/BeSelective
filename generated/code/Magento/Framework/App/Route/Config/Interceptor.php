<?php
namespace Magento\Framework\App\Route\Config;

/**
 * Interceptor class for @see \Magento\Framework\App\Route\Config
 */
class Interceptor extends \Magento\Framework\App\Route\Config implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Route\Config\Reader $reader, \Magento\Framework\Config\CacheInterface $cache, \Magento\Framework\Config\ScopeInterface $configScope, \Magento\Framework\App\AreaList $areaList, $cacheId = 'RoutesConfig')
    {
        $this->___init();
        parent::__construct($reader, $cache, $configScope, $areaList, $cacheId);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteFrontName($routeId, $scope = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRouteFrontName');
        if (!$pluginInfo) {
            return parent::getRouteFrontName($routeId, $scope);
        } else {
            return $this->___callPlugins('getRouteFrontName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteByFrontName($frontName, $scope = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRouteByFrontName');
        if (!$pluginInfo) {
            return parent::getRouteByFrontName($frontName, $scope);
        } else {
            return $this->___callPlugins('getRouteByFrontName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getModulesByFrontName($frontName, $scope = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getModulesByFrontName');
        if (!$pluginInfo) {
            return parent::getModulesByFrontName($frontName, $scope);
        } else {
            return $this->___callPlugins('getModulesByFrontName', func_get_args(), $pluginInfo);
        }
    }
}
