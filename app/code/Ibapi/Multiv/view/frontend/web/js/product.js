define([
    "jquery",
    'Ibapi_Multiv/js/date_fns',
      'Magento_Customer/js/customer-data',

      'Magento_Ui/js/modal/modal',
      'Magento_Catalog/js/price-utils',
      'ko',
      'uiComponent',

      'mage/mage',

      'mage/translate',
      'Ibapi_Multiv/js/jquery.cookie',
      'Ibapi_Multiv/js/jquery.blockUI',
      'domReady!'


], function ($,dateFns,customerData,modal,pU,ko,Component) {
    "use strict";
	var curt='';
	var sd=[];
	var pid='';
	var tz=new Date().getTimezoneOffset()*60*1000;
	var selr=false;
	var seldate=false;
 	var lasta=false;
	var rentp=0;
	var wash=0;
	var rent=0;
	var deposit=0;
	var lasttd=false;
	var initer;
    var module = {
    		
         		
    		
    		
    		
    		
    		
    		
        _create: function () {
            this._initState();
            var self = this;
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            /*
             * $.removeCookie('name'); // => true
$.removeCookie('nothing'); // => false

// Need to use the same attributes (path, domain) as what the cookie was written with
$.cookie('name', 'value', { path: '/' });
            $('#layered-filter-block, .pages-items').off('click', 'a').on('click', 'a', function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                if (url) {
                    self.updateCont(url, true);
                }
            });
            
            $(window).unbind('popstate').bind('popstate', this.updateContent.bind(this));
            */
            
        },

        _initState: function () {
            var self = this;
///                $(document).ready(function () {
   
            		
            
            		$('.advopts').show();
                	var dateij=[];
                 	var moy;
                 	var nevb;
                 	var mons;
                 	var cid;
                 	var block=[];
                 	var unblock=[];
                 	var sdt='';
                	
                
                	
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
                	
                	

                	function getrentp(){
                		//console.log('rentp',rentp)
                		return rentp;
                	}
                 	function initcells(dateparam,size,first,wsh){
                 		console.log('date v3');
                 		nevb=true;
                 		if(wsh>0)
                 			wash=wsh;
                 		var bds=[];
                 		//dateparams.date;
                 		var mons=dateparam.m;
                 		var rs=dateparam.b;
                 		var blcks=dateparam.r;
                 		
                 		if( size!=='-'){
                 		if(dateparam.sd){
                 		$('.spnsd').html(dateparam.sd);
             
            			bds[0]=dateparam.sd.split('-').reduce(function(x,y,i){
             				if(i==0){
             					x+=parseInt(y)*10000;
             				}
             				else if(i==1){
             					x+=parseInt(y)*100;
             				}
             				else
             					x+=parseInt(y);
             				return x;
             			},0);
             			
         
                 		
                 		}
                 	
                 		else{
                 			bds[0]=0;
                 	 		$('.spnsd').html('&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;-&nbsp;&nbsp;');
                 		}
                 		
                 		//console.log('spned',dateparam.ed)
                 		if(dateparam.ed){
                 			bds[1]=dateparam.ed.split('-').reduce(function(x,y,i){
                 				if(i==0){
                 					x+=parseInt(y)*10000;
                 				}
                 				else if(i==1){
                 					x+=parseInt(y)*100;
                 				}
                 				else
                 					x+=parseInt(y);
                 				return x;
                 			},0);
                 			
                 	 		$('.spned').html(dateparam.ed);
                 	 		}
                 	 		else{
                     	 		bds[1]=0;
                 	 			$('.spned').html('&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;-&nbsp;&nbsp;');
                 	 		}
                 		
                 		}
                    	
                  		seldate=dateparam.sel.split('-').map(x=>parseInt(x));
                  		//console.log('bds',bds);
                  		

                    	if(dateparam.d&&typeof dateparam.d!='undefined'){
                    		selr=parseInt(dateparam.d);
                    		rentp=selr;
                    		$('#rentp_'+rentp).attr('checked',true);
 
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

                 		
                 		
                 		
                 		
                 		
                 		
                 		
                 		
                 		curt= dateparam.now.split('-').map(a=>parseInt(a));
                 		
                 		var curmo=dateparam.cm;
                 		 
                 		//console.log('paramb',rs)
                 		if(dateparam.sd){
                 		sd=dateparam.sd.split('-');
                 		sd=sd.map(a=>parseInt(a));
                 		}else{
                 			
                 			
                 		}
                                  		
                 		
                 		
                 		var curmo=dateparam.cm;
                 
                 		///console.log('sd',sd,'curt',curt);
                 		
                 	 	$.each(blcks,function(i,r){
                 	 		var dts=r.sd.split('-').map(x=>parseInt(x));
                 	 		r.sd=dts[0]*10000+dts[1]*100+dts[2];
                 		    dts=r.ed.split('-').map(x=>parseInt(x));
                 	 		r.ed=dts[0]*10000+dts[1]*100+dts[2];
                 	 		
         					r.et = dateFns.getTime(new Date(dts[0], dts[1]-1, dts[2]));
                 	 		
                 	 		
                 	 	});
                 		

                 	 	$.each(rs,function(i,r){
                 	 		var dts=r.dt.split('-').map(x=>parseInt(x));
                 	 		r.b=dts[0]*10000+dts[1]*100+dts[2];
                 	 		if(r.b==bds[0]){
                 	 			bds[0]=0
                 	 		}
            	 	 		

                 	 	})
                 		
                 		var d=	$("#tblrent thead tr:last th").each(function(i){
                 	 		$(this).text(dateparam.w[i]);
                 	 	});

                 		
                 		///var m=dateFns.getMonth(dateparam.date);
          		
//                 		var m=sd[1];
                 		var nm=mons[curmo-1];
  //               		var y=sd[0];
                 		

                 		$('#calmo').text(nm);$('#calyr').text(dateparam.cy);
                 		var tmo=false;
                 		var dtx=new Date(curt[0],curt[1]-1,curt[2]);
                 		var dty=new Date(curt[0],curt[1]-1,curt[2]);
                 		var tod=0;
                 		var tmo=seldate[1]==curt[1];
             		var tod=0;
                 		
                 		var fd=dateparam.fd;
                /// 		fd=6-fd;
                 		
                 		var pm=dateparam.pm
                 		
                 		var nm=dateparam.nm
                 		
                 		$('#prevmo').data('nxt',-1).data('date',dateparam.sel);
                 		$('#nextmo').data('nxt',1).data('date',dateparam.sel);
                 		///console.log('pm',pm,nm,'fd',fd);
                 		
                 		
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

                 					
                 					yr=cyr
                 					mo=cm;
                 				}
                 				dtxx++;
                 	//	 		console.log('wkd',wkd,m,y,'fd',fd,'pmd',pmd,'pm',dateFns.getDaysInMonth(pm),'mo',mo,'yr',yr);

//                 				console.log('tdd',yr,mo+1,txt,'cm',cm,curt);
                 				var bl=false;
                         		var td=yr*10000+mo*100+txt
                         		var tt= dateFns.getTime(dateFns.addDays(new Date(yr,mo-1,txt),wash));
                         		
                         		var bkd=false;
                 				$.each(blcks,function(i,r){
                 					if(r.et>=tt&&r.sd<=td){
                 						bl=r;
                 						if(td>=bds[0] && td <=bds[1]){
                         					//console.log('checking',r,td,bds)

                 							bkd=true;
                 						}	else{
///                 							console.log('no ',td,bds)
                 						}
                 						
                 						return false;
                 					}
                 					
                 				});
                 			
                 	
                 		 var rf=rs.filter(function(r){
//                 			console.log('checking r ',r.b,'td',td.getTime());
                 			
                 			if(r.b==td){
                 				return true;
                 			}
                 			return false;
                 		});
                 		 var b=false;
         				 if(rf&&rf.length){
         					 cl.push('blocked');
         					 b=true;
         				 }
         				 if(!bl){
         					 cl.push('booked');
         					 if(!b)
         					 cl.push('blocked')
         				 
         				 }else if(bkd){
         					 nevb=false;
         					 if(td==bds[0]){
         						 cl.push('blockstart')
         					 }	
         					 else if(td==bds[1]){
         						 lasttd=1;
         						 cl.push('blockend')
         					 }
         					 cl.push('block')
         				 }
                 		 
                 				
                 				dateij[i][j]=getcell(i+1,j+1,yr,mo,txt,cl).data('val',[i+1,j+1])
                 				if(first)
                 				dateij[i][j].on('click',function(e){
                 						e.preventDefault();
                 					//	console.log('clickerd');
                 						if($(this).hasClass('blocked')){
                 							return false;
                 						}
                 						if($(this).hasClass('booked')){
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
                 						var dts=dt.split('-')
                 						dt=new Date(dts[2],dts[1],dts[0]);
                 						sdt=dt;
                 						var ij=$(this).data('val');
                 						var i1=ij[0]-1;
                 						var j1=ij[1]-1;
                 						var jj=0;
                 						var cnt=0;
                 						
                 						var jj=j1;
                 						var ii=i1;
                 						
             							var err=false;
//                 						console.log('find jj',ij,ii,jj,dateij)
               							$('.cal td').removeClass('block').removeClass('blockend').removeClass('blockstart');

                 						try{
                 							lasttd=false;
                 						while(cnt++<rentp){
                 							var td=	dateij[ii][jj++];
                 							if(td.hasClass('booked')){
                 								throw 'err'
                 							}
                 							
                 							if(cnt==1){
                 									td.addClass('blockstart');
                 							}else if(cnt==rentp){
             									td.addClass('blockend');
                 							}

                 							td.addClass('block')
                 							lasttd=td.data('date');
                 							if(jj==7){
                 								jj=0;
                 								ii++;
                 							}
                 							
                 						}
                 						}catch(e){
                 							$('.cal td').removeClass('block');
                             				err=true;			
                 						}
                 						
                 						if(err){
                 							$('#btns').hide();
                 							lasta=false;
                 						}else{
                     						lasta=$(this);
                 							
                 						}
                 						var rp=parseFloat(rent);
                 						var dp=parseFloat(deposit);
                 						var rt=parseInt(getrentp());
                 						rt=rt==8?initer.rent8:initer.rent4;
                 						
                 						$('#rentcal').text(pU.formatPrice(rt+deposit));

                 						return false;
                 						
                 				});
                 			}
                 			
                 		}
                 		if(nevb){
                 	 		$('.spnsd').html('&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;-&nbsp;&nbsp;');
                 	 		$('.spned').html('&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;-&nbsp;&nbsp;');
                 		}else{
                 			$('#btns').show();
                 		}
                 		
                 	}
                 	
                 	function getcell(i,j,yr,mo,txt,cl){
                 		
                 		
                 	var dt=yr+'-'+mo+'-'+txt;
                 	var d=	$("#tblrent tbody tr:nth-child("+i+") td:nth-child("+j+")");

                 	var a=d.find('a').text(txt).end().data('date',dt).removeClass();

                 	if(d.data('z')==1){
                 		d.addClass('blocked');
                 	}

                 	if(d.data('zz')==1){
                 		if(rentp!=8)
                 		d.addClass('blockedx');
                 		else
                 			d.removeClass('blockedx');                 			
                 	}                 	

                 	$.each(cl,function(i,c){
                 		d.addClass(c)
                 	});

                 	
                 	
                 	return d;
                 	}

                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                ///    self._saveState(document.location.href);
                	
                	rentp=$.cookie('rentalp');
                	if(!rentp || typeof rentp=='undefined'){
                		rentp=8;
                	}
                	if(rentp!=8&&rentp!=4) {
                		rentp=8;
                	}
                	$('#rentp_'+rentp).attr('checked',true);
                	
                	
                	$('.rentp').on('change',function(){
                		$.cookie('rentalp',$(this).val())
                		rentp=$(this).val();
                		rent=rentp==8?initer.rent8:initer.rent4;
                		$('.rentalprice').text(pU.formatPrice(rent))
                		$('.productrentprice').text(pU.formatPrice(rent)) 
                		lasta='';
                		$('.spnsd').html('&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;-&nbsp;&nbsp;');
             	 		$('.spned').html('&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;-&nbsp;&nbsp;');
             	 		$('#dater').val('')              	
             	 		if(rentp==4)
             	 		$('[data-zz]').addClass('blockedx');
             	 		else
             	 			$('[data-zz]').removeClass('blockedx');             	 			
             	 		$('.cal td').removeClass('block').removeClass('blockend').removeClass('blockstart');
/*
 * 
                		if(lasta)
                			{
                			lasta.trigger('click')
                			
                			}*/
                		
                	});
                	
                	
             	 	$('.cal_pn').on('click',function(e){
             	 		e.preventDefault();
             	 		var dt=$(this).data('date');
             	 		var nt=$(this).data('nxt')
             	 		
             	 		$.blockUI({message:'loading dates.'});
             	 		$.ajax({
            
             	 			url: initer.url,
             	 			data:{date:dt,pid:pid,next:nt}
             	 			
             	 		}).done(function(res){
             	 			if(res.error==1){
             	 				return;
             	 			}
             	 		    var dt=$.extend({},res.dateparam)
             	 			initcells(dt,'-',false,0)
             	 			
             	 			
             	 		}).always(function(){$.unblockUI()});;
             	 		
             	 		return false;
             	 		
             	 	});

                	

                    var options = {
                            'type': 'popup',
                            'modalClass': 'dateform',
//                            'focus': '[name=username]',
                            'responsive': true,
                            'innerScroll': true,
                             opened:function(){
                            	 
                            	
                             },
                            'trigger': '.datedialog',
                            'buttons': []
                        };

                        modal(options, $('#divdtdialog'));

             	 	
                    	$('body').on('click','#btncls',function(){
                    		
                    		$('#divdtdialog').modal('closeModal');
                		
                    	});
                    	
            		
                	
                	$('body').on('click','#btnsrc',function(){

                		if(lasta){
                			var sd=lasta.data('date');
                			var r=rentp;
                			if($('#multiv_purch').is(':visible')){
                				if($('#multiv_purch').data('r')!=r ||$('#multiv_purch').data('sd')!=sd){
                					$('#dater').val('')
                			$('.spnsd').html('--')
                			$('.spned').html('--');
                					return;
                				}
                				
                			}
                			
                			$('#dater').val(sd+'-'+r)
                    		$('#divdtdialog').modal('closeModal');
                			if(lasttd&&lasttd!=1){
                			$('.spnsd').html(sd)
                			$('.spned').html(lasttd);
                			}
                		}
                		
//                		if(lasta)
  //              			addParams([{name:'sd',value:lasta.data('date')},{name:'r',value:rentp},{name:'cat',value:cid}])
            		
                	});
                	
     /*           	
                	$('body').on('click','.datedialog',function(){
                	
///                			$('#divdtdialog').dialog('open');
                		//	console.log('dtdlc')
                		
                	});
   */             	
                	$('body').on('click','.sizedialog',function(){
                		
                	});
                	
                	initer=self.options.options;
                	cid=initer.cid;
                	
                	if(initer.sizes&&initer.sizes.length>1){
                		
                			initer.sizes=initer.sizes.filter(function(x){
                				return x[1]!=initer.size;
                				
                			});
   ///             		console.log('ts',tsize);
///                		$('#othersizes').show().append($(tsize));
                	
                	}else{
                    	
                		initer.sizes=[];
                	
                	}
                	
                	
                	pid=initer.pid;
                	
    
                	
                	
                  	if(initer.dateparam.sel&&typeof initer.dateparam.sel!='undefined'){
                		seldate=initer.dateparam.sel.split('-').map(x=>parseInt(x));
                	}
                	if(initer.dateparam.d&&typeof initer.dateparam.d!='undefined'){
                		selr=parseInt(initer.dateparam.d);
                		rentp=selr;
                		$('#rentp_'+rentp).attr('checked',true);

                	}else{

                    	if(!rentp || typeof rentp=='undefined'){
                    		rentp=8;
                    	}
                    	if(rentp!=8&&rentp!=4) {
                    		rentp=8;
                    	}
                    	$('#rentp_'+rentp).attr('checked',true);

                	}
                	var dx=new Date(seldate[0],seldate[1]-1,seldate[2]);
               
                	deposit=initer.deposit;
                	rent=rentp==4?initer.rent4:initer.rent8;
                	//console.log('rentp',initer)
                	$('.rentalprice').text(pU.formatPrice(rent))
                	$('#depshow').text(pU.formatPrice(deposit))
                	
                	
                	
     	 		    var dt=$.extend({},initer.dateparam)
                	initcells(dt,initer.sz,true,initer.wash);
                	if(lasta){
//                		console.log('lasta',lasta)
  //              		$(lasta).trigger('click');
                	}
                	
                ///domready});
            

        },

        _saveState: function (url) {
            window.history.pushState({url: url}, '', url);
        },
        showDateWin:function(){
        	
        	
        },
        

        updateContent: function (url, updateState) {},
        

        init: function (options) {
            if (!module.options) {
                module.options = options;
                if(!options.options.rental_option)
                module._create();
                
                else{
                	if(options.options.rental_option=='x'){
                		$('.pper').show();
                		$('.fper').hide();
                		$('#product-addtocart-button').hide();
                	}else{
                	$('.spnsd').text(options.options.sd)
                	$('.spned').text(options.options.ed)
                	$('#dater').val(options.options.rental_option)
                	}
                	$('.advopts2').show();                	
                	$('.advopts').hide();
//                
                
                }
            }
            return Component.extend( {
            	default:{
            		template: 'Ibapi_Multiv/size',
            	},
//            	getSizes: module.getSizes.bind(module)
            		
            	
            });
        }
    };

    return module.init;
});
