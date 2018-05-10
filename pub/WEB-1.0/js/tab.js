$(function(){
    $(".eTabWrap>.tabTh>li").on("click",function(e){
        e.preventDefault();
        var target= $(this).children("a").attr("href");
        $(".eTabWrap>div").hide();
        $(target).show();
    });
});

$(function(){
    var tabMn=$(".eTabWrap>.tabTh>li");
    var tabCon=$(".eTabWrap>div");
    tabMn.click(function(){
        tabMn.removeClass("tbOn");
        $(this).addClass("tbOn");
    });
});

