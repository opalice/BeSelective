define([
    "jquery",
    'Ibapi_Multiv/js/date_fns',
      'Magento_Customer/js/customer-data',
      'Magento_Ui/js/modal/modal',
      'Magento_Catalog/js/price-utils',

      'mage/mage',

      'mage/translate',
      'Ibapi_Multiv/js/jquery.cookie',
      'Ibapi_Multiv/js/jquery.blockUI'


], function ($,dateFns,customerData,modal,pU) {
    "use strict";
    var self;
	var curt='';
	var sd=[];
	var tz=new Date().getTimezoneOffset()*60*1000;
	console.log('tz',tz);
	var selr=false;
	var seldate=false;
 	var lasta=false;
	var rentp=0;
	var dateij=[];
 	var initer;
 	var moy;
 	var mons;
 	var cid;
 	var block=[];
 	var unblock=[];
 	var sdt='';
	var edt='';
	function getrentp(){
		return rentp;
	}

	
    function addParams(params){
    	console.log('entering addparams',params);
    	var queryParameters = {}, queryString = location.search.substring(1),
        re = /([^&=]+)=([^&]*)/g, m;

    // Creates a map with the query string parameters
    while (m = re.exec(queryString)) {
    	if((m[1]=='rent4'||m[1]=='rent8')&&m[1]!=$.pricetype){
    		continue;
    	}
    	queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
    }

    // Add new parameters or update existing ones
    $.each(params,function(i,v){
        queryParameters[v.name] = v.value;
    	
    });
	console.log('changing location',$.param(queryParameters));
    location.search = $.param(queryParameters); // Causes page to reload

    }

	

 	function initcells(dateparam,size,first){
 		//dateparams.date;

 		
 		if(dateparam.sd){
 		$('.spnsd').html(dateparam.sd);
 		
 		}
 	
 		else{
 	 		$('.spnsd').html('&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;-&nbsp;&nbsp;');
 		}
 		if(dateparam.ed){
 	 		$('.spned').html(dateparam.ed);
 	 		
 	 		}
 	 	
 	 		else{
 	 	 		$('.spned').html('&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;-&nbsp;&nbsp;');
 	 		}
 	 	
    	
  		seldate=dateparam.sel.split('-').map(x=>parseInt(x));

    	if(dateparam.d&&typeof dateparam.d!='undefined'){
    		selr=parseInt(dateparam.d);
    		rentp=selr;
    		if(rentp!=4&&rentp!=8) rentp=4;
    		
    		$('#rentp_'+rentp).attr('checked',true);
    		$.pricetype='rent'+rentp;

    	}
    	
        	if(rentp!=8&&rentp!=4) {
        		rentp=8;
        	}
        
        	$('#rentp_'+rentp).attr('checked',true);

    	
    	
    	var dx=new Date(seldate[0],seldate[1]-1,seldate[2]);
    	
    	
    	
    	
    	var sizes=[]
    	if(size&&typeof size !=='undefined'){
    	
    		sizes=size.split(',');
    		$('.spnsz').html(size);
            $.each(sizes,function(i,v){
            	
            	$('.szitem[data-s="'+v+'"]').addClass('selected');
            });

    	}

        
 		
 		var mons=dateparam.m;
 		var rs=dateparam.b;
 		curt= dateparam.now.split('-').map(a=>parseInt(a));
 		
 		var curmo=dateparam.cm;
 
 		console.log('paramb',rs)
 		if(dateparam.sd){
 		sd=dateparam.sd.split('-');
 		sd=sd.map(a=>parseInt(a));
 		}else{
 			
 			
 		}
 		
 	 	$.each(rs,function(i,r){
 	 		console.log('r',r);
 	 		var dts=r.dt.split('-').map(a=>parseInt(a));
 	 		r.b=dts[0]*10000+dts[1]*100+dts[0];
 	 		
 	 		

 	 	})
 		
 		var d=	$("#tblrent thead tr:last th").each(function(i){
 	 		$(this).text(dateparam.w[i]);
 	 	});

 		
 		///var m=dateFns.getMonth(dateparam.date);
	
 //		var m=sd[1];
 		var nm=mons[dateparam.cm-1];
 	//	var y=sd[0];
 		
 		$('#calmo').text(nm);$('#calyr').text(dateparam.cy);
 		var tmo=false;
 		var dtx=new Date(curt[0],curt[1]-1,curt[2]);
 		var dty=new Date(curt[0],curt[1]-1,curt[2]);
 		var tod=0;
 		var tmo=seldate[1]==curt[1];
 		
 		
 		
 		var fd=dateparam.fd;
/// 		fd=6-fd;
 		
 		var pm=dateparam.pm
 		
 		var nm=dateparam.nm;
 		
 		$('#prevmo').data('nxt',-1).data('date',dateparam.sel);
 		$('#nextmo').data('nxt',1).data('date',dateparam.sel);
 		
 		
 		var i=0;
 		var j=0;
 		var curd=1;
 		
 		var pmd=dateFns.getDaysInMonth(pm)-fd+1;

 		
 		var mo='';
 		var yr='';
 		var nmd=1;
 		var cyr=dateparam.cy;
		var cm=dateparam.cm;
		

		var mxd=dateparam.mxd;
 	
 		dateij=[0,0,0,0,0,0]
 		for(i=0;i<6;i++){
				dateij[i]=[0,0,0,0,0,0,0];
 			
 		}
		var dtxx=0;
		for(i=0;i<6;i++){
 			for(j=0;j<7;j++){
 				var txt=0;
 				var cl=[];
/*
 * prevmod if less than this month sel..start of selected month
 * 
 * blocked if less than curdate
 */
 				
 				if(dtxx<fd){
 					cl.push('prevmod')
 					yr=dateparam.py
 					mo=dateparam.pm
 					txt=pmd++;
 					
 				}else if(curd>mxd){
 						txt=nmd++;
 						
 	 					yr=dateparam.ny
 	 					mo=dateparam.nm
 	 					cl.push('nextmod')
 					
 				}else{
 					txt=curd++;
 					if(tmo&&txt<=curt[2]){
 						cl.push('blocked');
 					}
 						cl.push('curmod');
 					

 					yr=dateparam.cy
 					mo=dateparam.cm
 				}
 				dtxx++;
 	//	 		console.log('wkd',wkd,m,y,'fd',fd,'pmd',pmd,'pm',dateFns.getDaysInMonth(pm),'mo',mo,'yr',yr);
         		var td=//new Date(yr,mo,txt);
         		   yr*10000+mo*100+txt
         		

// 				console.log('tdd',yr,mo+1,txt,'cm',cm,curt);
 				 var rf=rs.filter(function(r){
          			
          			if(r.b==td){
          				return true;
          			}
          			return false;
          		});
 				 if(rf&&rf.length){
 					 cl.push('blocked');
 				 }
          		
 				
 				
 				dateij[i][j]=getcell(i+1,j+1,yr,mo,txt,cl).data('val',[i+1,j+1]);
 				if(first)
 					dateij[i][j].on('click',function(e){
 						e.preventDefault();
 						console.log('clickerd');
 						if($(this).hasClass('blocked')){
 							return false;
 						}
 						if($(this).hasClass('blockedx')){
 							return false;
 						}
 						if(!$(this).hasClass('curmod')){
 							return false;
 						}
 						$('#btns').show();
 						
 						var dt=$(this).data('date');
 						lasta=$(this);
 						var dts=dt.split('-')
 						dt=new Date(dts[0],dts[1],dts[2]);
 						sdt=dt;
 						edt=dateFns.addDays(dt,getrentp())
 						var ij=$(this).data('val');
 						var i1=ij[0]-1;
 						var j1=ij[1]-1;
 						var jj=0;
 						var cnt=0;
 						
 						var jj=j1;
 						var ii=i1;
							$('.cal td').removeClass('block').removeClass('blockend').removeClass('blockstart');
 						
 						try{
 						while(cnt++<rentp){
 							var td=	dateij[ii][jj++];
 							if(cnt==1){
 									td.addClass('blockstart');
 							}else if(cnt==rentp){
									td.addClass('blockend');
 								
 							}

// 							console.log('td',td);
 							td.addClass('block')

 							if(jj==7){
 								jj=0;
 								ii++;
 							}
 							
 						}
 						}catch(e){
 							$('.cal td').removeClass('block');
  							$('#btns').hide();

             							
 						}
 						
 						
 						
 						
 						return false;
 						
 				});
 			}
 			
 		}
 		
 	}	
 	

 	
 	function getcell(i,j,yr,mo,txt,cl){
 		
	var rf=false;
 		
 	var dt=yr+'-'+mo+'-'+txt;
 	var d=	$("#tblrent tbody tr:nth-child("+i+") td:nth-child("+j+")");

 	var a=d.find('a').text(txt).end().data('date',dt).removeClass();
 	if(d.data('z')==1){
 		d.addClass('blocked');
 	}
 	if(d.data('zz')==1){
 		if(rentp==4)
 		d.addClass('blockedx');
 		else
 			d.removeClass('blockedx');
 	}
 	$.each(cl,function(i,c){
 		d.addClass(c)
 	});
 	if(!lasta&&sd&&sd[0]==yr&&sd[1]==mo&&sd[2]==txt){
 		lasta=d;
 	}
 	

 		
 	return d;
 	}

	
		

function fetchdt(dt,nt){
	
		$.blockUI({message:'loading dates.'});
		$.ajax({

			url: initer.url,
			data:{date:dt,pid:'',next:nt}
			
		}).done(function(res){
			if(res.error){
				return;
			}
			var dt=$.extend({},res.dateparam)
			initcells(dt,false,false)
			
			
		}).always(function(){$.unblockUI()});;

}


    var module = {
    		
    		
       options:{} 	,
            	
    		
    		
        _create: function () {
            this._initState();
            
            
            
            
            
            
            
            /*
             * $.removeCookie('name'); // => true
$.removeCookie('nothing'); // => false

// Need to use the same attributes (path, domain) as what the cookie was written with
$.cookie('name', 'value', { path: '/' });
            $('#layered-filter-block, .pages-items').off('click', 'a').on('click', 'a', function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                if (url) {
                    self.updateContent(url, true);
                }
            });
            
            $(window).unbind('popstate').bind('popstate', this.updateContent.bind(this));
            */
            
        },

        _initState: function () {
        	self=this;
        	initer={}
            initer.url=this.options.url;
        	initer.dateparam=this.options.dateparem;
        	initcells($.extend(true,{},this.options.dateparam),this.options.s,true);
           /// console.log('url',initer.url,self.options);
            
        //	cid=initer.cid;
        	//console.log('initer',initer);
//        	var tx=dateFns.format(initer.dateparam.date,'YYYY-MM-DD')
        
        	
	 		
	 		    
	 		    
        	if(lasta){
        		$(lasta).trigger('click');
        	}

            
            
            
            $(document).ready(function () {
            		$.pricetype='rent4';
            		
//                	$('.advopts').show();
                //	fetchdt('','');
                	
                	
             	 	$('.cal_pn').on('click',function(e){
             	 		e.preventDefault();
             	 		var dt=$(this).data('date');
             	 		var nt=$(this).data('nxt')
             	 		fetchdt(dt,nt);
             	 		return false;
             	 	});
                	
             	 	

                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                ///    self._saveState(document.location.href);
                	
                	
                	
                	$('.rentp').on('change',function(){
                		$.cookie('rentalp',$(this).val())
                		rentp=$(this).val();
                		if(rentp!=4&&rentp!=8) rentp=4;
 
                		$.pricetype='rent'+rentp;
                		
                		$('[data-zz]').each(function(e){
                			if(rentp==4){
                					$(this).addClass('blockedx');
                			}else{
                				$(this).removeClass('blockedx');                				
                			}
                			
                			
                		});
                		lasta=false;
                		$('#tblrent td').removeClass('block').removeClass('blockstart').removeClass('blockend');
                		/*
                		if(lasta)
                			{
                			lasta.trigger('click')
                			
                			}
                		*/
                	});
                	

                	

                    var options = {
                            'type': 'popup',
                            'modalClass': 'dateform',
//                            'focus': '[name=username]',
                            'responsive': true,
                            'innerScroll': true,
                             opened:function(){
                            	 
                            	console.log('modal open');
                            	
                             },
                            'trigger': '.datedialog',
                            'buttons': []
                        };

                        modal(options, $('#divdtdialog'));
                        var options = {
                                'type': 'popup',
                                'modalClass': 'sizeform',
//                                'focus': '[name=username]',
                                'responsive': true,
                                'innerScroll': true,
                                 opened:function(){
                                	 
                                	console.log('modal open');
                                	
                                 },
                                'trigger': '.sizedialog',
                                'buttons': []
                            };


                        
                        
                        modal(options, $('#divszdialog'));

             	 	
                    	$('body').on('click','#btncls',function(){
                    		
                    		$('#divdtdialog').modal('closeModal');
                		
                    	});
                    	$('body').on('click','.szitem',function(e){
                    		e.preventDefault();
                    		if($(this).data('s')==''){
                    			
                    			$('.szitem').removeClass('selected');

                    					return;
                    		}
                    		if($(this).hasClass('selected')){
                    			$(this).removeClass('selected');
                    		}else{
                    			$(this).addClass('selected');
                    		}
                    		return false;

                    	});
                    	
            		
                    	$('body').on('click','.szapply',function(e){
                    		e.preventDefault();
                    		console.log('appliued');
                    		var s=[];
                    		$('.szitem.selected').each(function(){s.push($(this).data('s'))});
                    		
                			addParams([{name:'size',value:s.join(',')}]);

                    	});


                    		$('body').on('click','.szcls',function(e){
                    		e.preventDefault();
                    		console.log('appliued');
                    		var s=[];
                    		$('.szitem.selected').each(function(){s.push($(this).data('s'))});

                			addParams([{name:'size',value:''}]);

                    	});


                    	$('body').on('click','#btnsrc',function(){
                		if(lasta)
                			{
                			var ps=[{name:'sd',value:lasta.data('date')},{name:'r',value:rentp}];
                			if(cid){
                				ps.append({name:'cat',value:cid});
                			}
                			console.log('app2');
                			addParams(ps)
                			}
                	});
                	
     /*           	
                	$('body').on('click','.datedialog',function(){
                	
///                			$('#divdtdialog').dialog('open');
                		//	console.log('dtdlc')
                		
                	});
   */             	
                	$('body').on('click','.sizedialog',function(){
                		
                		
                	});
                	
                	
                	
                });
            

        },

        _saveState: function (url) {
            window.history.pushState({url: url}, '', url);
        },
        showDateWin:function(){
        	
        	
        },
        

        updateContent: function (url, updateState) {},
        

        init: function (options) {
        		module.options=options;
                module._create();

                return {
                updateContent: module.updateContent.bind(module),
                showDateWin:module.showDateWin.bind(module)
            };
        }
    };

    return module.init;
});
