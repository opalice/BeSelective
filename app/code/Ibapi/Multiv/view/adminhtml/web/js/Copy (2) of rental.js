define(['ko','Magento_Ui/js/form/element/abstract', 'jquery','Ibapi_Multiv/js/date_fns','Ibapi_Multiv/js/jquery.blockUI'],function(ko,Abstract,$,dateFns) {
 	$(function(){
 		
 	})
 	var initer;
 	var moy;
 	var mons;
 	var block=[];
 	var unblock=[]
 	
 	
 	
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
 		
 		var d=	$("#tblrent thead tr th").each(function(i){
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

 				getcell(i+1,j+1,rs,yr,mo+1,txt,cl).find('a').data('val',[i+1,j+1]).on('click',function(e){
 						e.preventDefault();
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
 							if(block.indexOf(dt)==-1)
 							block.push(dt);
 							$(this).addClass('block').addClass('block_a').data('type','a');
 						
							if(unblock.indexOf(dt)>-1)
		 							unblock.splice(unblock.indexOf(dt),1)
		 							
		 						}

 						
 						console.log('data',$(this).data('val'),$(this).data('date'));
 						return false;
 						
 				});
 			}
 			
 		}
 		
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
  		console.log('rendered');
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
 	 		
 	},

 	*/
 	
	
	return Abstract.extend({

        defaults: {
        	template: {
                name: 'Ibapi_Multiv/rental',
                afterRender: function(){
                	console.log('render',dateFns.getTime(dateparam.date));
              		console.log('rendered');
              		initcells($.extend(true,{},initer.dateparam));
              		
             	 	$('.cal_pn').on('click',function(e){
             	 		e.preventDefault();
             	 		var dt=$(this).data('date');
             	 		
             	 		$.blockUI({message:'loading dates.'});
             	 		$.ajax({
            
             	 			url: initer.url,
             	 			data:{date:dt,pid:initer.pid}
             	 			
             	 		}).done(function(res){
             	 			console.log('res',res);
             	 		    var dt=$.extend(true,{},initer.dateparam,res)
             	 		    console.log('dt',dt);
             	 			initcells(dt)
             	 			
             	 			
             	 		}).always(function(){$.unblockUI()});;
             	 		
             	 		return false;
             	 		
             	 	});

              		
             		$('#btndtrent').on('click',function(){
             			
             			console.log('ok',block,unblock);
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
        	console.log('opts',options.dateparam);
        	initer=options;
        	
        	dateparam=options.dateparam;
        	
       
        	
         		$("#btndtrent").on('click',function(){
         	 		console.log('btncl',block,unblock);
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
        	console.log('date',id);
        	
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