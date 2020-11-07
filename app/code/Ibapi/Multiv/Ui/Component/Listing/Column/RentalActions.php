<?php
namespace Ibapi\Multiv\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Ibapi\Multiv\Block\Grid\Render\UrlBuilder;
use Magento\Framework\UrlInterface;

class RentalActions extends Column
{
    /** Url path */
    /** @var UrlBuilder */
    protected $actionUrlBuilder;

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlBuilder $actionUrlBuilder
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlBuilder $actionUrlBuilder,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->actionUrlBuilder = $actionUrlBuilder;
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
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['id'])) {
                    $item[$name]['ship'] = [
                        'href' => $this->urlBuilder->getUrl('multiv/reserve/action', ['id' => $item['id'],'item_id'=>$item['item_id'],'order_id'=>$item['order'],'a'=>'s']),
                        'label' => __('Ship')
                    ];
                    $item[$name]['received'] = [
                        'href' => $this->urlBuilder->getUrl('multiv/reserve/action', ['id' => $item['id'],'item_id'=>$item['item_id'],'order_id'=>$item['order'],'a'=>'r']),
                        'label' => __('Received'),
                        'confirm' => [
                            'title' => __('Received'),
                            'message' => __('Do you want to mark received ?')
                        ]
                    ];
                    
                    $item[$name]['refund'] = [
                        'href' => $this->urlBuilder->getUrl('multiv/reserve/action', ['id' => $item['id'],'item_id'=>$item['item_id'],'order_id'=>$item['order_id'],'a'=>'x']),
                        'label' => __('Full Refund'),
                        'confirm' => [
                            'title' => __('Refund'),
                            'message' => __('Full Refund ?')
                        ]
                    ];
                    $item[$name]['prefund'] = [
                        'href' => $this->urlBuilder->getUrl('multiv/reserve/action', ['id' => $item['id'],'item_id'=>$item['item_id'],'order_id'=>$item['order_id'],'a'=>'p']),
                        'label' => __('Partial Refund'),
                        'confirm' => [
                            'title' => __('Refund'),
                            'message' => __('Partial Refund ?')
                        ]
                    ];
                    
                    
                    
                    $item[$name]['gur'] = [
                        'href' => $this->urlBuilder->getUrl('multiv/reserve/action', ['id' => $item['id'],'item_id'=>$item['item_id'],'order_id'=>$item['order_id'],'a'=>'v']),
                        'label' => __('Deposit Take'),
                        'confirm' => [
                            'title' => __('Deposit'),
                            'message' => __('Void Deposit?')
                        ]
                    ];
                    
                }
            }
        }
        return $dataSource;
    }
}