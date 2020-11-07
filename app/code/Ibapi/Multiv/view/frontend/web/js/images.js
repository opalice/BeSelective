define(['jquery','ko','uiComponent','Ibapi_Multiv/js/imagelist'], function($,ko,Component,images) {
		var self;
        return Component.extend({
            options:{},
        	getlist: function(){ 
        		
        			
        		return images.images;},

        	remove:function($d){
        		$.ajax(
        		{
        			url:self.options.url,
        			data:$d,
        			dataType:'json',
        			type:'POST'
        		}		
        		).done(function(s){
                		images.removeImage($d);

        			
        		}).always(function(){
        		});
        		
        	}
        	,
        	initialize: function (opts) {
                this._super();
                self=this;
                this.options=opts.data;
                $.each(opts.data.images,function(i,img){
                	console.log('image',img);
                	images.addImage(img)
                })
            }
        });
});