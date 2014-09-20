/*  Datatable version 2.0.0
 *  Anytimestream JavaScript Framework
 *  (c) 2014 Anytimestream Technologies Limited
 *  @autor Norman Osaruyi
 *
 *  For details contact support@anytimestream.com
 *
 *--------------------------------------------------------------------------*/

normosa.prototype.datatable = function(e){
    var  instance = this;
    instance.init = _init;
    instance.parent = e.parent;
    instance.objectCode = instance.ui.id;
    instance.dialog = null;
    instance.newDialog = null;
    instance.working = false;
    instance.isEditing = false;
    instance.isSelected = false;
    instance.pageInfo = null;
    instance.forms = {};

    function _init(){
        instance.inherit(NT.Core.events);
        instance.events.onScrolling = new Array();
        instance.addEventListener('onScrolling', onScrolling);
        $('#'+ instance.ui.id+ ' div.content div.tr[contextmenu]').each(function(){
            var row = $(this);
            row.contextmenu({
                delegate: ".content",
                menu: eval(row.attr('contextmenu')),
                select: function(event, ui) {
                    var contextmenu = contextmenuurl[ui.cmd];
                    if(contextmenu.window == 'same'){
                        document.location = contextmenu.url+event.target.id;
                    }
                    else if(contextmenu.window == 'dialog'){
                        instance.newDialog = $n('').dialog({
                            title:contextmenu.title,
                            url:contextmenu.url+event.target.id,
                            parent:instance
                        });
                    }
                    else if(contextmenu.window == 'form'){
                        instance.newDialog = $n('').dialog({
                            title:contextmenu.title,
                            url:contextmenu.url+event.target.id,
                            parent:instance
                        });
                    }
                    else{
                        window.open(contextmenu.url+event.target.id, '_blank');
                    }
                }
            });
        });
        $('#'+ instance.ui.id+ ' div.pagination .goto').change(function(){
            var btn = $(this);
            document.location = btn.attr("url")+btn.val();
        });
        $('#'+ instance.ui.id+ ' div[class=toolbar] a[action=fullscreen]').click(function(e){
            e.preventDefault();
            instance.toggleFullScreen();
        });
        $('#'+ instance.ui.id+ ' div.scrollpane').scroll(function(){
            instance.trigger('onScrolling', null);
        });
        $('#'+ instance.ui.id+ ' div.tr[id]').click(function(){
            var row = $(this);
            $('#'+ instance.ui.id + ' div.tr[id]').each(function(){
                var inner_row = $(this);
                if(row.attr('id') != inner_row.attr('id') && inner_row.hasClass('selected')){
                    inner_row.removeClass('selected');
                }
            });
            if(row.hasClass('selected')){
                row.removeClass('selected');
            }
            else{
                row.addClass('selected');
            }  
        });
        $('#datatable_form').load(update_row);
    }
    
    function update_row(){
        instance.doWaiting(false, null);
        var data = $('#datatable_form').contents().find("body").html();
        if(!isError(data)){
            instance.newDialog.close();
            var div = document.createElement('div');
            div.innerHTML = data;
            var row = $($(div).children('div.tr')[0]);
            $('#'+ instance.ui.id + ' div.tr[id='+row.attr('id')+']').html(row.html());
        }
        else{
            instance.newDialog.setContent(data);
        }
    }
    
    instance.forms.submit = function(e){
        instance.doWaiting(true, "Saving...");
    }
    
    function isError(e){
        var div = document.createElement('div');
        div.innerHTML = e;
        if($(div).children('span.error').length > 0){
            return true;
        }
        return false;
    }
    
    instance.doWaiting = function(e, text){
        if(instance.dialog == null){
            instance.dialog = $n('').dialog({
                title:text
            });
        }
        if(e == true){
            instance.dialog.animate();
        }
        else{
            instance.dialog.close();
            instance.dialog = null;
        }
    }
    
    instance.toggleFullScreen = function(){
        var _ui = $(instance.ui);
        var _scroll = $('#'+instance.ui.id+' div.scrollpane');
        var _header = $('#'+instance.ui.id+' div.th');
        var _header_fix = $('#'+instance.ui.id+' div.th_fix');
        if(_ui.css('position') == 'fixed'){
            _ui.css('position','static');
            _header.css('position', 'static');
            _header_fix.hide();
            _scroll.css('height', _scroll.attr('dheight'));
        }
        else{
            _ui.css('right', '0');
            _ui.css('position', 'fixed');
            _header.css('position', 'fixed');
            _header.css('min-width', 'inherit');
            _header_fix.show();
            _ui.css('left', '0');
            _ui.css('top', '0');
            _ui.css('bottom', '0');
            _scroll.attr('dheight', _scroll.css('height'));
            _scroll.css('width', '100%');
            _scroll.css('height', $(window).height() - $('#'+instance.ui.id+' div.toolbar').height());
            instance.trigger('onScrolling', null);
        }
    }
    
    function onScrolling(){
        var scroll = $('#'+ instance.ui.id+ ' div.scrollpane')[0];
        var header = $('#'+ instance.ui.id+ ' div.th');
        if(header.css('position') == 'fixed'){
            $('#'+ instance.ui.id+ ' div.th').css('left','-'+$(scroll).scrollLeft()+'px');
        }
    }

    instance.inherit(NT.Core.base);

    return instance;
}