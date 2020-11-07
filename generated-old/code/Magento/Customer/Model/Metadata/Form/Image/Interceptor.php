<?php
namespace Magento\Customer\Model\Metadata\Form\Image;

/**
 * Interceptor class for @see \Magento\Customer\Model\Metadata\Form\Image
 */
class Interceptor extends \Magento\Customer\Model\Metadata\Form\Image implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Psr\Log\LoggerInterface $logger, \Magento\Customer\Api\Data\AttributeMetadataInterface $attribute, \Magento\Framework\Locale\ResolverInterface $localeResolver, $value, $entityTypeCode, $isAjax, \Magento\Framework\Url\EncoderInterface $urlEncoder, \Magento\MediaStorage\Model\File\Validator\NotProtectedExtension $fileValidator, \Magento\Framework\Filesystem $fileSystem, \Magento\Framework\File\UploaderFactory $uploaderFactory, \Magento\Customer\Model\FileProcessorFactory $fileProcessorFactory = null, \Magento\Framework\Api\Data\ImageContentInterfaceFactory $imageContentInterfaceFactory = null)
    {
        $this->___init();
        parent::__construct($localeDate, $logger, $attribute, $localeResolver, $value, $entityTypeCode, $isAjax, $urlEncoder, $fileValidator, $fileSystem, $uploaderFactory, $fileProcessorFactory, $imageContentInterfaceFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function extractValue(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'extractValue');
        if (!$pluginInfo) {
            return parent::extractValue($request);
        } else {
            return $this->___callPlugins('extractValue', func_get_args(), $pluginInfo);
        }
    }
}
