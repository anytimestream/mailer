/*  History version 1.0.0
 *  Normosa Technologies JavaScript Framework
 *  (c) 2011 Normosa Technologies
 *  @autor Norman Osaruyi
 *
 *  For details contact js-support@normosa.com
 *
 *--------------------------------------------------------------------------*/

normosa.prototype.history = function(e){
    var  instance = this;
    instance.init = _init;
    instance.objectCode = 'history';
    instance.e = e;
    instance.inherit(NT.Core.base);

    instance.setUrl = function(url){
        if(window.history.pushState){
            window.history.pushState(null, '', url);
        }
        else {
            location.hash = url;
        }
    }

    function _init(){
        instance.inherit(NT.Core.events);
        instance.inherit(NT.Core.hashTable);
        instance.watchList = new NT.Core.hashTable();
        instance.cache = new NT.Core.hashTable();
        instance.iframe = document.getElementById('iframe_fix');
        instance.iframeContent = getIframeContent();
        instance.history = getHistory();
        instance.loading = document.createElement('img');
        //instance.loading.src = _base+'/loading.gif';
        instance.interval = null;
        instance.events.historychanged = new Array();
        instance.registerComponents(instance.ui.id);
        $(instance.iframe).load(on_load);
        monitorHistory();
    }

    instance.changeUrl = function(url){
        clearInterval(instance.interval);
        if(window.history.pushState){
            window.history.pushState(null, '', url);
        }
        else {
            location.hash = url;
        }

        instance.iframeContent = getIframeContent();
        instance.history = getHistory();
        monitorHistory();
    }

    function getHistory(){
        if(window.history.pushState)
            return location.toString();
        else return location.hash.substring(location.hash.indexOf('#') + 1,location.hash.length);
        return '';
    }

    function getIframeContent(){
        var doc = (instance.iframe.contentWindow || instance.iframe.contentDocument);
        if (doc.document) doc = doc.document;
        return doc.body.innerHTML;
    }

    function getIframeUrl(){
        var doc = (instance.iframe.contentWindow || instance.iframe.contentDocument);
        if (doc.document) doc = doc.document;
        return doc.location.search;
    }

    function load(url, id){
        instance.initialized = true;
        var doc = (instance.iframe.contentWindow || instance.iframe.contentDocument);
        instance.disposeComponents(id);
        $('#'+id).empty();
        if(instance.cache.get(url) != null){
            $('#'+id).append(instance.cache.get(url));
            instance.registerComponents(id);
            return;
        }
        else{
            if(_ie == true && _iev <= 7){
                doc.location = url+'&ajax='+id;
            }
            else{
                doc.location.replace(url+'?ajax='+id);
            }
        }
    }

    function on_load(){
        if(!instance.initialized)
            return;
        var id = instance.watchList.matchUrl(instance.history);
        if(id != null && document.getElementById(id) != null){
            $('#'+id).empty().append(getIframeContent());
            if(_ie == true && _iev <= 7){
                clearInterval(instance.interval);
                location.hash = stripAjax(getIframeUrl());
                instance.history = stripAjax(getIframeUrl());
                instance.trigger('historychanged', instance.history);
                monitorHistory();
            }
            if($('#'+id).attr('cache') != null){
                instance.cache.put(instance.history, document.getElementById(id).innerHTML);
            }
            instance.registerComponents(id);
        }
        else{
            location.search = instance.history;
        }
    }

    function normalizeUrl(url){
        if(url.substring(0, 1) == '?')
            return url.substring(1);
        return url;
    }

    function monitorHistory(){
        instance.interval = setInterval(function(){
            if(instance.history != getHistory()){
                instance.history = getHistory();
                var id = instance.watchList.matchUrl(instance.history);
                if(id == null || document.getElementById(id) == null){
                    location = instance.history;
                }
                else{
                    instance.trigger('historychanged', instance.history);
                    load(instance.history, id);
                }
            }
        },200);
    }

    function stripAjax(url){
        var pos = url.lastIndexOf('&ajax=');
        return url.substring(1, pos);
    }

    return instance;
}