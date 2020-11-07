<?php
namespace Ibapi\Multiv\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Refund extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    
    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
        ) {
            $this->urlBuilder = $urlBuilder;
            parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$fieldName . '_html'] = "Actions";
                $item[$fieldName . '_title'] = __('Enter Order and or payment status');
                $item[$fieldName . '_submitlabel'] = __('Submit');
                $item[$fieldName . '_cancellabel'] = __('Reset');
                $item[$fieldName . '_id'] = $item['id'];
                $url= $this->urlBuilder->getUrl('sales/order/view/order_id/'.$item['order']);  //$this->getContext()->getUrl('adminhtml/sales/order/view',['order_id'=>$item['order']]);
                $item[$fieldName.'_url']=$url;
                $item[$fieldName . '_order'] = $item['order'];
                
                $item[$fieldName . '_formaction'] = $this->urlBuilder->getUrl('multiv/reserve/action');
            }
        }
        
        return $dataSource;
    }
}