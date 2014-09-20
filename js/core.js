/*  Core version 1.0.0
 *  Normosa Technologies JavaScript Framework
 *  (c) 2011 Normosa Technologies
 *  @autor Norman Osaruyi
 *
 *  For details contact js-support@normosa.com
 *
 *--------------------------------------------------------------------------*/

var _ie = document.all? true : false;
var _iev = null;
if(_ie){
    var index = navigator.appVersion.indexOf('MSIE');
    _iev = parseInt(navigator.appVersion.toString().substring(index + 5).substring(0,1));
}

NT = {};
NT.UI = {};
NT.Core = {};
NT.Core.Instances = {};
NT.Core.Validation = {};

NT.Keys = {
    ctrl:{
        isPress:false
    }
};

$(document).keydown(function(e){
    if(e.keyCode == 17)
        NT.Keys.ctrl.isPress = true;
}).keyup(function(e){
    if(e.keyCode == 17)
        NT.Keys.ctrl.isPress = false;
});

NT.Core.base = function(){
    var instance = this;
    instance.dispose = function(){
        if(instance.trigger != null)
            instance.trigger('disposing', {});

        instance.unRegister();

        if(instance.trigger != null)
            instance.trigger('disposed', {});
    }
    if(instance.parent != null && instance.parent.addEventListener != null){
        instance.parent.addEventListener('disposing', function(e){
            instance.dispose();
        });
    }

    instance.register = function(){
        if(NT.Core.Instances[instance.getObjectCode()] == null){
            NT.Core.Instances[instance.getObjectCode()] = instance;
            instance.init();
        }
    }

    instance.unRegister = function(){
        if(NT.Core.Instances[instance.getObjectCode()] != null)
            delete NT.Core.Instances[instance.getObjectCode()];
    }

    instance.getObjectCode = function(){
        return instance.objectCode;
    }

    instance.registerComponents = function(ui){
        try{
            _plugins.each(ui);
        }catch(e){}

        $('#'+ui+' .normosa-ui-banner').each(function(){
            $n(this).banner({
                parent:instance,
                container:ui
            });
        });
        $('#'+ui+' .normosa-ui-datatable').each(function(){
            NT.Core.Instances[this.id] = $n(this).datatable({
                parent:instance,
                container:ui
            });
        });
        $('#'+ui+' form.normosa-ui-form').each(function(){
            NT.Core.Instances[this.id] = $n(this).form({
                parent:instance,
                container:ui
            });
        });
        $('#'+ui+' a.normosa-ui-anchor').each(function(){
            $n(this).anchor({
                parent:instance,
                container:ui
            });
        });
    }

    instance.disposeComponents = function(ui){
        $.each(NT.Core.Instances, function(){
            if($('#'+ ui +' *[id='+this.getObjectCode()+']').length > 0){
                this.dispose();
            }
        });
    }

    instance.register();
}

NT.Core.events = function(){
    var instance = this;

    instance.events = {
        'disposing':new Array(),
        'disposed':new Array()
    };

    instance.addEventListener = function(evt, callback){
        if(instance.events[evt] != null)
            instance.events[evt][instance.events[evt].length] = callback;
    }

    instance.trigger = function(evt, args){
        if(instance.events[evt] != null){
            for(var i = 0; i < instance.events[evt].length; i++){
                if(instance.events[evt][i].call(instance, args) == false)
                    break;
            }
        }
    }
}

NT.Core.hashTable = function(){
    var instance = this;
    var list = {};

    instance.put = function(name, value){
        list[name] = value;
    }

    instance.remove = function(name){
        if(list[name] != null)
            delete list[name];
    }

    instance.get = function(name){
        return list[name];
    }

    instance.matchUrl = function(name){
        for(var e in list){
            if(name.toString().indexOf(e, 0) >= 0){
                return list[e];
            }
        }
        return null;
    }

    instance.each = function(e){
        $.each(list, function(){
            this(e);
        });
    }

}

NT.Core.ui = function(){
    var instance = this;
    instance.anchorLeft = function(ui){
        var d = instance.getDimension();
        $(ui).css('left', d.left + d.width + 'px');
        $(ui).css('top', d.top + 'px');
        $(ui).show();
    }

    instance.anchorCenter = function(){
        $(instance.ui).css('margin-top', Math.ceil((- $(instance.ui).height() / 2)) + 'px');
        $(instance.ui).css('margin-left', Math.ceil((- $(instance.ui).width() / 2)) + 'px');
        $(instance.ui).show();
    }

    instance.getDimension = function(ui){
        var left, top, height, width = 0;
        var _ui = (ui == null)? instance.ui: ui;
        width = _ui.offsetWidth;
        height = _ui.offsetHeight;
        if(_ui.offsetParent){
            left = _ui.offsetLeft;
            top = _ui.offsetTop;
            while((_ui = _ui.offsetParent) && left > 0 && $(_ui).css('position') != 'absolute'){
                left += _ui.offsetLeft;
                top += _ui.offsetTop;
            }
        }
        if(_ie && _ev == 7){
            left += 2;
        }
        return {
            left:left,
            top:top,
            width:width,
            height:height
        };
    }

}

function $n(ui){
    return new normosa(ui);
}

function normosa(ui){
    this.ui = ui;
}

normosa.prototype.inherit = function(parent){
    parent.call(this);
}

