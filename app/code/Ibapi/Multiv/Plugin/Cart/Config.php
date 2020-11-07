<?php
/**
 * Plugin for cart product configuration
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ibapi\Multiv\Plugin\Cart;

use Ibapi\Multiv\Model\Type\AccessoryType;
use Ibapi\Multiv\Model\Type\ClothType;

class Config
{
	/**
	 * Decide whether product has been configured for cart or not
	 *
	 * @param \Magento\Catalog\Model\Product\CartConfiguration $subject
	 * @param callable $proceed
	 * @param \Magento\Catalog\Model\Product $product
	 * @param array $config
	 *
	 * @return bool
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function aroundIsProductConfigured(
			\Magento\Catalog\Model\Product\CartConfiguration $subject,
			\Closure $proceed,
			\Magento\Catalog\Model\Product $product,
			$config
	) {
		if ($product->getTypeId() == AccessoryType::TYPE_CODE||$product->getTypeId() == ClothType::TYPE_CODE) {


		    
		}
		return $proceed($product, $config);
	}
}