/*  Form version 1.0.0
 *  Normosa Technologies JavaScript Framework
 *  (c) 2011 Normosa Technologies
 *  @autor Norman Osaruyi
 *
 *  For details contact js-support@normosa.com
 *
 *--------------------------------------------------------------------------*/

normosa.prototype.form = function(e){
    var  instance = this;
    instance.init = _init;
    instance.parent = e.parent;
    instance.objectCode = instance.ui.id;
    instance.elements = new Array();

    function _init(){
        instance.inherit(NT.Core.events);
        $(instance.ui).submit(on_submit);
        $.each(instance.ui.elements, function(){
            if($(this).attr('inputtype') != null){
                var inputType = $(this).attr('inputtype').toLowerCase();
                instance.elements[instance.elements.length] = eval('$n(this).'+inputType+'({parent:instance})');
            }
        });
    }

    instance.getValues = function(){
        var str = '';
        for(var i = 0; i < instance.elements.length; i++){
            if($(instance.ui).attr('method').toLowerCase() == 'get')
                str += instance.elements[i].id + '=' + window.escape(instance.elements[i].val());
            else str += instance.elements[i].id + '=' + instance.elements[i].val();
            if(i < instance.elements.length - 1)
                str += '&';
        }
        return str;
    }

    function on_submit(e){
        if(instance.isValid()){
            if(instance.parent.forms != null){
                instance.parent.forms.submit.call(instance, e);
            }
            else if($(instance.ui).attr('method').toLowerCase() == 'get'){
                NT.Core.Instances['history'].setUrl('?'+instance.getValues());
                e.preventDefault();
                instance.dispose();
            }
            else{
                doPost(e);
            }
        }
        else{
            e.preventDefault();
        }
    }

    function doPost(e){

    }

    instance.isValid = function(){
        var ok = true;
        for(var i = 0; i < instance.elements.length; i++){
            if(!instance.elements[i].validate())
                ok = false;
        }
        return ok;
    }

    instance.inherit(NT.Core.base);

    return instance;
}

normosa.prototype.textbox = function(e){
    var instance = this;
    instance.init = _init;
    instance.parent = e.parent;
    instance.objectCode = instance.ui.id;
    instance.id = instance.ui.id;
    instance.val = function(){
        return $(instance.ui).val();
    }

    function _init(){
        $(instance.ui).blur(on_blur);
        $(instance.ui).focus(on_focus);
        instance.label = null;
        instance.inherit(NT.Core.ui);
        instance.inherit(NT.Core.events);
        instance.events.error = new Array();
        var _ui = $(instance.ui);
        instance.label = $(instance.ui.parentNode).children('span.error')[0];
        instance.addEventListener('error', showError);
        if(_ui.attr('validation') != null)
            eval('instance.inherit(NT.Core.Validation.'+_ui.attr('validation')+')');
        else
            instance.inherit(NT.Core.Validation.String);
    }

    function on_blur(){
        instance.validate();
    }

    function on_focus(){
        if(instance.label != null){
            $(instance.label).fadeOut();
        }
    }

    function showError(e){
        instance.label.innerHTML = e;
        $(instance.label).fadeIn();
    }

    instance.inherit(NT.Core.base);
    return instance;
}

