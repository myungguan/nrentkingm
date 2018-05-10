/**
 * Created by Sanggoo.
 * Date: 2017-04-26
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

	$(window).on('resize', function() {
		if (searchMap.map && searchMap.center) {
			clearTimeout(searchMap.timeout);
			searchMap.timeout = setTimeout(function () {
				searchMap.map.panTo(searchMap.center);
			}, 300);
		}
	});

	$(document)
		.on('click', '.map-wrap .go-list', function(e) {
			e.preventDefault();

			if(searchMap.rentshopMarker) {
				searchMap.rentshopMarker.setMap(null);
				searchMap.rentshopMarker = null;
			}

			$searchWrap.addClass('list').removeClass('re-search rentshop-position');
			$('header').removeClass('no-shadow');
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
			var scrollTop =  scrollTarget.scrollTop();
			$page.find('li.item').each(function() {
				var $li = $(this);
				var offset = $li.offset();

				window.listItemOffset.push({
					$item: $li,
					offset: offset.top - searchWrapOffset.top
				});
			});

			$page.addClass('init');
			scrollTarget.trigger('scroll');
		})
		.on('click', '.list-content-wrap .list-scroll .page li.item button.show-location', function(e) {
			e.stopPropagation();

			$(this).parent().trigger('goRentshop');
		})
		.on('goRentshop', '.list-content-wrap .list-scroll .page li.item', function(e) {
			e.stopPropagation();

			var $item = $(this);
			var rentshop = $item.data('rentshop');

			$searchWrap.removeClass('list detail').addClass('rentshop-position');
			$('header').removeClass('no-shadow');
			$('body').removeClass('header-hide show-go-top');

			if(searchMap.rentshopMarker) {
				searchMap.rentshopMarker.setMap(null);
				searchMap.rentshopMarker = null;
			}


			var rentshopDetail = $searchWrap.data('rentshopDetail');
			for(var i = 0; i < rentshopDetail.length; i++) {
				if(rentshopDetail[i]['idx'] == rentshop) {
					var position = new daum.maps.LatLng(rentshopDetail[i].lat, rentshopDetail[i].lng);

					var addr1 = rentshopDetail[i]['addr1'].split(' ');
					var content = '<div class="info">' +
						// '<div class="name">' + rentshopDetail[i]['name'] + '(' + rentshopDetail[i]['affiliate']+ ')</div>' +
						// '<div class="address">' + rentshopDetail[i]['addr1'] + ' ' + rentshop[i]['addr2'] + '</div>' +
						'<div class="name">' + rentshopDetail[i]['affiliate'] + '</div>' +
						'<div class="address">' + addr1.slice(0, addr1.length > 3 && (addr1[1] == '고양시' || addr1[1] == '성남시') ? 4 : 3).join(' ') + '</div>' +
						'<div class="time">' +
						'<div class="title">운영시간</div>' +
						'<div class="data">평일(' + rentshopDetail[i]['s1time1'] + ' ~ ' + rentshopDetail[i]['s1time2'] + ')<br />주말/공휴일(' + rentshopDetail[i]['s2time1'] + ' ~ ' + rentshopDetail[i]['s2time2'] + ')</div>' +
						'</div>' +
						'<div class="cars">' +
						'<div class="title">운영차량 <span class="description">(이용가능/전체차량)</span></div>' +
						'<div class="data">' + rentshopDetail[i].availcar + '대/' + rentshopDetail[i].totalcar + '대</div>' +
						'</div>' +
						'</div>';
					var marker = '<div class="marker rentshop-info on" id="rentshop' + rentshopDetail[i]['idx'] + '">' + content + '</div>';
					var customOverlay = new daum.maps.CustomOverlay({
						position: position,
						content: marker,
						yAnchor: 1
					});
					customOverlay.setMap(searchMap.map);
					searchMap.rentshopMarker = customOverlay;

					setTimeout(function() {
						searchMap.map.relayout();
						searchMap.map.setCenter(position);
						searchMap.map.panBy(0, -120);

					}, 200);

				}
			}
		})
		.on('click', '.list-search-wrap .close-search', function() {
			$searchWrap.removeClass('filter');
			scrollTarget.scrollTop(0);
			clearTimeout(searchMap.timeout);
			searchMap.timeout = setTimeout(function() {
				searchMap.map.relayout();
				searchMap.map.setCenter(searchMap.center);
			}, 300);
		})
		.on('click', '.list-search-wrap .list-search-submit', function() {
			$searchWrap.removeClass('filter');
			scrollTarget.scrollTop(0);
			$searchForm.trigger('resetList').submit();
		})
		.on('click', '.list-content-wrap .grade-info button.option', function() {
			$searchForm.trigger('resetList').submit();
		})
	;


	$('header').addClass('no-shadow');

	runLocationTask();
});