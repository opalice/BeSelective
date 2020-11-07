define([
    "jquery",
    'Ibapi_Multiv/js/date_fns',
      'Magento_Customer/js/customer-data',
      'Magento_Catalog/js/price-utils',

      'mage/mage',
      'mage/translate',
      "jquery/ui",
      'Ibapi_Multiv/js/jquery.cookie',
      'Ibapi_Multiv/js/jquery.blockUI'


], function ($,dateFns,customerData,pU) {
    "use strict";

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
                    self.updateContent(url, true);
                }
            });
            
            $(window).unbind('popstate').bind('popstate', this.updateContent.bind(this));
            */
            
        },

        _initState: function () {
            var self = this;
                $(document).ready(function () {
                	$('.advopts').show();
                	var dateij=[];
                 	var initer;
                 	var moy;
                 	var mons;
                 	var cid;
                 	var block=[];
                 	var lasta=false;
                 	var unblock=[];
                 	var sdt='';
                	var rentp=0;
                	var edt='';
                
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
                	
               	 function updateParam(key, value) {

                	    var baseUrl = [location.protocol, '//', location.host, location.pathname].join(''),
                	        urlQueryString = document.location.search,
                	        newParam = key + '=' + value,
                	        params = '?' + newParam;

                	    // If the "search" string exists, then build params from it
                	    if (urlQueryString) {
                	        var updateRegex = new RegExp('([\?&])' + key + '[^&]*');
                	        var removeRegex = new RegExp('([\?&])' + key + '=[^&;]+[&;]?');

                	        if( typeof value == 'undefined' || value == null || value == '' ) { // Remove param if value is empty
                	            params = urlQueryString.replace(removeRegex, "$1");
                	            params = params.replace( /[&;]$/, "" );

                	        } else if (urlQueryString.match(updateRegex) !== null) { // If param exists already, update it
                	            params = urlQueryString.replace(updateRegex, "$1" + newParam);

                	        } else { // Otherwise, add it to end of query string
                	            params = urlQueryString + '&' + newParam;
                	        }
                	    }

                	    // no parameter was set so we don't need the question mark
                	    params = params == '?' ? '' : params;

                	    window.history.replaceState({}, "", baseUrl + params);
                	};
                	

                	 function addParam(param, value) {
                          //check if param exists
                          var result = new RegExp(param + "=([^&]*)", "i").exec(W.location.search);
                          result = result && result[1] || "";

                          //added seperately to append ? before params
                          var loc = W.location;
                          var url = loc.protocol + '//' + loc.host + loc.pathname + loc.search;

                          //param doesn't exist in url, add it
                          if (result == '')
                          {
                              //doesn't have any params
                              if (loc.search == '')
                              {
                                  url += "?" + param + '=' + value;
                              }
                              else
                              {
                                  url += "&" + param + '=' + value;
                              }
                          }else{
                        	  
                        	  
                          }

                          //return the finished url
                          return url;
                      }
                	function getrentp(){
                		console.log('rentp',rentp)
                		return rentp;
                	}
                 	function initcells(dateparam){
                 		//dateparams.date;
                /// 		console.log('dateparams',dateparam);
                 		var mons=dateparam.m;
                 		var rs=dateparam.r;
                 	 	$.each(rs,function(i,r){
                 	 		console.log('r',r);
                 	 		if(!r.ed || typeof r.ed =='undefined'){
                 	 			return;
                 	 		}
                 	 		var dts=r.ed.split('-');
                 	 		r.ed=new Date(dts[0],dts[1],dts[2]);
                 	 	    dts=r.sd.split('-');
                 	 		r.sd=new Date(dts[0],dts[1],dts[2]);

                 	 	})
                 		
                 		var d=	$("#tblrent thead tr:last th").each(function(i){
                 	 		$(this).text(dateparam.w[i]);
                 	 	});

                 		
                 		var m=dateFns.getMonth(dateparam.date);
                 		
                 		
                 		var nm=mons[m];
                 		var y=dateFns.getYear(dateparam.date);
                 		$('#calmo').text(nm);$('#calyr').text(y);
                 		
                 		
                 		var wkd=dateFns.getDay(dateparam.date);
                 		
                 		var fd=dateFns.getDay(dateFns.startOfMonth(dateparam.date));
                /// 		fd=6-fd;
                 		
                 		var pm=dateFns.addMonths(dateparam.date,-1)
                 		
                 		var nm=dateFns.addMonths(dateparam.date,1)
                 		$('#prevmo').data('date',dateFns.format(pm,'X'));
                 		$('#nextmo').data('date',dateFns.format(nm,'X'));
                 		
                 		
                 		
                 		var i=0;
                 		var j=0;
                 		
                 		var dtx=0;
                 		var curd=1;
                 		
                 		var pmd=dateFns.getDaysInMonth(pm)-fd+1;

                 		var mo='';
                 		var yr='';
                 		var nmd=1;
                 		var cyr=dateFns.getYear(dateparam.date)
                		var cm=dateFns.getMonth(dateparam.date)
                		var mxd=dateFns.getDaysInMonth(dateparam.date);
                 		dateij=[0,0,0,0,0,0]
                 		for(i=0;i<6;i++){
             				dateij[i]=[0,0,0,0,0,0,0];
                 			
                 		}
                		
                 		for(i=0;i<6;i++){
                 			for(j=0;j<7;j++){
                 				var txt='';
                 				var cl='';
                 				if(dtx<fd){
                 					cl='prevmod'
                 					yr=dateFns.getYear(pm)
                 					mo=dateFns.getMonth(pm)
                 					txt=pmd++;
                 					
                 				}else if(curd>mxd){
                 						txt=nmd++;
                 	 					yr=dateFns.getYear(nm)
                 	 					mo=dateFns.getMonth(nm)
                 	 					cl='nextmod'
                 					
                 				}else{
                 					txt=curd++;
                 					yr=cyr
                 					mo=cm;
                 					

                 				}
                 				dtx++;
                 	//	 		console.log('wkd',wkd,m,y,'fd',fd,'pmd',pmd,'pm',dateFns.getDaysInMonth(pm),'mo',mo,'yr',yr);

                 				dateij[i][j]=getcell(i+1,j+1,rs,yr,mo+1,txt,cl).find('a').data('val',[i+1,j+1]).on('click',function(e){
                 						e.preventDefault();
                 						$('.cal a').removeClass('block');
                 						var dt=$(this).data('date');
                 						lasta=$(this);
                 						var dts=dt.split('-')
                 						dt=new Date(dts[2],dts[1],dts[0]);
                 						sdt=dt;
                 						edt=dateFns.addDays(dt,getrentp())
                 						dts[3]=getrentp();
                 						$('#dater').val(dts.join('-'))
                 						var ij=$(this).data('val');
                 						var i1=ij[0]-1;
                 						var j1=ij[1]-1;
                 						var jj=0;
                 						var cnt=0;
                 						
                 						var jj=j1;
                 						var ii=i1;
                 						var rp=parseFloat($('#rentcal').data('rent'));
                 						var rt=parseInt(getrentp());
                 						console.log('find jj',ij,ii,jj,dateij)
                 						$('#rentcal').text(pU.formatPrice(rt*rp));
                 						try{
                 						while(cnt++<rentp){
                 							var td=	dateij[ii][jj++];
                 							td.addClass('block')
                 							if(jj==7){
                 								jj=0;
                 								ii++;
                 							}
                 							
                 						}
                 						}catch(e){
                 							$('.cal a').removeClass('block');
                             							
                 						}
                 						
                 						
                 						
                 						
                 						console.log('data',$(this).data('val'),dt,rentp);
                 						return false;
                 						
                 				});
                 			}
                 			
                 		}
                 		console.log('alldates',dateij)
                 		
                 	}
                 	
                 	function getcell(i,j,rs,yr,mo,txt,cl){
                 		rs=rs||[];
                 		var td=new Date(yr,mo,txt);
                 		var rf=rs.filter(function(r){
                 			console.log('checking r ',r.ed,'td',td);
                 			
                 			if(dateFns.compareAsc(r.ed,td)>=0&&dateFns.compareAsc(r.sd,td)<=0){
                 				return true;
                 			}
                 			return false;
                 		});
                 	var dt=yr+'-'+mo+'-'+txt;
                 	var d=	$("#tblrent tbody tr:nth-child("+i+") td:nth-child("+j+")");
                 	var a=d.find('a').text(txt).data('date',dt).each(function(){
                 		this.className=''
                 	});
                 	
                 	
                 	if(cl){
                 		console.log('add',cl,i,j)
                 		a.addClass(cl)
                 	}
                 	if(rf.length){
                 		a.addClass('block').addClass('block_'+rf.type).data('type',rf.type);
                		}
                 		
                 	return d;
                 	}

                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                	
                ///    self._saveState(document.location.href);
                	console.log('dateready',self.options);
                	
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
                		if(lasta)
                			lasta.trigger('click')
                	});
                	
                	
             	 	$('.cal_pn').on('click',function(e){
             	 		e.preventDefault();
             	 		var dt=$(this).data('date');
             	 		
             	 		$.blockUI({message:'loading dates.'});
             	 		$.ajax({
            
             	 			url: initer.url,
             	 			data:{date:dt,pid:''}
             	 			
             	 		}).done(function(res){
             	 			console.log('res',res);
             	 		    var dt=$.extend(true,{},initer.dateparam,res)
             	 		    console.log('dt',dt);
             	 			initcells(dt)
             	 			
             	 			
             	 		}).always(function(){$.unblockUI()});;
             	 		
             	 		return false;
             	 		
             	 	});

                	
                	
            		
                	
                	$('body').on('click','#btnsrc',function(){
                		if(lasta)
                			addParams([{name:'sd',value:lasta.data('date')},{name:'r',value:rentp},{name:'cat',value:cid}])
            		
                	});
                	
                	
                	
                	
                	initer=self.options;
                	
                	cid=initer.cid;
                	console.log('initer',initer);
                	var tx=dateFns.format(initer.dateparam.date,'YYYY-MM-DD')
                	$('.spnsd').html(tx);
                	console.log('tx',tx)
                	tx=dateFns.addDays(initer.dateparam.date,rentp)
                	tx=dateFns.format(tx,'YYYY-MM-DD')
                	console.log('tx2',tx)
               
                	$('.spned').html(tx);
                	
                	
                	initcells(initer.dateparam);
                	
                });
            

        },

        _saveState: function (url) {
            window.history.pushState({url: url}, '', url);
        },
        showDateWin:function(){
        	
        	
        },
        

        updateContent: function (url, updateState) {
            if (updateState) {
                this._saveState(url);
            }
            if (url instanceof Object) {
                url = url.originalEvent.state.url;
            }
            $('body').loader('show');
            var self = this;
            $.ajax({
                url: url,
                cache: true,
                type: 'GET',
                data: {niksAjax: true},
                success: function (resp) {
                    if (resp instanceof Object) {
                        $(self.options.filtersContainer).replaceWith(resp.leftnav);
                        $(self.options.productsContainer).replaceWith(resp.products);
                        $.mage.init();
                        $('html, body').animate({
                            scrollTop: $('#maincontent').offset().top
                        }, 400);
                        self._create();
                        $('body').loader('hide');
                    }
                }
            });
        },
        

        init: function (options) {
            if (!module.options) {
                module.options = options;
                module._create();
            }
            return {
                updateContent: module.updateContent.bind(module),
                showDateWin:module.showDateWin.bind(module)
            };
        }
    };

    return module.init;
});
