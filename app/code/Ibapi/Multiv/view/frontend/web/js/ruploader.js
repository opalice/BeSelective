define([
    'jquery',
    'underscore',
    "jquery/ui",
    "jquery/file-uploader"
/*
    'jquery/fileUploader/jquery.fileuploader',

    'jquery/fileUploader/jquery.fileupload-ui',
    'jquery/fileUploader/jquery.iframe-transport',
    'jquery/fileUploader/load-image',
    'jquery/fileUploader/canvas-to-blob'
*/
    ], function($, _) {
    "use strict";
    var module={
    		options:false,
    		
    		updateContent: function(){
    			
    		},
    		
            init: function (options) {
            
            	if (!module.options) {
                    module.options = options;
                    $(document).ready(function () {
                    	
                    	
                    	$('#removeimg').on('click',function(e){
                       	   			console.log('remiving  img',options.photo	)
                         	 ///                                    	images.addImage(result.r);
                           		$('#review-pic').attr('src',options.photo);
                           			$(this).hide();
                           			$('#myfile').show();
                           			$('#myreview-img').val('x');            	   
                           			e.preventDefault();
                           			return false;
                            });
                          
                          $("#a-write-review").on('click',function(e){
                  			e.preventDefault();


                  	$('#review-add').show();
                  	
                  	});
                    	
                    	
                    	setTimeout(function(){
                    	
                    		
                    		console.log('uploadopts',options);
                    	$('#myfile').fileupload({
                            url: options.url,
                            sequentialUploads: true,
                            acceptFileTypes: /(\.|\/)(jpg)$/i,
                            maxFileSize: 2000000, // 2 MB
                            add: function (e, data) {
                            	console.log('data',data.files[0]);
                            	if((data.files[0].type!='image/jpg'&&data.files[0].type!='image/jpeg') ||data.files[0].size>2000000){
                          		   console.log('invalid file');
                          		   return;
                          		   
                          	   }
                            	
                                var jqXHR = data.submit()
                                    .success(function (result, textStatus, jqXHR) {/* ... */
                                    		console.log('resultx',result)
                                    	if(!result.r||result.r.error)
                                    	{
                                    		return;
                                    	}
                                    	    $('#myfile').hide();
                                    		$('#review-pic').attr('src',result.r.url+"?"+Math.random());
                                    		$('#removeimg').show();
                                    		$('#myreview-img').val(result.r.file);	
                                    		
                                    	
                                    })
                                    .error(function (jqXHR, textStatus, errorThrown) {/* ... */})
                                    .complete(function (result, textStatus, jqXHR) {/* ... */
                                    	
                                    	console.log('completeupload');
                                    	
                                    });
                            }

                        });
                    	
                    	},4000);
                       
                    	
                    });
                    
                }
                
                return {
                    updateContent: module.updateContent.bind(module),

                };
            }	
    		
    };
    
    return module.init;
    
});