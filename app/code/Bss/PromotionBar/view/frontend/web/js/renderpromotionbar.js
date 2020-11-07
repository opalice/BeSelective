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
        		var width = $(window).width() * (95/100);
		        $("#promotionbar_bottomposition").css("width",(width+"px"));

		        $( window ).resize(function() {
		            var width = $(window).width() * (95/100);
		            $("#promotionbar_bottomposition").css("width",(width+"px"));
		        });
		        
		        var page = config.page;
		        var storeViewID = config.storeViewId;
		        var productId = config.productId;
		        var categoryId = config.categoryId;
		        $.ajax({
					url: config.updateUrl,
					type: 'POST',
					data: {
						pageDisplay: page,
						storeViewID: storeViewID,
						categoryId: categoryId,
						productId: productId
					},
					success : function(res) {

						for (var i = 0; i < result.length; i++) {
							
							$(result[i].targetDiv).append(result[i].html);
						}						
						$(".promotionbar_wrapper").trigger('contentUpdated');
					}
		        });

        	})
        }
    }
);