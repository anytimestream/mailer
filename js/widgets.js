/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

iportalworks.prototype.banner = function(e){
    var  instance = this;
    instance.init = _init;
    instance.e = e;
    instance.objectCode = instance.ui.id;
    instance.inherit(IP.Core.base);
    
    function _init(){
        if($(instance.ui).hasClass('fading')){
            fading();
        }
        else{
            slidingTab();
        }
    }

    function slidingTab(){
        instance.content = $($('#'+instance.ui.id+' .content div')[0]);
        instance.banners = instance.content.children('a');
        instance.len = instance.banners.length;
        if(instance.len < 1){
            return;
        }
        instance.bannerWidth = instance.banners[0].offsetWidth;
        instance.content.css('width', (instance.len * instance.bannerWidth)+'px');
        instance.index = 1;
        setInterval(function(){
            if(instance.index == instance.len){
                $('#'+instance.ui.id).hide();
                $('#'+instance.ui.id+' .content').animate({
                    'scrollLeft':(0 * instance.bannerWidth)+'px'
                });
                $('#'+instance.ui.id).fadeIn();
                instance.index = 0;
            }
            else{
                $('#'+instance.ui.id+' .content').animate({
                    'scrollLeft':(instance.index * instance.bannerWidth)+'px'
                });
                instance.index++;
            }
        },10000);
    }

    function fading(){
        instance.imgs = $('#'+instance.objectCode+' a');
        instance.len = instance.imgs.length;
        if(instance.len < 1){
            return;
        }
        $(instance.imgs[0]).fadeIn();
        instance.index = 1;
        
        setInterval(function(){
            if(instance.index == instance.len){
                instance.index = 0;
                $(instance.imgs[(instance.len - 1)]).fadeOut(function(){
                    $(instance.imgs[0]).fadeIn();
                    instance.index++;
                });
            }
            else{
                $(instance.imgs[instance.index - 1]).fadeOut(function(){
                    $(instance.imgs[instance.index]).fadeIn();
                    instance.index++;
                });
            }
        },7000);
    }
    
    return instance;
}

