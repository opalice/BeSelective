define(['ko','Magento_Ui/js/form/element/abstract', 'jquery','Ibapi_Multiv/js/date_fns','Ibapi_Multiv/js/jquery.blockUI'],function(ko,Abstract,$,dateFns) {
 	$(function(){
 		
 	})
 	
    var self;
	var curt='';
	var sd=[];
	var tz=new Date().getTimezoneOffset()*60*1000;
	var selr=false;
	var seldate=false;
 	var lasta=false;
	var dateij=[];
 	var initer;
 	var moy;
 	var mons;
 	var cid;
 	var sdt='';
	var edt='';
	 	var block=[];
 	 	var unblock=[];

 	
 	var moy;
 	var mons;
/// 	var rs;
 	
 	

	

 	function initcells(dateparam,first){

 		nevb=true;
 		var bds=[];
 		//dateparams.date;
 		var mons=dateparam.m;
 		var rs=dateparam.b;
 		var ablcks=dateparam.a;
 		var blcks=dateparam.r;
 		
// 	 	 block=[];
 //	 	 unblock=[];
 	 	dateij=[];
 		
    	
  		seldate=dateparam.sel.split('-').map(x=>parseInt(x));
  		

        
    	var dx=new Date(seldate[0],seldate[1]-1,seldate[2]);
    	
 		
 		
 		
 		
 		
 		
 		
 		
 		curt= dateparam.now.split('-').map(a=>parseInt(a));
 		
 		var curmo=dateparam.cm;
 		 
 		if(dateparam.sd){
 		sd=dateparam.sd.split('-');
 		sd=sd.map(a=>parseInt(a));
 		}else{
 			
 			
 		}
                  		
 		
 		
 		var curmo=dateparam.cm;
 
 		
 	 	$.each(blcks,function(i,r){
 	 		var dts=r.sd.split('-').map(x=>parseInt(x));
 	 		r.sd=dts[0]*10000+dts[1]*100+dts[2];
 		    dts=r.ed.split('-').map(x=>parseInt(x));
 	 		r.ed=dts[0]*10000+dts[1]*100+dts[2];
 	 	});


 	 	$.each(rs,function(i,r){
 	 		var dts=r.dt.split('-').map(x=>parseInt(x));
 	 		r.b=dts[0]*10000+dts[1]*100+dts[2];
 	 		if(r.b==bds[0]){
 	 			bds[0]=0
 	 		}
 	 		

 	 	})
 	 	
 	 	$.each(ablcks,function(i,r){
 	 		var dts=r.dt.split('-').map(x=>parseInt(x));
 	 		r.b=dts[0]*10000+dts[1]*100+dts[2];
/*
 	 		if(r.b==bds[0]){
 	 			bds[0]=0
 	 		}
 	 		*/
 	 		

 	 	})

 	 	
 		
 		var d=	$("#tblrent thead tr:last th").each(function(i){
 	 		$(this).text(dateparam.w[i]);
 	 	});

 		
 		///var m=dateFns.getMonth(dateparam.date);
	
// 		var m=sd[1];
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

// 				console.log('tdd',yr,mo+1,txt,'cm',cm,curt);
 				var bl=false;
         		var td=yr*10000+mo*100+txt
         		var bkd=false;
         		var ab=false;
 				$.each(blcks,function(i,r){
 					if(r.ed>=td&&r.sd<=td){
 						bl=r;
 						/*
 						if(td>=bds[0] && td <=bds[1]){
         					console.log('checking',r,td,bds)

 							bkd=true;
 						}	else{
///                 							console.log('no ',td,bds)
 						}*/
 						
 						return false;
 					}else{
 					}
 					
 				});
 			
 	
 		 var rf=rs.filter(function(r){
// 			console.log('checking r ',r.b,'td',td.getTime());
 			
 			if(r.b==td){
 				return true;
 			}
 			return false;
 		});
 		 
 		 var ab=ablcks.filter(function(r){
  			
  			if(r.b==td){
  				return true;
  			}
  			return false;
  		});

 		 bkd=false;
 		 var b=false;
			 if( rf&&rf.length){
				 cl.push('block');
				 cl.push('block_A');
				 b=true;
			 }
			else if(ab&&ab.length){
				cl.push('block');
				cl.push('block_a');
			}
			else if(!bl){
				 cl.push('booked');
				// if(!b)
				 //cl.push('blocked')
			 
			 }else if(bkd){
				 nevb=false;
				 /*
				 if(td==bds[0]){
					 cl.push('blockstart')
				 }	
				 else if(td==bds[1]){
					 lasttd=1;
					 cl.push('blockend')
				 }*/
				 cl.push('block')
			 }
 		 
 				
 				var cdate=dateij[i][j]=getcell(i+1,j+1,yr,mo,txt,cl).data('val',[i+1,j+1]);
 				if(first)
 				cdate.on('click',function(e){
						e.preventDefault();
						if($(this).hasClass('block_A')||$(this).hasClass('booked')||$(this).hasClass('blocked')||$(this).hasClass('prevmod')||$(this).hasClass('nextmod')){
		 					var dt=$(this).data('date');

							return false;
						}
						
						
 					var dt=$(this).data('date');
 					
						if($(this).hasClass('block')){
							
							if($(this).hasClass('block_a')){
								
								if(unblock.indexOf(dt)==-1)
									unblock.push(dt);
								if(block.indexOf(dt)>-1)
		 							block.splice(block.indexOf(dt),1)
		 							
		 						

								
								$(this).removeClass('block').removeClass('block_a');
							}
							
						}else{
							if(block.indexOf(dt)==-1){
							block.push(dt);
							$(this).addClass('block').addClass('block_a').data('type','a');
							}
						if(unblock.indexOf(dt)>-1){
	 							unblock.splice(unblock.indexOf(dt),1)
	 							
						}
	 						}

 					
 					
 				});
 			}
 			
 		}
 		
 		
 	
 		
 		
 	}	
 	

 	
 	function getcell(i,j,yr,mo,txt,cl){
 		
	var rf=false;
 		
 	var dt=yr+'-'+mo+'-'+txt;
 	var d=	$("#tblrent tbody tr:nth-child("+i+") td:nth-child("+j+")");

 	d.find('a').text(txt);

 	d.removeAttr('class');

 	if(d.data('z')==1){
 		d.addClass('blocked');
 	}
 	d.data('date',dt);
 	
// 	console.log('setting text ',i,j,txt,mo,yr,'c',cl);
 	$.each(cl,function(i,c){
 		d.addClass(c)
 	});

 		
 	return d;
 	}

	
		


 	
 	
	
	return Abstract.extend({

        defaults: {
        	template: {
                name: 'Ibapi_Multiv/rental',
                afterRender: function(){
              		initcells($.extend(true,{},initer.dateparam),true);
              		
                   	
             	 	$('.cal_pn').on('click',function(e){
             	 		e.preventDefault();
             	 		var dt=$(this).data('date');
             	 		var nt=$(this).data('nxt')
             	 		
             	 		$.blockUI({message:'loading dates.'});
             	 		$.ajax({
            
             	 			url: initer.url,
             	 			data:{date:dt,pid:initer.pid,next:nt}
             	 			
             	 		}).done(function(res){
             	 			if(res.error==1){
             	 				return;
             	 			}
             	 		    var dt=$.extend({},res.dateparam)
             	 		  initcells(dt,false)
             	 			
             	 			
             	 		}).always(function(){$.unblockUI()});;
             	 		
             	 		return false;
             	 		
             	 	});

              		
              		

              		
             		$('#btndtrent').on('click',function(){
             			
             			var d=ko.dataFor($('input[name="product[rental_dt]"')[0])
             			d.value(block.join(',')+":"+unblock.join(','));
           			
//             			$(document).find("input[name='product[rental_dt]']").val(block.join(',')+":"+unblock.join(','));
             		});
              		/*
              		$("#btndtrent").calendar({
             		      dateFormat: "M/d/yy",
             		      showsTime: true,
             		      timeFormat: "HH:mm:ss",
             		      sideBySide: true,
             		      closeText: "Done",
             		      selectOtherMonths: true,
             		      onClose: function( selectedDate ) {
             		       /// $( "#period_date_end" ).datepicker( "option", "minDate", selectedDate );
             		      }
             		   });
             		*/
             		
             	 		
             	}

                }
,
        	
        },

        /**
         * Initializes component, invokes initialize method of Abstract class.
         *
         *  @returns {Object} Chainable.
         */
        initialize: function (options) {
        	initer=options;
        	
        	dateparam=options.dateparam;
        	
       
        	
         		$("#btndtrent").on('click',function(){
         	
         		});

        		
            return this._super();
        },


        /**
         * Init observables
         *
         * @returns {Object} Chainable.
         */
        initObservable: function () {
            return this._super()
                .observe([
                    'items'
                ]);
        },
      

        /**
         * Change currently selected color
         *
         * @param {String} color
         */
        selectDate: function(id){
        	
        },

        /**
         * Returns class based on current selected color
         *
         * @param {String} color
         * @returns {String}
         */
        isSelected: function (color) {
            return color === this.value() ? 'selected' : '';
        }
    });
});