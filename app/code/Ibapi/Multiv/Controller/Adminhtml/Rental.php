<?php
/**
* Copyright © 2015 PlazaThemes.com. All rights reserved.

* @author PlazaThemes Team <contact@plazathemes.com>
*/

namespace Ibapi\Multiv\Controller\Adminhtml;

/**
 * Banner Controller
 */
abstract class Rental extends \Magento\Backend\App\Action {

	/**
	 * Registry object
	 * @var \Magento\Framework\Registry
	 */
	protected $_coreRegistry;
	/**
	 * @var \Ibapi\Multiv\Helper\Data 
	 */
	protected  $helper;
	
	/**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry,\Ibapi\Multiv\Helper\Data $helper)
    {
        $this->_coreRegistry = $coreRegistry;
        $this->helper=$helper;
        parent::__construct($context);
    }

	/**
	 * Check if admin has permissions to visit related pages
	 *
	 * @return bool
	 */
	protected function _isAllowed() {
	    return true;
//		return $this->_authorization->isAllowed('Plazathemes_Bannerslider::bannerslider_banners');
	}
}
