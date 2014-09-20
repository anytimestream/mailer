/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function slider(){
    var instance = this;
    instance.ui = $(instance.element);
    instance.containerHeight = $(jQuery(instance.element).find(".container")[0]).height();
    $(instance.element).find("a").mouseenter(function(event){
        var desc = $(this).find('.description');
        desc.css('top', event.pageY+'px');
        desc.css('left', event.pageX+'px');
        desc.show();
    });
    $(instance.element).find("a").mouseleave(function(event){
        $(this).find('.description').hide();
    });
    instance.animate = function(){
        var scrollTop = $(instance.element).scrollTop();
        if(scrollTop < (instance.containerHeight - instance.ui.height())){
            instance.ui.animate({
                'scrollTop': scrollTop + instance.ui.height()
            });
        }
        else{
            instance.ui.scrollTop(0);
        }
    }
    instance.stop = function(){
        clearInterval(instance.interval);
    }
    instance.start = function(){
        instance.interval = setInterval(instance.animate, instance.duration);
    }
    instance.ui.mouseenter(instance.stop);
    instance.ui.mouseleave(instance.start);
    instance.start();
}

