define([
    'Magento_Ui/js/grid/columns/column',
    'jquery',
    'mage/template',
    'text!Ibapi_Multiv/template/grid/refund.html',
    'mage/url'
,
'Magento_Ui/js/modal/confirm',
'Magento_Ui/js/modal/alert',

    'Magento_Ui/js/modal/modal',


], function (Column, $, mageTemplate, aTemplate,url,confirm,alert) {
    'use strict';
    var  previewPopup;
    var action;
    var currow;
    var self={};
    

    
    $(document).ready(function(){
    $('body').on('click','.btnmultiv',function(e){
    	///var dc=$('table[data-role="grid"] > tbody > tr:eq('+rowindex+')');
 
    	if($('input[name="raction"]:checked').val()&&$('input[name="paction"]:checked').val()){
    		return;
    	}
    	
    	jQuery('body').loader('show');
    	$.ajax({
    		data: {
    			raction: $('input[name="raction"]:checked').val(),
    			paction: $('input[name="paction"]:checked').val(),
    			part:$('input[name="part"]').val(),
    			note:$('textarea[name="note"]').val(),
    			message:$('textarea[name="message"]').val(),
    			id:$('input[name="id"]').val(),
    			action:'O',
    		    form_key: FORM_KEY

    		},
    		type: 'POST',
    		dataType:'json'
    		
    	}).done(function(x){
    		if(!x.error){
    				if(x.all&&typeof x.all!=='undefined'){
    			     	 $('a[data-role="'+currow.orderid+'"]').text('done');
    				}
    				else{
    					
    					$('a[data-r="'+currow.id+'"]').text('done');
    				}
    			
    			//	dc.hide();
    		}else{
    			
    		}
    		   alert({
    		        title: x.error?'Error':'OK',
    		        content:x.result,
    		        actions: {
    		            always: function(){}
    		        }
    		    });
    		
    	}).fail(function(x){

    		   alert({
    		        title: 'Error',
    		        content: 'In request',
    		        actions: {
    		            always: function(){}
    		        }
    		    });
    	}).always(function(){
    		jQuery('body').loader('hide');
    		previewPopup.modal('closeModal')
    		$(previewPopup).empty();
    		
    	});
    	
    });
    	
    })
    
    return Column.extend({
    	_create:function(){
    		this._super();
    		self=this;
    		
    	},
        defaults: {
            bodyTmpl: 'ui/grid/cells/html',
            fieldClass: {
                'data-grid-html-cell': true
            }
        },
        gethtml: function (row) {
            return row[this.index + '_html'];
        },
        getFormaction: function (row) {
            return row[this.index + '_formaction'];
        },
        getCustomerid: function (row) {
        	
            return row[this.index + '_id'];
        },
        getLabel: function (row) {
            return row[this.index + '_html']
        },
        getOrder:function (row){
        	return row[this.index+'_order']
        },
        getTitle: function (row) {
            return row[this.index + '_title']
        },
        getSubmitlabel: function (row) {
            return row[this.index + '_submitlabel']
        },
        getLink:function (row){
        	return row[this.index+'_url'];

        },
        getCancellabel: function (row) {
            return row[this.index + '_cancellabel']
        },
        preview: function (row) {

    	    //   if(row.method!='ops_cc'){

//            var x=url.build('/admin/sales/order/view/'+row.orderid);

  ///          console.log('x',x);
     ///       return false;
        ///    return;
           // }

        	action=this.getFormaction(row);
        	currow=row;
            var modalHtml = mageTemplate(
                aTemplate,
                {
                    html: this.gethtml(row), 
                    title: this.getTitle(row), 
                    label: this.getLabel(row), 
                    formaction: this.getFormaction(row),
                    id: this.getCustomerid(row),
                    order: this.getOrder(row),
                    submitlabel: this.getSubmitlabel(row), 
                    cancellabel: this.getCancellabel(row), 
                }
            );
             previewPopup = $('<div/>').html(modalHtml);
             previewPopup.find('input[type="radio"]').attr('disabled',true);
             
             previewPopup.find('input[data-role="'+row.status+'"]').attr('disabled',false);             
           
             if(row.status=='processing'){
            	 previewPopup.find('input[data-role="'+row.status+'"]').prop('checked',true);            	 
             }else if(row.status=='rent_default'){
            	 previewPopup.find('input[data-fault="1"]').prop('disabled',false);            	 
             }
            
             
             ///console.log('prev',row.status,previewPopup.find('input[type="radio"]'))
             
            previewPopup.modal({
                title: this.getTitle(row),
                innerScroll: true,
                modalClass: '_image-box',
                buttons: []}).trigger('openModal');
        },
        getFieldHandler: function (row) {
    	        if(row.method!='ops_cc'){
    	            return false;
    	            //console.log('redirecting');
                }
    	    ///   return false;
            return this.preview.bind(this, row);
        }
    });
});