define(['jquery'], function($){ 
    "use strict";
    $(document).ready(function(){
        /*
            Toggle Click to FAQ
        */

        var faqHeading = $('.panel .panel-heading');

        $(faqHeading).click(function(){
            var icon = $(this).children().find('i');

            if($(icon).hasClass('fa-plus-square-o')){
                $(icon).removeClass('fa-plus-square-o');
                $(icon).addClass('fa-minus-square-o');
            }else{
                $(icon).removeClass('fa-minus-square-o');
                $(icon).addClass('fa-plus-square-o');
            }   
            $(this).next().fadeToggle('500',function(){
                
            });
        });
                   
    });
});