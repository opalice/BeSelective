<?php
/**
 * \Netresearch\OPS\Helper\MobileDetect
 *
 * @package
 * @copyright 2016 Netresearch
 * @author    Sebastian Ertner <sebastian.ertner@netresearch.de>
 * @license   OSL 3.0
 */
namespace Netresearch\OPS\Helper;

use Netresearch\OPS\Test\Unit\Helper\MobileDetectTest;

class MobileDetect extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Computer device type string
     */
    const DEVICE_TYPE_COMPUTER = 'Computer';
    /**
     * mobile device type string
     */
    const DEVICE_TYPE_MOBILE = 'Mobile';
    /**
     * tablet device type string
     */
    const DEVICE_TYPE_TABLET = 'Tablet';

    /**
     * @var Mobile_Detect
     */
    private $detector = null;
    private $header;
    private $mobileg;

    /**
     * create class instance
     *
     * Netresearch_OPS_Helper_MobileDetect constructor.
     *
     * @internal param null $headers
     * @internal param null $userAgent
     * @internal param null $detector
     */
    public function __construct(   \Magento\Framework\HTTP\Header $httpHeader
       )
    {
 ///       $this->mobileg=$mobileAgent;
       // $this->header=$httpHeader;
        
///        $this->detector = new Mobile_Detect();
    }

    public function setDetector($detector)
    {
        
        $this->detector = $detector;
    }

    /**
     * determine device type with help of mobile_detect lib and return it
     *
     * @return string
     */
    public function getDeviceType()
    {
        return self::DEVICE_TYPE_COMPUTER;        
        $userAgent = $this->header->getHttpUserAgent();
      return       $this->mobileg->match($userAgent, $_SERVER)?self::DEVICE_TYPE_MOBILE:self::DEVICE_TYPE_COMPUTER;
    }
}
