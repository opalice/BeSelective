define(
    ['ko'],
    function (ko) {
        'use strict';
        var data=ko.observableArray([]);
        
        return {
        		data: data
        };  
        
    }
    );