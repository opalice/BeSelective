<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Multiv\Model;

use Magento\Catalog\Api\Data\ProductOptionInterface;
use Magento\Catalog\Model\ProductOptionProcessorInterface;
use Magento\Downloadable\Model\DownloadableOptionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\DataObject;
use Magento\Framework\DataObject\Factory as DataObjectFactory;

class ProductOptionProcessor implements ProductOptionProcessorInterface
{
    /**
     * @var DataObjectFactory
     */
    protected $objectFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    protected $productOptionFactory;
    protected $extensionInterface;


    public function __construct(
        DataObjectFactory $objectFactory,
        DataObjectHelper $dataObjectHelper,
        \Ibapi\Multiv\Model\Extension\ProductOptionFactory $factory
    ) {
        $this->objectFactory = $objectFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->productOptionFactory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToBuyRequest(ProductOptionInterface $productOption)
    {
        /** @var DataObject $request */
        $request = $this->objectFactory->create();

        $info = $this->getRentalDates($productOption);
        if (!empty($info)) {
            $request->addData(['rental_option' => $info]);
        }

        return $request;
    }

    /**
     * Retrieve downloadable links option
     *
     * @param ProductOptionInterface $productOption
     * @return array
     */
    protected function getRentalDates(ProductOptionInterface $productOption)
    {
        file_put_contents('logs/productoptprocess.txt', 'ee');
        
        if ($productOption
            && $productOption->getExtensionAttributes()
            && $productOption->getExtensionAttributes()->getRentalOption()
        ) {
            return $productOption->getExtensionAttributes()
                ->getRentalOption()
                ->getRentalDates();
        }
        return "";
    }

    /**
     * {@inheritdoc}
     */
    public function convertToProductOption(DataObject $request)
    {
        $poption = $this->productOptionFactory->create();



        $extrainfo = $request->getRentalOption();

        file_put_contents('logs/productoptionprocessor.txt', $extrainfo);

        $poption->setRentalDates($extrainfo);

        if (!empty($extrainfo)) {

        	return ['rental_option' => $poption];
        }

        return [];
    }
}
