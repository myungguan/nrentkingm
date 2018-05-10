/**
 * Created by Sanggoo.
 * Date: 2017-04-25
 */

$(function() {
	window.isMobile = true;
	var $window = $(window);
	var isMain = $('html').hasClass('main');
	var $header = $('header');
	var $body = $('body');

	$(document)
		.on('click', 'header .searchbar-wrap .info button', function (e) {
			$header.toggleClass('open');
		})
		.on('setDate', 'header', function(e) {
			var $sdate = $('#sdate');
			var $edate = $('#edate');
			var sdate = moment($sdate.val());
			var edate = moment($edate.val());
			var $infoSDate = $header.find('.searchbar-wrap .info .sdate');
			var $infoEDate = $header.find('.searchbar-wrap .info .edate');
			var $infoLength = $header.find('.searchbar-wrap .info .length');
			var lengthText = '';

			$infoSDate.text(sdate.format('YYYY-MM-DD HH:mm'));
			$infoEDate.text(edate.format('YYYY-MM-DD HH:mm'));

			var months = Math.floor(-sdate.diff(edate, 'M', true));
			if(months > 0) {
				var days = Math.ceil(-sdate.clone().add(months, 'M').diff(edate.clone(), 'd', true));

				lengthText = '[ ' + months + '개월';
				if(days > 0) {
					lengthText += ' ' + days + '일';
				}
				lengthText += ' ]';
				$infoLength.text(lengthText);
			} else {
				var days = Math.floor(-sdate.diff(edate, 'd', true));
				var hours = -sdate.clone().add(days, 'd').diff(edate, 'H', true);

				lengthText = '[ ' + days + '일';
				if(hours > 0) {
					lengthText += ' ' + hours + '시간';
				}
				lengthText += ' ]';
				$infoLength.text(lengthText);
			}
		})
		.on('dateChanged', '#sdate', function(e) {
			$header.trigger('setDate');
		})
		.on('dateChanged', '#edate', function(e) {
			$header.trigger('setDate');
		})
		.on('click', '.go-top', function(e) {
			var scrollTop = $(window).scrollTop();
			var time = scrollTop / 800 * 800;
			if(time > 800)
				time = 800;
			$('html, body').animate({'scrollTop':0}, time);
		})
	;

	$header.trigger('setDate');

	var headerHeight = $header.outerHeight();
	var scrollStartPosition = -1;
	$window.on('touchstart', function(e) {
		scrollStartPosition = document.documentElement.scrollTop || document.body.scrollTop;
	});

	$window.on('touchend', function(e) {
		scrollStartPosition = document.documentElement.scrollTop || document.body.scrollTop;
	});

	//메인 헤더
	$window.on('scroll', function(e) {
		var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
		if(isMain) {
			if(scrollTop > 0) {
				$header.addClass('mask');
			} else {
				$header.removeClass('mask');
			}
		} else {
			if(scrollStartPosition > -1) {
				var move = scrollTop - scrollStartPosition;
				if(move > 10 && scrollTop > headerHeight) {
					scrollStartPosition = scrollTop;
					$header.removeClass('open');
					$body.addClass('header-hide');
				} else if(move < -10) {
					scrollStartPosition = scrollTop;
					$body.removeClass('header-hide');
				}
			}

			if(scrollTop < 40) {
				$body.removeClass('show-go-top');
			} else {
				if(move > 10) {
					$body.removeClass('show-go-top');
				} else if(move < -10){
					$body.addClass('show-go-top');
				}
			}
		}
	});
});