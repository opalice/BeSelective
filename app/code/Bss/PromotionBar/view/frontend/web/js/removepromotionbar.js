/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_PromotionBar
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

define([
        'jquery'
    ],
    function($) {
        return function(config){
            $(document).ready(function(){
                $(window).on('resize', function () {
                    var columnWidth = $(".column").width();
                    $(".page-bottom .content").css("width",(columnWidth+"px"));
                });
                var  selector = ".promotionbar_position_"+ config.page +config.position;
                var close = "span#promotion_bar_close_"+ config.page +config.position;

                if (config.timeOut != 0) {
                    setTimeout(function() {
                        $(selector).fadeOut();
                    }, config.timeOut);
                }

                $(document).on('click', close, function(event){
                    $(selector).fadeOut();
                });
            });
        }
    }
);
