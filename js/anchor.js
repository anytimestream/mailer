/*  Anchor version 1.0.0
 *  Normosa Technologies JavaScrnt Framework
 *  (c) 2011 Normosa Technologies
 *  @autor Norman Osaruyi
 *
 *  For details contact js-support@normosa.com
 *
 *--------------------------------------------------------------------------*/

normosa.prototype.anchor = function(e){
    var  instance = this;
    instance.init = _init;
    instance.e = e;
    instance.objectCode = 'anchor_'+(Math.floor(Math.random() * (999999999 - 100000000 + 1)) + 100000000);
    instance.ui.id = instance.objectCode;
    instance.url = $(instance.ui).attr('href');
    instance.inherit(NT.Core.base);

    function _init(){
        NT.Core.Instances['history'].addEventListener('historychanged', function(e){
            var _ui = $(instance.ui);
            if(e.indexOf(instance.url) >= 0){
                if(_ui.hasClass('default'))
                    _ui.removeClass('default').addClass('active');
            }
            else if(_ui.attr('map') != null && e.indexOf(_ui.attr('map')) >= 0){
                if(_ui.hasClass('default'))
                    _ui.removeClass('default').addClass('active');
            }
            else if(instance.url == e){
                if(_ui.hasClass('default'))
                    _ui.removeClass('default').addClass('active');
            }
            else{
                if(_ui.hasClass('active'))
                    _ui.removeClass('active').addClass('default');
            }
        });
        $(instance.ui).click(on_click);
    }

    function on_click(e){
        e.preventDefault();
        NT.Core.Instances['history'].setUrl(instance.url);
    }

    return instance;
}
