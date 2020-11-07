define(
    	[
        	'jquery',
        	'ko',
        	'uiComponent',
   		'[NameSpace]_[ModuleName]/js/view/grid/price'
    	],
    	function ($, ko, component, priceRender) {
        	"use strict";
 
        	return component.extend({
            	items: ko.observableArray([]),
            	columns: ko.observableArray([]),
            	defaults: {
                	template: '[NameSpace]_[ModuleName]/grid',
            	},
            	initialize: function () {
                	this._super();
                	this._render();
            	},
            	_render: function() {
                	this._prepareColumns();
                	this._prepareItems();               	 
            	},
            	_prepareItems: function () {
            	},
            	_prepareColumns: function () {
 
            	},
            	addItem: function (item) {
                	item.columns = this.columns;
                	this.items.push(item);
            	},
            	addItems: function (items) {
                	for (var i in items) {
                    	this.addItem(items[i]);
                	}
            	},
            	addColumn: function (column) {
                	this.columns.push(column);
            	}
        	});
    	}
);