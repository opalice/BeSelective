define([
    'jquery',
    'jquery/ui'
 ],
    function($){
        $.widget('multiv.cal', {
        	options: {
                url: 'magently_ajax/getInfo',
                method: 'post',
                triggerEvent: 'click'
            },
            _init: function () {
                alert("I'm Inchoo");
            },
            toggle: function () {
                alert("I'm Inchoo");
            }
    });
    return $.multiv.cal;
    });