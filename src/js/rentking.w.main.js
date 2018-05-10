$(function() {
	var $body = $('body');

	var $visualArea = $body.find('.visual-area');
	var $visualData = $visualArea.find('.data');
	var $visualImage = $visualArea.find('.visual-image');
	var visualImage = {
		loaded: false,
		width: 0,
		height: 0,
		ratio: 0
	};

	var $serviceArea = $body.find('.service-area');
	var $eventArea = $body.find('.event-area');

	$visualImage.on('load', function() {
		visualImage.loaded = true;
		visualImage.height = $(this).outerHeight();
		visualImage.width = $(this).outerWidth();
		visualImage.ratio = visualImage.width / visualImage.height
	});

	$(document)
		.on('mouseenter', '.service-area li', function() {
			var $item = $(this);
			var timeout = $serviceArea.data('timeout');
			if(timeout)
				clearTimeout(timeout);

			// $serviceArea.data('timeout', setTimeout(function() {
				$item.addClass('active').siblings().removeClass('active');
				$item.parent().addClass('active');
			// }, 150));
		})
		.on('mouseleave', '.service-area', function() {
			var timeout = $serviceArea.data('timeout');
			if(timeout)
				clearTimeout(timeout);

			// $serviceArea.data('timeout', setTimeout(function() {
				$serviceArea.find('ul').removeClass('active').find('li').removeClass('active');
			// }, 150));


		});

	var swiper = new Swiper('.event-area', {
		pagination: '.swiper-pagination',
		nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev',
		slidesPerView: 1,
		loop: true,
		onSlideChangeStart: function(swiper) {
			$eventArea.removeClass('page-0 page-1').addClass('page-' + swiper.activeIndex % 2);
		}
	});

	$(document)
		.on('show', '.visual-area', function(e) {
			e.stopPropagation();

			$body.hideLoading();
			$visualArea.addClass('load');
			setTimeout(function() {
				$visualArea.find('.numberAnimation').trigger('animate');
				// $visualArea.trigger('tour');
			}, 500);
		})
		.on('tour', '.visual-area', function(e) {
			var tour = new Shepherd.Tour({
				defaults: {
					classes: 'shepherd-theme-arrows',
					scrollTo: true
				}
			});

			tour.addStep('tour-pickup', {
				title: '픽업일시 선택',
				text: '대여를 시작할 픽업날짜와 시간은 선택합니다.',
				attachTo: '#searchBar li.sdate',
				classes: 'tour-pickup',
				buttons: [
					{
						text: '다음',
						action: tour.next
					}
				]
			});

			tour.start();
		})
		.on('resize', '.visual-area', function(e) {
			e.stopPropagation();

			var timeout = $visualArea.data('timeout');
			if(typeof timeout !== 'undefined') {
				clearTimeout(timeout);
			}

			var resize = function() {
				if(visualImage.loaded) {
					var winWidth = window.innerWidth;
					var winHeight = window.innerHeight;
					if(winWidth < 940) winWidth = 940;
					if(winHeight < 700)	winHeight = 700;

					var winRatio = winWidth / winHeight;
					var toWidth = 0;
					var toHeight = 0;

					var visualImageCss = {};
					if(winRatio > visualImage.ratio) {
						toWidth = winWidth;
						toHeight = visualImage.height * toWidth / visualImage.width;
						visualImageCss = {left: 0, top: (winHeight - toHeight) / 2};
					} else {
						toHeight = winHeight;
						toWidth = visualImage.width * toHeight / visualImage.height;
						visualImageCss = {top: 0, left: (winWidth - toWidth) / 2};
					}
					$.extend(visualImageCss, {width: toWidth, height: toHeight});
					$visualImage.css(visualImageCss);

					$visualArea.css({height: winHeight});
					// $visualDescription.css({marginTop: -(winHeight - 90) / 4});
					// $visualData.css({marginTop:(winHeight-90)/4});

					if(!$visualArea.data('init')) {
						$visualArea.data('init', true).trigger('show');
					}

				} else {
					$visualArea.data('timeout', setTimeout(resize, 200));
				}
			};
			$visualArea.data('timeout', setTimeout(resize, 200));
		})
        .on('click', '#appLink', function(e) {
            var $cp = $('#cp');
            var $btn = $(this);
            e.stopPropagation();

            if ($cp.val() == '') {
                alert('휴대폰번호를 입력하세요.');
                return;
            }

            if(!$btn.data('process')) {
                $btn.data('process', true)
                    .text('발송중...');

                var param = "cp=" + $cp.val();

                $.getJSON('/mo/proajax.php?modtype=mem_common&han=applink&' + param, function (result) {
                    if (result['res'] == 'ok') {
                        $btn.text('발송 완료');
                    } else {
                        alert(result['msg']);
                        $btn.data('process', false)
                            .text('앱링크 발송');
                    }
                });
            }

        });

	$visualImage.attr('src', $visualImage.data('src'));

	$(window)
		.on('resize', function() {
			$visualArea.trigger('resize');
		})
		.on('scroll', function() {
			var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
			var position = -scrollTop / 3;
			var blur = scrollTop / 60;
			if(blur > 10)
				blur = 10;
			$visualImage.css({transform: 'translate3d(0, '+ position + 'px, 0)', filter: 'blur(' + blur + 'px)'});
		}).trigger('resize');
});
