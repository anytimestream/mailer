$(document).ready(function(){
    var news = $('.news dd');
    var panel = $('#header .bottom .wrapper .left');
    var index = 1;
    setInterval(function(){
        panel.animate({'scrollTop': (index * 40) + 'px'});
        index++;
        if(index > news.length){
            index = 0;
        }
    }, 4000);
});