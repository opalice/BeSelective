define(['jquery','ko','uiComponent','Ibapi_Multiv/js/imagelist',
	
	"jquery/jstree/jquery.jstree",
	'jquery/ui','accordion'
	
	
	], function($,ko,Component,images,Jstree,ui) {
		var categoryTree;
		var option;
        function addParams(params){
        	
        	var queryParameters = {}, queryString = location.search.substring(1),
            re = /([^&=]+)=([^&]*)/g, m;

        // Creates a map with the query string parameters
        while (m = re.exec(queryString)) {
            queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
        }

        // Add new parameters or update existing ones
        $.each(params,function(i,v){
            queryParameters[v.name] = v.value;
        	
        });

        location.search = $.param(queryParameters); // Causes page to reload

        }
        	
        function inittree(){
        	
        		console.log('cattree',categoryTree);
        		
         	var jst=$("#cattree").jstree({
         		  "themes" : {
         			             "theme" : "default",
         			 
         			             "dots" : true,
         			 
         			             "icons" : true
         			 
         			         },

         		 'core' : {
         			data:categoryTree,

         			'check_callback':true,
         			'multiple':false
         			///'initially_open':['1','2']
         		 },
         		"types" : {
         		      "types": {
         		        "disabled" : { // Defining new type 'disabled'
         		          "check_node" : false,
         		          "uncheck_node" : false
         		        },
         		        "default" : { // Override default functionality
         		          "check_node" : function (node) {
         		        	  console.log('checknode');
         		        	  
         		          },
         		          "uncheck_node" : function (node) {
         		            $(node).children('ul').children('li').children('a').children('.jstree-checkbox').click();
         		            return true;
         		          }
         		        }
         		      }
         		},

         		 "ui":{
         			'disable_selecting_children':false,
         			'initially_select':['1','2']
         		 },
         	    "plugins" : [
         	        "themes","json_data", "ui","checkbox",'state','types','search'
         	    ],
         	   "checkbox":{ "tie_selection": false ,'two_state':true},
    	    	
         	   

         	    "json_data" : {
         	    data:	categoryTree
         	    }



         	}).bind('select_node.jstree',
         			function(e, data){

         	
         	}
         	).bind('open_node.jstree',
         			function(e, data){

         	}).bind('check_node.jstree',
         			function(e, data){
         		 var dt= $(e.target).data(); // read it off the DOM
         		 	console.log('check',dt)
         			
         		}).bind('uncheck_node.jstree',
         			function(e, data){
         		 
         		 console.log('unbind',data)
         		 
         	 }
         	     );
        	
         	console.log('theme',$.jstree._themes);
        }
		
		return Component.extend({
        	stores:ko.observableArray([]),
        	price:ko.observable(),
        	rent4:ko.observable(),
        	rent8:ko.observable(),
        	size:ko.observable(1),
        	type:ko.observable('cloth'),
        	status:ko.observable(true),
        	color:ko.observable(''),
        	coloropts:ko.observableArray([]),
        	lenopts:ko.observableArray([]),
        	brandopts:ko.observableArray([]),
        	sizeopts:ko.observableArray([]),
        	brand:ko.observable(''),
        	length:ko.observable(''),
        	weight:ko.observable(0),
        	deposit:ko.observable(0),

        	pid:0,
        	afterRender:function(){
//        		$('#storetabs').accordion();
        		console.log('rendered',categoryTree);
        		inittree();
        		
        	},

        	initObservable: function () {
        		  console.log('initobser')

        		  this._super()
                       .observe({
                        status: ko.observable(true),
                        stores:ko.observableArray([]),
                    	price:ko.observable(),
                    	length:ko.observable(''),
                    	rent4:ko.observable(),
                    	rent8:ko.observable(),
                    	size:ko.observable(1),
                    	type:ko.observable('cloth'),
                    	coloropts:ko.observableArray([]),
                    	lenopts:ko.observableArray([]),
                    	brandopts:ko.observableArray([]),
                    	sizeopts:ko.observableArray([]),
                    	status:ko.observable(true),
                    	color:ko.observable(''),
                    	brand:ko.observable(''),
                    	weight:ko.observable(0),
                    	deposit:ko.observable(0)

                       });
        		  

                   this.status.subscribe(function (newValue) {
                       if(newValue){
                           console.log('checked');
                       }else{
                           console.log('Unchecked');
                       }
                   });

                   return this;
               }
           ,
        	submitProduct:function(){
        		 var cids = []; 
        	        $("#cattree").jstree("get_checked",null,true).each 
        	            (function () { 
        	                cids.push(this.id); 
        	            }); 
        	       
        		console.log('submitted',cids);
        		self=this;
        		$.ajax({
        			url:option.url,
        			type:'POST',
        			dataType:'json',
        			data:{
        				cids: cids,
        				images: images.images(),
        				stores:this.stores(),
        				price:this.price(),
        				rent4:this.rent4(),
        				rent8:this.rent8(),
        				type:this.type(),
        				weight:this.weight(),
        				size: this.size(),
        				color:this.color(),
        				brand:this.brand(),
        				length:this.length(),
        				deposit:this.deposit(),
        				pid: this.pid
        			}
        		}).done(function(s){
        			if(s.ok){
        				var pid=parseInt(s.ok);
        				if(!isNaN(pid)&&pid>0){
        					console.log('msg',option.msg,option.title);
        					
        				$('<div></div>').html( 'Product is saved' ).dialog({
        			        title: 'ok',
        			        resizable: false,
        			        modal: true,
        			        buttons: {
        			            'Ok': function()  {
        			                $( this ).dialog( 'close' );
        	        				addParams({pid:s.ok});

        			            }
        			        }
        			    });
        				
        				}
        				else{

        					$('<div></div>').html( 'Error in saving product' ).dialog({
        				        title: 'Error',
        				        resizable: false,
        				        modal: true,
        				        buttons: {
        				            'Ok': function()  {
        				                $( this ).dialog( 'close' );
        				            }
        				        }
        				    });
        					
        				}
        				
        			}
        			
        			console.log('saved',s);
        		}).always(function(){
        			console.log('saved');
        		});
        		
        	},
        	
            initialize: function (options) {
            	if(!options||options.data == undefined)
            		return;
            	this._super();
            	
            	console.log('initoptions',options.data)
            	
            	
            	categoryTree=options.data.cats;
            	option={
            			url: options.data.url,
            			msg:options.data.msg,
            			errmsg:options.data.errmsg,
            			title:options.data.title,
            			errtitle:options.data.errtitle
            	}
            	var data=options.data.data;
            	//this.name(data.name)
            	this.price(data.price)
            	this.rent4(data.rent4)
            	this.rent8(data.rent8)
            	this.type(data.type)
            	this.weight(data.weight)
            	this.status(data.status)
            	this.size(data.size);
            	this.color(data.color);
            	this.brand(data.brand);
            	this.pid=data.pid
            	this.stores(options.data.stores);
            	this.coloropts(options.data.coloropts),
            	this.lenopts(options.data.lenopts),
            	this.brandopts(options.data.brandopts),
            	this.sizeopts(options.data.sizeopts),
            	
            	
            	this.type.subscribe(function(v){
            			console.log('typec',v)
            			if(v=='cloth'){
            				$('#divsize').show();
            			}else{
            				$('divsize').hide();
            			}
            		
            	});

            	
///data-bind="checked:setMyRadio, data-bind="click: submitValue.bind($data,'5','129.00')"
//<input type="checkbox" name="custom" value="custom-fee" data-bind='checked: CheckVal'/>

            	
            	

            }
        });
});