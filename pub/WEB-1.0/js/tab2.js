$(function(){
    $(".svWrap>.tabTh>li").on("click",function(e){
        e.preventDefault();
        var target= $(this).children("a").attr("href");
        $(".svWrap>div").hide();
        $(target).show();
    });
});

$(function(){
    var tabMn=$(".svWrap>.tabTh>li");
    var tabCon=$(".svWrap>div");
    tabMn.click(function(){
        tabMn.removeClass("tbOn");
        $(this).addClass("tbOn");
    });
});

