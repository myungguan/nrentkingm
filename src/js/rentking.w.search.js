/**
 * Created by Sanggoo.
 * Date: 2017-04-10
 */



$(function() {
	var $searchWrap = $('#searchWrap');
	var $searchMap = $('#searchMap');
	var $searchForm = $searchWrap.find('#searchForm');
	var $mapWrap = $searchWrap.find('.map-wrap');
	var $mapPointWrap = $mapWrap.find('.map-point-wrap');
	var $listContentWrap = $('.list-content-wrap');
	var $listScrollInner = $listContentWrap.find('.list-scroll-inner');
	var $listScrollContent = $listScrollInner.find('.list-scroll-content');

	var resizeContentWrap = function() {
		var headerHeight = $('.header-wrap').outerHeight();
		var height = window.innerHeight - headerHeight;
		if (height < 400)
			height = 400;
		$searchWrap.css({height: height});
		$('body').addClass('resized');

		if (searchMap.map && searchMap.center) {
			clearTimeout(searchMap.timeout);
			searchMap.timeout = setTimeout(function () {
				searchMap.map.panTo(searchMap.center);
			}, 300);
		}
	};

	resizeContentWrap();
	$(window).on('resize', function() {
		resizeContentWrap();
	});

	$(document)
		.on('setRentshopMarker', '#searchWrap', function(e, param) {
			e.stopPropagation();

			var rentshop = param.rentshop;

			if(searchMap.rentshopMarker) {
				for(i = 0; i < searchMap.rentshopMarker.length; i++) {
					searchMap.rentshopMarker[i].setMap(null);
				}
			}
			searchMap.rentshopMarker = null;
			searchMap.bounds = new daum.maps.LatLngBounds();

			if(searchMap.searchMarker || searchMap.pickupMarker || searchMap.pickupReturnMarker) {
				searchMap.bounds.extend((searchMap.searchMarker || searchMap.pickupMarker || searchMap.pickupReturnMarker).getPosition());
			}

			if(rentshop.length > 0) {
				searchMap.rentshopMarker = [];
				for(var i = 0; i < rentshop.length; i++) {
					var position = new daum.maps.LatLng(rentshop[i].lat, rentshop[i].lng);
					searchMap.bounds.extend(position);

					var addr1 = rentshop[i]['addr1'].split(' ');
					var content = '<div class="info">' +
							// '<div class="name">' + rentshop[i]['name'] + '(' + rentshop[i]['affiliate']+ ')</div>' +
							// '<div class="address">' + rentshop[i]['addr1'] + ' ' + rentshop[i]['addr2'] + '</div>' +
							'<div class="name">' + rentshop[i]['affiliate'] + '</div>' +
							'<div class="address">' + addr1.slice(0, addr1.length > 3 && (addr1[1] == '고양시' || addr1[1] == '성남시') ? 4 : 3).join(' ') + '</div>' +
							'<div class="time">' +
								'<div class="title">운영시간</div>' +
								'<div class="data">평일(' + rentshop[i]['s1time1'] + ' ~ ' + rentshop[i]['s1time2'] + ')<br />주말/공휴일(' + rentshop[i]['s2time1'] + ' ~ ' + rentshop[i]['s2time2'] + ')</div>' +
							'</div>' +
							'<div class="cars">' +
								'<div class="title">운영차량 <span class="description">(이용가능/전체차량)</span></div>' +
								'<div class="data">' + rentshop[i].availcar + '대/' + rentshop[i].totalcar + '대</div>' +
							'</div>' +
						'</div>';
					var marker = '<div class="marker rentshop-info" id="rentshop' + rentshop[i]['idx'] + '">' + content + '</div>';
					var customOverlay = new daum.maps.CustomOverlay({
						position: position,
						content: marker,
						yAnchor: 1
					});

					customOverlay['idx'] = rentshop[i]['idx'];

					customOverlay.setMap(searchMap.map);
					searchMap.rentshopMarker.push(customOverlay);
				}
			}
			searchMap.map.setBounds(searchMap.bounds);
		})
		.on('mouseenter', '#searchMap .marker.rentshop-info', function() {
			var $marker = $(this);
			var $parent = $marker.parent();
			var originalZIndex = $parent.css('z-index');
			$parent.data('originalZIndex', originalZIndex);

			var zIndex = 100;
			if($marker.hasClass('on'))
				zIndex = 50;

			$parent.css({zIndex: zIndex});
		})
		.on('mouseleave', '#searchMap .marker.rentshop-info', function() {
			var $parent = $(this).parent();
			var originalZIndex = $parent.data('originalZIndex');
			$parent.css({zIndex: originalZIndex});
		})
		.on('click', '.map-wrap .go-list', function(e) {
			e.preventDefault();

			$searchWrap.addClass('list').removeClass('re-search');
			if($searchWrap.data('detail')) {
				$searchWrap.data('detail', false);
			}

			$listScrollContent.find('li.item').removeClass('on');
			setTimeout(function() {
				searchMap.map.relayout();
				searchMap.map.panTo(searchMap.center);
			}, 400);
		})
		.on('init', '.list-content-wrap .list-scroll .page', function(e) {
			e.stopPropagation();

			var $page = $(this);
			var searchWrapOffset = $searchWrap.offset();
			$page.find('li.item').each(function() {
				var $li = $(this);
				var offset = $li.offset();

				window.listItemOffset.push({
					$item: $li,
					offset: offset.top - searchWrapOffset.top + scrollTarget.scrollTop()
				});
			});

			$page.addClass('init');
			scrollTarget.trigger('scroll');
		})
		.on('goRentshop', '.list-content-wrap .list-scroll .page li.item', function(e) {
			e.stopPropagation();

			var $item = $(this);
			var rentshop = $item.data('rentshop');

			var go = function() {
				if(!$searchMap.find('#rentshop' + rentshop).hasClass('on')) {
					$searchMap.find('.marker.rentshop-info').removeClass('on').trigger('mouseleave');

					for(var i in searchMap.rentshopMarker) {
						if(searchMap.rentshopMarker[i].idx == rentshop) {
							var bounds = new daum.maps.LatLngBounds();
							bounds.extend(searchMap.rentshopMarker[i].getPosition());
							if(searchMap.searchMarker || searchMap.pickupMarker || searchMap.pickupReturnMarker) {
								bounds.extend((searchMap.searchMarker || searchMap.pickupMarker || searchMap.pickupReturnMarker).getPosition());
							}
							// bounds.extend(searchMap.searchMarker.getPosition());
							searchMap.map.setBounds(bounds);
							searchMap.map.setLevel(searchMap.map.getLevel()+1);
							searchMap.center = searchMap.map.getCenter();
						}
					}

					$searchMap.find('#rentshop' + rentshop).addClass('on').trigger('mouseenter');
				}
			};

			var back = function() {
				if($searchMap.find('#rentshop' + rentshop).hasClass('on')) {
					$searchMap.find('.marker.rentshop-info').removeClass('on').trigger('mouseleave');
				}
			};

			if($item.hasClass('on')) {
				if(!$searchWrap.hasClass('detail') || $searchWrap.hasClass('filter')) {
					$searchWrap.addClass('detail').data('detail', true);
					clearTimeout(searchMap.timeout);
					searchMap.timeout = setTimeout(function() {
						searchMap.map.relayout();
						go();
					}, 300);
				} else {
					go();
				}
			} else {
				back();
			}

		})
		.on('click', '.list-search-wrap button.option, .list-content-wrap .grade-info button.option', function() {
			$searchForm.trigger('resetList').submit();
		})
		.on('click', '.list-search-wrap input[name="nosmoke"]', function() {
			$searchForm.trigger('resetList').submit();
		})

	;

	runLocationTask();
});