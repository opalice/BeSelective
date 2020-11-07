<?php
namespace  Ibapi\Multiv\Model\CustomerData;
use Ibapi\Multiv\Model\Type\ClothType;
use Ibapi\Multiv\Model\Type\AccessoryType;
use Magento\Framework\Mview\Test\Unit\View\SubscriptionTest;
use Ibapi\Multiv\Model\Type\SubType;

class Cart extends  \Magento\Checkout\CustomerData\Cart
{

    public function getSectionData()
    {
        $totals = $this->getQuote()->getTotals();
        $subtotalAmount = $totals['subtotal']->getValue();
        return [
            'summary_count' => $this->getSummaryCount(),
            'subtotalAmount' => $subtotalAmount,
            'subtotal' => isset($totals['subtotal'])
            ? $this->checkoutHelper->formatPrice($subtotalAmount)
            : 0,
            'possible_onepage_checkout' => $this->isPossibleOnepageCheckout(),
            'items' => $this->getRecentItems(),
///            'times'=>['sd','ed','st','et'],
            'extra_actions' => $this->layout->createBlock(\Magento\Catalog\Block\ShortcutButtons::class)->toHtml(),
            'isGuestCheckoutAllowed' => $this->isGuestCheckoutAllowed(),
            'website_id' => $this->getQuote()->getStore()->getWebsiteId()
        ];
    }

    protected function getRecentItems()
    {
        $items = [];
        if (!$this->getSummaryCount()) {

            return $items;
        }

        foreach (array_reverse($this->getAllQuoteItems()) as $item) {
            /* @var $item \Magento\Quote\Model\Quote\Item */
            $bit=false;
            if (!$item->getProduct()->isVisibleInSiteVisibility()) {
                $product =  $item->getOptionByCode('product_type') !== null
                ? $item->getOptionByCode('product_type')->getProduct()
                : $item->getProduct();

                $products = $this->catalogUrl->getRewriteByProductStore([$product->getId() => $item->getStoreId()]);
                if (!isset($products[$product->getId()])) {
                    if($product->getTypeId()==SubType::TYPE_CODE){

                    }

                    if(strpos($product->getSku(),'res-')===0){
                        $bit=true;
                        foreach ($item->getChildren() as $chi){
                            

                            if(in_array($chi->getProduct()->getTypeId(), [ClothType::TYPE_CODE,AccessoryType::TYPE_CODE])){
                                $products = $this->catalogUrl->getRewriteByProductStore([$chi->getProduct()->getId() => $item->getStoreId()]);

                                $urlDataObject = new \Magento\Framework\DataObject($products[$chi->getProduct()->getId()]);

                                $item->getProduct()->setUrlDataObject($urlDataObject);
                            }
                        }

                    }else{

                    continue;

                    }

                }else{
                $urlDataObject = new \Magento\Framework\DataObject($products[$product->getId()]);
                $item->getProduct()->setUrlDataObject($urlDataObject);
                }
            }

            $item=$this->itemPoolInterface->getItemData($item);
            if($bit){
                $item['options']=[];
            }

            $items[] = $item;
        }
        return $items;
    }




}
