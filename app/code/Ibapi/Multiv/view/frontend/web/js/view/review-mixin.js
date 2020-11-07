define(
    [
        'ko',
        
        'Magento_Customer/js/customer-data',
    ], function (ko,customerData) {
        'use strict';

        
        /*
         * 
         
                    	
                    	$('#removeimg').on('click',function(){
                      	   
                          	 ///                                    	images.addImage(result.r);
                            		$('#review-pic').attr('src',$(this).data('url'));
                            			$(this).hide();
                            			
                            			$('#myreview-img').val('x');            	   
                             });
                           
                           $("#a-write-review").on('click',function(e){
                   			e.preventDefault();


                   	$('#review-add').show();
                   	
                   	});
                    	
        var frx=  $('#myfile').fileupload({
            url: $('#myfile').data('href'),
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            maxFileSize: 2000000, // 2 MB
            sequentialUploads: true,
            dataType: 'json',
            formData: {
                'form_key': window.FORM_KEY
            },
            
            
//            acceptFileTypes: /(\.|\/)(jpg)$/i,
//                 maxFileSize: 2000000, // 10 MB
            add: function (e, data) {
         	   console.log(e,data)
         	   if(data.files[0].type!='image/jpg'||data.files[0].size>2000000){
         		   console.log('invalid file');
         		   return;
         		   
         	   }
         	   
                var jqXHR = data.submit()
                    .success(function (result, textStatus, jqXHR) {
                    		console.log('resultx',result)
                    	if(!result.r||result.r.error)
                    	{
                    		return;
                    	}
    ///                                    	images.addImage(result.r);
                    		$('#review-pic').attr('src',result.r.url);
                    		$('#removeimg').show();
                    		$('#myreview-img').val(result.r.file);
                    		
                    	
                    })
                    .error(function (jqXHR, textStatus, errorThrown) {})
                    .complete(function (result, textStatus, jqXHR) {
                    	
                    	
                    });
            }

        });
        */
        

        
        var mixin = {

            initialize: function () {
                this._super();
                //this.review
                

///               	$('#myfile').fileupload();
               	
               	
                
            ///    $('form[data-role="product-review-form"]').hide();
                ///console.log('customerreview',customerData.get('customer');
         ///       	customerData.reload('customer').done(function(x){
          ////     if(x.customer.firstname) 		
///                $('form[data-role="product-review-form"]').show();


               
               
               
////                	});///cust9merdata
                return this;
            }
        };

        return function (target) {
            return target.extend(mixin);
        };
    }
);