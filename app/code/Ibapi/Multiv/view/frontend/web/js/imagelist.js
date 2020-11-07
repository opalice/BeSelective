define(
    ['ko'],
    function (ko) {
        'use strict';
        var images = ko.observableArray([]);
        var newimages = ko.observableArray([]);
        var delimages = ko.observableArray([]);
        var self=this;
        
        function randomNumber() {
            return Math.floor((Math.random() * 255) + 1);
        }
        function addImage(s) {

        	images.push(s)
          }
        
        function removeImage(s){
        	console.log('image removed',s)
        	images.remove(s)
        	
        }
        
        return {
        	addImage: addImage,
            removeImage: removeImage,
            images:images,
            newimages:newimages,
            delimages:delimages
        };
    }
);