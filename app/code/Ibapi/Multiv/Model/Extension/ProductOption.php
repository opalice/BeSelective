<?php
namespace Ibapi\Multiv\Model\Extension;
use Magento\Framework\Model\AbstractExtensibleModel;
use Ibapi\Multiv\Api\Data\OptionInterface;
class ProductOption extends AbstractExtensibleModel implements OptionInterface{

	public function getRentalDates(){


		$s= $this->getData(self::OPTION_KEY);

		return $s;
	}
	public function setRentalDates($param) {



		$this->setData(self::OPTION_KEY,$param);




	}


	/**
	 * Retrieve existing extension attributes object or create a new one.
	 *
	 * @return 			\Ibapi\Multiv\Api\Data\OptionInterface $extensionAttributes
	 */
	public function getExtensionAttributes()
	{
		return $this->_getExtensionAttributes();
	}

	/**
	 * Set an extension attributes object.
	 *
	 * @param 			\Ibapi\Multiv\Api\Data\OptionInterface $extensionAttributes
	 * @return $this
	 */
	public function setExtensionAttributes(
			\Ibapi\Multiv\Api\Data\OptionInterface $extensionAttributes
	) {
		return $this->_setExtensionAttributes($extensionAttributes);
	}

}