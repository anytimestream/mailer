/*  Dialog version 1.0.0
 *  Normosa Technologies JavaScript Framework
 *  (c) 2011 Normosa Technologies
 *  @autor Norman Osaruyi
 *
 *  For details contact js-support@normosa.com
 *
 *--------------------------------------------------------------------------*/

normosa.prototype.dialog = function(e){
    var  instance = this;
    instance.init = _init;
    instance.e = e;
    instance.allowExit = true;
    instance.parent = e.parent;
    var dialogOpts = {
        close:function(){
            instance.close();
        },
        beforeclose: function(){
            return instance.allowExit;
        },
        modal:true,
        width:'auto',
        bigiframe:true,
        resizable:false,
        minHeight: 0,
        minWidth: 150,
        position:'center'
    };
    instance.objectCode = 'dialog_'+Math.random().toString().substr(3);
    var dialog = null;

    function _init(){
        instance.inherit(NT.Core.events);
        instance.ui = document.createElement('div');
        $(instance.ui).css('min-width', '200px');
        instance.ui.className = 'normosa-ui-dialog';
        instance.ui.title = instance.e.title;
        instance.content = document.createElement('div');
        instance.content.id = 'dialog_'+Math.random().toString().substr(3);
        instance.content.className = 'content';
        if(instance.e.content != null)
            instance.content.innerHTML = instance.e.content;
        instance.ui.appendChild(instance.content);
        document.body.appendChild(instance.ui);
        dialog = $(instance.ui).dialog(dialogOpts);

        instance.inherit(NT.Core.ui);
    }

    instance.request = function(){
        instance.animate();
        $.ajax({
            type:'GET',
            url:instance.e.url,
            success:function(data){
                instance.content.innerHTML = data;
                instance.parent.registerComponents(instance.content.id);
                instance.open();
                instance.allowExit = true;
            }
        });
    }
    
    instance.setContent = function(data){
        instance.content.innerHTML = data;
        instance.parent.registerComponents(instance.content.id);
        instance.open();
    }

    instance.close = function(){
        try{
            instance.disposeComponents(instance.content.id);
            dialog.dialog('destroy');
            instance.dispose();
            document.body.removeChild(instance.ui);
        }
        catch(e){}
    }

    instance.open = function(){
        dialog.dialog(dialogOpts);
    }

    instance.animate = function(){
        instance.disposeComponents(instance.content.id);
        var img = document.createElement('img');
        img.src = _base+'/loading.gif';
        $(instance.content).empty().append(img);
        instance.open();
        instance.allowExit = false;
    }

    instance.inherit(NT.Core.base);

    if(instance.e.url != null)
        instance.request();

    return instance;
}