normosa.prototype.autosuggest = function(e){
    var instance = this;
    instance.init = _init;
    instance.parent = e.parent;
    instance.objectCode = instance.ui.id;
    instance.id = instance.ui.id;
    instance.val = function(){
        return $(instance.ui).val();
    }

    function _init(){
        $(instance.ui).blur(instance.validate);
        $(instance.ui).focus(instance.hideError);
        $(instance.ui).keydown(instance.keydown);
        instance.label = null;
        instance.menu = null;
        instance.isValid = false;
        instance.isWorking = false;
        instance.url = $(instance.ui).attr('lookup');
        instance.inherit(NT.Core.ui);
    }

    instance.validate = function(){
        if(!instance.isValid && $(instance.ui).attr('allownull') != 'true'){
            instance.showError('Not Valid');
            return false;
        }
        if($(instance.ui).attr('plugin') != null && eval($(instance.ui).attr('plugin')) != true){
            instance.showError(eval($(instance.ui).attr('plugin')));
            return false;
        }
        instance.hideError();
        return true;
    }

    instance.hideError = function(){
        if(instance.label != null){
            $(instance.label).fadeOut();
        }
    }

    instance.keydown = function(e){
        var str = '';
        if(_isvalid(e.which))
            str = instance.val() + String.fromCharCode(e.which);
        else
            str = instance.val();
        _lookup(instance.url+'?value='+window.escape(str)+'&ajax=true');

    }

    function _lookup(_url){
        if(instance.isWorking) return;
        instance.isWorking = false;
        $.ajax({
            type:'GET',
            url:_url,
            success:function(data){
                _showlist(data);
                instance.isWorking = false;
            }
        });
    }

    function _showlist(data){
        if(instance.menu == null){
            instance.menu = document.createElement('ul');
            instance.ui.parentNode.appendChild(instance.menu);
            instance.menu.className = 'autosuggest';
        }
        $(instance.menu).empty();
        instance.menu.style.position = 'absolute';
        var rows = JSON.parse(data);
        for(var i = 0; i < rows.length; i++){
            var li = document.createElement('li');
            var a = document.createElement('a');
            a.id = rows[i].id;
            a.innerHTML = rows[i].value;
            a.title = rows[i].title;
            if(rows[i].text != null)
                a.setAttribute('text', rows[i].text);
            a.onclick = _setvalue;
            li.appendChild(a);
            instance.menu.appendChild(li);
        }
        var d = instance.getDimension();
        $(instance.menu).css('left', d.left + 'px');
        $(instance.menu).css('top', (d.top + d.height) + 'px');
        $(instance.menu).show();
    }

    function _setvalue(){
        $(instance.ui).val(this.id);
        var local = this;
        this.title = this.innerHTML;
        if(this.getAttribute('text') != null)
            this.innerHTML = this.getAttribute('text');
        instance.isValid = true;
        instance.validate();
        this.onclick = function(){
            $(instance.ui).val('');
            $(instance.menu).fadeOut();
            $(instance.ui).focus();
            instance.isValid = false;
        }
        var d = instance.getDimension();
        instance.menu.style.left = d.left + 'px';
        instance.menu.style.top = d.top + 'px';
        local.style.width = d.width + 'px';
        $(instance.menu).children().each(function(){
            if(this.childNodes[0] != local)
                instance.menu.removeChild(this);
        });
    }

    function _isvalid(e){
        var reg = /^([A-Za-z0-9_\-\.])$/;
        if(reg.test(String.fromCharCode(e)))
            return true;
        return false;
    }

    instance.showError = function(e){
        if(instance.label == null){
            instance.label = document.createElement('span');
            instance.label.className = 'validator';
            instance.ui.parentNode.appendChild(instance.label);
        }
        instance.label.innerHTML = e;
        if($(instance.parent.ui).attr('errorFlag') == null){
            var w = instance.label.offsetWidth;
            instance.label.style.position = 'absolute';
            instance.anchorLeft(instance.label);
            if(instance.label.style.width == '')
                instance.label.style.width = w + 'px';
        }
        $(instance.label).fadeIn();
    }

    instance.inherit(NT.Core.base);
    return instance;
}

normosa.prototype.date = function(e){
    var instance = this;
    instance.init = function(){};
    instance.parent = e.parent;
    instance.objectCode = instance.ui.id;
    instance.id = instance.ui.id;
    var format = "dd/mm/yy";
    var changeMonth = true;
    var changeYear = true;
    var numberOfMonths = 1;
    if($(instance.ui).attr('format') != null){
        format = $(instance.ui).attr('format');
    }
    if($(instance.ui).attr('changeMonth') != null){
        changeMonth = $(instance.ui).attr('changeMonth');
    }
    if($(instance.ui).attr('numberOfMonths') != null){
        numberOfMonths = parseInt($(instance.ui).attr('numberOfMonths'));
    }
    var pickerOpts = {
        changeMonth: changeMonth,
        changeYear: changeYear,
        dateFormat: format,
        numberOfMonths: numberOfMonths,
        showOtherMonths: true
    };
    instance.datepicker = $(instance.ui).datepicker(pickerOpts);
    
    //instance.datepicker('option', 'minDate', minDate);
    
    instance.val = function(){
        return $(instance.ui).val();
    }
    if($(instance.ui).attr('ext') != null){
        eval($(instance.ui).attr('ext')+'.call(instance)');
    }

    instance.validate = function(){
        return true;
    }

    instance.inherit(NT.Core.base);

    return instance;
}

normosa.prototype._default = function(e){
    var instance = this;
    instance.init = function(){};
    instance.parent = e.parent;
    instance.objectCode = instance.ui.id;
    instance.id = instance.ui.id;
    instance.val = function(){
        return $(instance.ui).val();
    }

    instance.validate = function(){
        return true;
    }

    instance.inherit(NT.Core.base);

    return instance;
}
