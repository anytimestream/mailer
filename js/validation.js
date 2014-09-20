/*  Validation version 1.0.0
 *  Normosa Technologies JavaScript Framework
 *  (c) 2011 Normosa Technologies
 *  @autor Norman Osaruyi
 *
 *  For details contact js-support@normosa.com
 *
 *--------------------------------------------------------------------------*/

NT.Core.Validation.Email = function(){
    var instance = this;
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

    instance.validate = function(){
        var _ui = $(instance.ui);
        if(reg.test(instance.val()) != true){
            instance.trigger('error','Invalid Email');
            return false;
        }
        else if(_ui.attr('plugin') != null){
            var e = eval(_ui.attr('plugin')+'.call(instance)');
            if(e != true){
                instance.trigger('error',e);
                return false;
            }
        }
        return true;
    }
}

NT.Core.Validation.String = function(){
    var instance = this;

    instance.validate = function(){
        var _ui = $(instance.ui);
        if((_ui.attr('min') != null) && (instance.val().length < parseInt(_ui.attr('min')))){
            instance.trigger('error', 'Minimum length is '+ _ui.attr('min'));
            return false;
        }
        else if((_ui.attr('max') != null) && (instance.val().length > parseInt(_ui.attr('max')))){
            instance.trigger('error', 'Maximum length is '+ _ui.attr('max'));
            return false;
        }
        else if(_ui.attr('plugin') != null){
            var e = eval(_ui.attr('plugin'));
            if(e != true){
                instance.trigger('error',e);
                return false;
            }
        }
        return true;
    }
}

NT.Core.Validation.Date = function(){
    var instance = this;
    var reg = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;

    instance.validate = function(){
        var _ui = $(instance.ui);
        if(_ui.attr('allowNull') == 'true' && instance.val().length == 0)
            return true;
        else if(reg.test(instance.val()) != true){
            instance.trigger('error','Invalid Date, format is dd/MM/yyyy');
            return false;
        }
        else if(_ui.attr('plugin') != null){
            var e = eval(_ui.attr('plugin')+'.call(instance)');
            if(e != true){
                instance.trigger('error',e);
                return false;
            }
        }
        return true;
    }
}

NT.Core.Validation.Number = function(){
    var instance = this;
    var _ui = $(instance.ui);

    instance.validate = function(){
        if(isNaN(instance.val()) == true){
            instance.trigger('error','Invalid Entry, Numbers only');
            return false;
        }
        else if((_ui.attr('min') != null) && (instance.val().length < parseInt(_ui.attr('min')))){
            instance.trigger('error','Minimum Value is '+_ui.attr('min'));
            return false;
        }
        else if((_ui.attr('max') != null) && (instance.val().length > parseInt(_ui.attr('max')))){
            instance.trigger('error','Maximum Value is '+_ui.attr('max'));
            return false;
        }
        else if(_ui.attr('plugin') != null){
            var e = eval(_ui.attr('plugin')+'.call(instance)');
            if(e != true){
                instance.trigger('error',e);
                return false;
            }
        }
        return true;
    }
}