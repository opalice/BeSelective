define(function () {
 'use strict';


		 
 return function (target) { 
   
	 target.cb=null;
	 
	 target.setCallback=function(x){
		 this.cb=x;
	 };
	 return target;
 };
});

