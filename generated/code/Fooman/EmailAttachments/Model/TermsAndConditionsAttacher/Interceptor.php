<?php
namespace Fooman\EmailAttachments\Model\TermsAndConditionsAttacher;

/**
 * Interceptor class for @see \Fooman\EmailAttachments\Model\TermsAndConditionsAttacher
 */
class Interceptor extends \Fooman\EmailAttachments\Model\TermsAndConditionsAttacher implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\CheckoutAgreements\Model\ResourceModel\Agreement\CollectionFactory $termsCollection, \Fooman\EmailAttachments\Model\ContentAttacher $contentAttacher)
    {
        $this->___init();
        parent::__construct($termsCollection, $contentAttacher);
    }

    /**
     * {@inheritdoc}
     */
    public function attachForStore($storeId, \Fooman\EmailAttachments\Model\Api\AttachmentContainerInterface $attachmentContainer)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'attachForStore');
        if (!$pluginInfo) {
            return parent::attachForStore($storeId, $attachmentContainer);
        } else {
            return $this->___callPlugins('attachForStore', func_get_args(), $pluginInfo);
        }
    }
}
