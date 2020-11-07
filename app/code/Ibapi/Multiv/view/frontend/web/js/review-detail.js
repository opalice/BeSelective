define([
    'jquery',
    'underscore',
    'mage/translate',
    ], function($, _) {
    "use strict";
    var module={
    		options:false,
    		
    		updateContent: function(){
    			
    		},
    		
            init: function (options) {
///            	this._super();
            	console.log('reviewopts',options);
            	
            	if (!module.options) {
                    
            		var dt=options;
            		
            		$(document).ready(function(){
            		
            		var fr=$('form[data-role="product-review-form"]')                

            		if(dt.id){
            			$('#myfile').hide();
            		}
                   	
                    fr.find('#summary_field').val(dt.title)
                    fr.find('#review_field').val(dt.detail)
                    	if(dt.url)
                    $('#review-pic').attr('src',dt.url+"?"+Math.random());
                    
                    $.each(dt.ratings ,function(i,v){
                    	var f=parseFloat(v[2])*5/100;
                    	if(isNaN(f)) f=0;
                    	var i=0;
                    	while(i++<f){
                    	$('#'+v[1]+'_'+i).trigger('click');
                    	}
                    	
                    });
            		
            		
            		});//docread
                    	
                       
                    module.options=options;	
                    
                }
                
            }	
    		
    };
    
    return module.init;
    
});