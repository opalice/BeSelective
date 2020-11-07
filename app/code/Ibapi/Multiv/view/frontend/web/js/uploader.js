define([
    'jquery',
    'underscore',
    'Ibapi_Multiv/js/imagelist',
    "jquery/ui",
    "jquery/file-uploader"
/*
    'jquery/fileUploader/jquery.fileuploader',

    'jquery/fileUploader/jquery.fileupload-ui',
    'jquery/fileUploader/jquery.iframe-transport',
    'jquery/fileUploader/load-image',
    'jquery/fileUploader/canvas-to-blob'
*/
    ], function($, _,images) {
    "use strict";
    var module={
    		options:false,
    		
    		updateContent: function(){
    			
    		},
    		
            init: function (options) {
            
            	if (!module.options) {
                    module.options = options;
                    $(document).ready(function () {
                    		console.log('options',options);
                    	$('#myfile').fileupload({
                            url: options.url,
                            sequentialUploads: true,
                            add: function (e, data) {
                                var jqXHR = data.submit()
                                    .success(function (result, textStatus, jqXHR) {/* ... */
                                    		console.log('resultx',result)
                                    	if(!result.r||result.r.error)
                                    	{
                                    		return;
                                    	}
                                    	images.addImage(result.r);
                                    	
                                    		
                                    		
                                    	
                                    })
                                    .error(function (jqXHR, textStatus, errorThrown) {/* ... */})
                                    .complete(function (result, textStatus, jqXHR) {/* ... */
                                    	
                                    	console.log('complete');
                                    	
                                    });
                            }

                        });
                       
                    	
                    });
                    
                }
                
                return {
                    updateContent: module.updateContent.bind(module),

                };
            }	
    		
    };
    
    return module.init;
    
});