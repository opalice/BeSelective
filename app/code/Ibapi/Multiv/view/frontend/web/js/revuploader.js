define([
    'jquery',
    'underscore',
    "jquery/ui",
    "jquery/file-uploader",
    'domReady!'
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
                   		console.log('revopts',options);
                    	$('#myfile').fileupload({
                            url: $('#myfile').data('href'),
                            sequentialUploads: true,
                            add: function (e, data) {
                                var jqXHR = data.submit()
                                    .success(function (result, textStatus, jqXHR) {/* ... */
                                    		console.log('resultx',result)
                                    	if(!result.r||result.r.error)
                                    	{
                                    		return;
                                    	}
                                    	$('#myreview-img').val(result.r.file)
                                    	
///                                    	images.addImage(result.r);
                                    	
                                    		
                                    		
                                    	
                                    })
                                    .error(function (jqXHR, textStatus, errorThrown) {/* ... */})
                                    .complete(function (result, textStatus, jqXHR) {/* ... */
                                    	
                                    	console.log('complete');
                                    	
                                    });
                            }

                        });
                       
                    	
                    
                }
                
                return {
                    updateContent: module.updateContent.bind(module),

                };
            }	
    		
    };
    
    return module.init;
    
});