$(function() {
	$(".arctic_scroll").arctic_scroll({
		speed: 800
	});

	$(".close a").click(function(e){
        e.preventDefault();
		$('body').addClass('closeEvent');

		$('.arctic_scroll[data-offset=-210]').attr('data-offset', -80);
	});

	$(".busLie a").click(function(){
		$(".busLieIMG").show();
		$(".fI>div>span>img").click(function(){
			$(".fI>div").hide();
		});
	});
	$(".mod a").click(function(){
		$(".modIMG").show();
		$(".fI>div>span>img").click(function(){
			$(".fI>div").hide();
		});
	});
	$(".ventu a").click(function(){
		$(".ventuIMG").show();
		$(".fI>div>span>img").click(function(){
			$(".fI>div").hide();
		});
	});

	$("#list h3 a").click(function(e){
		e.preventDefault();
		var $h3 = $(this).parents('h3');
		var $content = $h3.next('div');
		$content.slideToggle("fast").siblings("div:visible").slideUp("fast");
		$h3.toggleClass('active').siblings('h3').removeClass('active');
	});
});
