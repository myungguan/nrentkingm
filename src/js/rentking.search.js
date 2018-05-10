/**
 * Created by Sanggoo on 2017-06-29.
 */

$(function() {
	var $searchWrap = $('#searchWrap');
	var $searchMap = $('#searchMap');
	var $searchForm = $searchWrap.find('#searchForm');
	var $reservationForm = $searchWrap.find('#reservationForm');
	var $mapWrap = $searchWrap.find('.map-wrap');
	var $mapSearchWrap = $mapWrap.find('.map-search-wrap');
	var $mapPointWrap = $mapWrap.find('.map-point-wrap');
	var $listSearchWrap = $('.list-search-wrap');
	var $listContentWrap = $('.list-content-wrap');
	var $listScrollInner = $listContentWrap.find('.list-scroll-inner');
	var $listScrollContent = $listScrollInner.find('.list-scroll-content');
	window.scrollTarget = isMobile ? $(window) : $listScrollInner;

	var geocoder = new daum.maps['services']['Geocoder']();
	var ps = new daum.maps['services']['Places']();

	window.searchMap = {
		dom: $searchMap[0],
		map: null,
		center: null,
		bounds: null,
		timeout: null,
		pickupMarker: null,
		returnMarker: null,
		pickupReturnMarker: null,
		searchMarker: null,
		rentshopMarker: null
	};

	window.listItemOffset = [];

	var markerCount = 0;

	$(document)
		.on('click', '.panToPosition', function(e) {
			e.preventDefault(0);

			var $a = $(this);
			var lat = $a.data('lat');
			var lng = $a.data('lng');

			$mapPointWrap.removeClass('on');
			searchMap.center = new daum.maps['LatLng'](lat, lng);
			searchMap.map['panTo'](searchMap.center);
		})
		.on('resetList', '#searchForm', function(e) {
			e.stopPropagation();

			$searchWrap.data('detail', false);
			$searchWrap.removeClass('filter');
			scrollTarget.scrollTop(0);
			$searchForm.data('finished', false);
			$listScrollContent.removeClass('finished');

			$searchForm.find('input[name="totalItem"]').val(20);
			$searchForm.find('input[name="currentItem"]').val(0);
			$searchForm.find('input[name="itemTo"]').val(19);
		})
		.on('submit', '#searchForm', function(e) {
			e.preventDefault();

			if(!$searchForm.data('finished') && !$searchForm.data('running')) {
				var showLoading = false;
				$searchForm.data('running', true);

				var $totalItem = $searchForm.find('input[name="totalItem"]');
				var $currentItem = $searchForm.find('input[name="currentItem"]');
				var $itemTo = $searchForm.find('input[name="itemTo"]');

				var totalItem = parseInt($totalItem.val());
				var currentItem = parseInt($currentItem.val());
				var itemTo = parseInt($itemTo.val());

				if(itemTo >= totalItem) {
					itemTo = totalItem - 1;
					$itemTo.val(itemTo);
				}
				if(currentItem == 0) {
					showLoading = true;
					$listContentWrap.showLoading();

					var searchCount = parseInt($searchWrap.find('input[name="searchCount"]').val());
					$searchWrap.find('input[name="searchCount"]').val(searchCount+1);
				}

				$searchForm.find('input[name="colorQuery"]').val($.trim($listSearchWrap.find('input[name="color"]').val()));
				$searchForm.find('input[name="companyQuery"]').val($.trim($listSearchWrap.find('input[name="company"]').val()));
				$searchForm.find('input[name="modelQuery"]').val($.trim($listSearchWrap.find('input[name="model"]').val()));


				$.get('/rent/search_list.php', $searchForm.serializeArray())
					.done(function(html) {
						if(currentItem == 0) {
							window.listItemOffset = [];
							$listScrollContent.find('.page').remove();

							var $distanceInfo = $listContentWrap.find('.distance-info');
						}

						$listScrollContent.find('.loading').before(html);
						$listScrollContent.find('.page:not(.init)').trigger('init');
						$listScrollContent.parents('.scrollWrap').trigger('resizeScrollBar');
						totalItem = parseInt($totalItem.val());
						if(currentItem == 0) {
							$distanceInfo.find('.info').html('검색업체: <span class="num">' + $searchWrap.data('rentshopDetailLength').numberFormat(0) + '</span>, 예약가능 차량: <span class="num">' + totalItem.numberFormat(0) + '</span>');
						}

						currentItem += 20;
						itemTo += 20;

						if(currentItem >= totalItem) {
							$searchForm.data('finished', true);
							$listScrollContent.addClass('finished');
						}

						$currentItem.val(currentItem);
						$itemTo.val(itemTo);

						if(totalItem < 1)
							$listScrollContent.addClass('no-item');
						else
							$listScrollContent.removeClass('no-item');
					}).complete(function() {
					if(showLoading)
						$listContentWrap.hideLoading();
					$searchForm.data('running', false);
				});
			}
		})
		.on('reset', '#searchForm', function(e) {
			e.stopPropagation();

			//대여방법
			$searchForm.find('input[name="ptype"]').val('');

			//자차보험
			$searchForm.find('input[name="insu"]').val('');

			//모델
			$searchForm.find('input[name="modelQuery"]').val('');
			$searchForm.find('input[name="model"]').val('');

			//연료
			$searchForm.find('input[name="fuel"]').val('');

			//차량옵션
			$searchForm.find('input[name="option"]').val('');

			//출고일
			$searchForm.find('input[name="outdate"]').val('');

			//차량색상
			$searchForm.find('input[name="colorQuery"]').val('');
			$searchForm.find('input[name="color"]').val('');

			//제조사
			$searchForm.find('input[name="companyQuery"]').val('');
			$searchForm.find('input[name="company"]').val('');

			//금연차
			$searchForm.find('input[name="nosmoke"]').val('N');

			$searchForm.trigger('resetList');

			$searchForm.submit();
		})
		.on('setListLayout', '#searchWrap', function(e) {
			e.stopPropagation();

			$searchWrap.addClass('list').removeClass('re-search');
			$('header').removeClass('no-shadow');
			setTimeout(function() {
				searchMap.map['relayout']();
				if(searchMap.bounds) {
					searchMap.map.setBounds(searchMap.bounds);
				} else {
					searchMap.map['panTo'](searchMap.center);
				}
			}, 400);
		})
		.on('setAddress', '#searchWrap', function(e, param) {
			var $pickupAddr = $('#pickupAddr');
			var $returnAddr = $('#returnAddr');
			var $searchAddr = $('#searchAddr');
			var type = '';
			var deliveryComment = '지도를 움직여 픽업/반납 위치를 선택해 주세요';
			var removeMarker = null;
			var markerText = '';
			var marker = null;
			var customOverlay = null;
			var $marker = null;

			if(typeof param === 'undefined') {
				return false;
			}

			if(typeof param['search'] !== 'undefined') {
				$searchForm.find('input[name="searchLatLng"]').val(param.coord['getLat']()+','+param.coord['getLng']());
				if(searchMap.searchMarker) {
					$('#marker' + searchMap.searchMarker.idx).addClass('off');
					removeMarker = searchMap.searchMarker;
					setTimeout(function() {
						removeMarker['setMap'](null);
					}, 300);
					searchMap.searchMarker = null;
				}

				markerText = '검색위치';
				$searchAddr.html('<a href="#panToPosition" class="panToPosition" data-lat="' + param.coord['getLat']() + '" data-lng="' + param.coord['getLng']() + '">' + param['search'] + '</a>');

				marker = '<div class="marker" id="marker'+(++markerCount)+'"><div class="tooltip top"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + markerText + '</div></div></div>';
				customOverlay = new daum.maps['CustomOverlay']({
					position: param.coord,
					content: marker,
					yAnchor: 1
				});
				customOverlay['idx'] = markerCount;
				searchMap['searchMarker'] = customOverlay;
				customOverlay['setMap'](searchMap.map);

				$marker = $('#marker'+markerCount);
				setTimeout(function() {
					$marker.addClass('on');
				}, 100);

				$searchForm.find('input[name="distance"]').val(-1);

				$searchForm.find('input[name="pickupAddr"]').val(param['search']);
				$searchForm.find('input[name="returnAddr"]').val(param['search']);
				$listContentWrap.find('.delivery-info .search-addr').text(param['search']);

				$searchWrap.trigger('search');
			} else {
				if(typeof param['pickup'] !== 'undefined' || typeof param['pickupReturn'] !== 'undefined') {
					$searchForm.find('input[name="searchLatLng"]').val(param.coord['getLat']()+','+param.coord['getLng']());
					if(searchMap.pickupMarker) {
						$('#marker' + searchMap.pickupMarker.idx).addClass('off');
						removeMarker = searchMap.pickupMarker;
						setTimeout(function() {
							removeMarker['setMap'](null);
						}, 300);
						searchMap.pickupMarker = null;
					}
				}

				if(typeof param['return'] !== 'undefined' || typeof param['pickupReturn'] !== 'undefined') {
					if(searchMap.returnMarker) {
						$('#marker' + searchMap.returnMarker.idx).addClass('off');
						removeMarker = searchMap.returnMarker;
						setTimeout(function() {
							removeMarker['setMap'](null);
						}, 300);
						searchMap.returnMarker = null;
					}
				}

				if(searchMap.pickupReturnMarker) {
					$('#marker' + searchMap.pickupReturnMarker.idx).addClass('off');
					removeMarker = searchMap.pickupReturnMarker;
					setTimeout(function() {
						removeMarker['setMap'](null);
					}, 300);
					searchMap.pickupReturnMarker = null;

					if(typeof param['pickup'] !== 'undefined') {
						$returnAddr.text(deliveryComment);
						$searchWrap.data('return', false).data('returnAddr', '');
					}

					if(typeof param['return'] !== 'undefined') {
						$pickupAddr.text(deliveryComment);
						$searchWrap.data('pickup', false).data('pickupAddr', '');
					}
				}

				if(typeof param['pickup'] !== 'undefined') {
					markerText = '픽업위치';
					$pickupAddr.html('<a href="#panToPosition" class="panToPosition" data-lat="' + param.coord['getLat']() + '" data-lng="' + param.coord['getLng']() + '">' + param['pickup'] + '</a>');
					type = 'pickupMarker';
					$searchWrap.data('pickup', true).data('pickupAddr', param['pickup']);
				}

				if(typeof param['return'] !== 'undefined') {
					markerText = '반납위치';
					$returnAddr.html('<a href="#panToPosition" class="panToPosition" data-lat="' + param.coord['getLat']() + '" data-lng="' + param.coord['getLng']() + '">' + param['return'] + '</a>');
					type = 'returnMarker';
					$searchWrap.data('return', true).data('returnAddr', param['return'])
				}

				if(typeof param['pickupReturn'] !== 'undefined') {
					markerText = '검색위치';
					$pickupAddr.html('<a href="#panToPosition" class="panToPosition" data-lat="' + param.coord['getLat']() + '" data-lng="' + param.coord['getLng']() + '">' + param['pickupReturn'] + '</a>');
					$returnAddr.html('<a href="#panToPosition" class="panToPosition" data-lat="' + param.coord['getLat']() + '" data-lng="' + param.coord['getLng']() + '">' + param['pickupReturn'] + '</a>');
					type = 'pickupReturnMarker';
					$searchWrap.data('pickup', true).data('pickupAddr', param['pickupReturn']);
					$searchWrap.data('return', true).data('returnAddr', param['pickupReturn']);
				}

				marker = '<div class="marker" id="marker'+(++markerCount)+'"><div class="tooltip top"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + markerText + '</div></div></div>';
				customOverlay = new daum.maps['CustomOverlay']({
					position: param.coord,
					content: marker,
					yAnchor: 1
				});
				customOverlay['idx'] = markerCount;
				searchMap[type] = customOverlay;
				customOverlay['setMap'](searchMap.map);

				$marker = $('#marker'+ markerCount);
				setTimeout(function() {
					$marker.addClass('on');
				}, 100);

				if($searchWrap.data('return') && $searchWrap.data('pickup')) {
					var pickupAddr = $searchWrap.data('pickupAddr');
					var returnAddr = $searchWrap.data('returnAddr');
					$searchForm.find('input[name="pickupAddr"]').val(pickupAddr);
					$searchForm.find('input[name="returnAddr"]').val(returnAddr);
					$searchForm.find('input[name="distance"]').val(-1);

					$listContentWrap.find('.delivery-info .pickup-addr').text(pickupAddr);
					$listContentWrap.find('.delivery-info .return-addr').text(returnAddr);

					$searchWrap.data('pickup', false).data('return', false);

					$searchWrap.trigger('search');
				}
			}
		})
		.on('search', '#searchWrap', function(e) {
			e.stopPropagation();

			$searchForm.trigger('resetList');
			$.getJSON('/rent/search_rentshop.php', $searchForm.serializeArray())
				.done(function(data) {
					if(!$searchWrap.hasClass('list')) {
						$searchWrap.trigger('setListLayout');
					}

					$searchForm.find('input[name="rentshopList"]').val(data.rentshopList);
					var distanceList = data['distanceList'];
					var distance = data.distance;
					var $distanceInfo = $listContentWrap.find('.distance-info');
					var rentshopDetail = data['rentshopDetail'];

					$distanceInfo.find('button').remove();
					if(distanceList) {
						for(var i = 0; i < distanceList.length; i++) {
							var on = '';
							if(distance == distanceList[i]) {
								on = 'on';
							}
							$distanceInfo.append('<button class="' + on +'" data-distance="' + i + '">'+(distanceList[i]/1000).numberFormat(0)+'km</button>');
						}
					}
					$searchWrap.data('rentshopDetailLength', rentshopDetail.length);
					$searchWrap.data('rentshopDetail', rentshopDetail).trigger('setRentshopMarker', {rentshop: rentshopDetail});
					$searchForm.find('input[name="distanceM"]').val(distance);
					$searchForm.submit();

				}).complete(function() {});
		})
		.on('submit', '.map-search-wrap form', function(e) {
			e.preventDefault();
			var $form = $(this);
			var $input = $form.find('input');
			var addr = $input.val();

			$mapSearchWrap.removeClass('error');
			geocoder['addr2coord'](addr, function(status, result) {
				if (status === daum.maps['services']['Status']['OK']) {
					window.searchMap.center = new daum.maps['LatLng'](result.addr[0].lat, result.addr[0].lng);
					if(window.searchMap.map['getLevel']() > 5)
						window.searchMap.map['setLevel'](5);
					window.searchMap.map['panTo'](window.searchMap.center);
					$form.tooltip('hide');
					$mapPointWrap.addClass('on');
					$input.blur();
				} else {
					ps['keywordSearch'](addr, function(status, data) {
						if (status === daum.maps['services']['Status']['OK']) {
							window.searchMap.center = new daum.maps['LatLng'](data['places'][0].latitude, data['places'][0].longitude);
							if(window.searchMap.map['getLevel']() > 5)
								window.searchMap.map['setLevel'](5);
							window.searchMap.map['panTo'](window.searchMap.center);
							$form.tooltip('hide');
							$mapPointWrap.addClass('on');
							$input.blur();
						} else {
							$form.tooltip('show');
							$mapSearchWrap.addClass('error').find('.tooltip-inner').text('검색 결과를 찾을 수 없습니다.');
						}
					})
				}
			});
		})
		.on('click', '.map-point-wrap button', function() {
			var $btn = $(this);

			var coord = searchMap.map.getCenter();
			geocoder['coord2detailaddr'](coord, function(status, result) {
				if (status === daum.maps['services']['Status']['OK']) {
					$mapPointWrap.removeClass('on');
					var addr = '';
					// var addr = result[0].fullName;
					if (result[0]['roadAddress']['name']) {
						addr = result[0]['roadAddress']['name'];
					}
					else if(result[0]['jibunAddress']['name']){
						addr = result[0]['jibunAddress']['name'];
					}

					var data = {
						coord: coord
					};

					if($btn.hasClass('pickup')) {
						data['pickup'] = addr;
					} else if($btn.hasClass('return')) {
						data['return'] = addr;
					} else if($btn.hasClass('pickup-return')) {
						data['pickupReturn'] = addr;
					} else {
						data['search'] = addr;
					}

					$searchWrap.trigger('setAddress', data);
				}
			});
		})
		.on('click', '.list-search-wrap button.option, .list-content-wrap .grade-info button.option', function() {
			var $button = $(this);
			$button.toggleClass('on');

			var enable = $button.hasClass('on');
			var name = $button.data('name');
			var value = $button.data('value');
			var $input = $searchForm.find('input[name="' + name +'"]');
			var val = $input.val();

			if(enable) {
				val += '|' + value + '|';
			} else {
				val = val.replace('|' + value + '|', '')
			}
			$input.val(val);
		})
		.on('click', '.list-search-wrap input[name="nosmoke"]', function() {
			$searchForm.find('input[name="nosmoke"]').val($(this).val());
		})
		.on('click', '.list-search-wrap .reset-search', function() {
			//대여방법
			$listSearchWrap.find('button.option[data-name="ptype"]').removeClass('on');

			//자차보험
			$listSearchWrap.find('button.option[data-name="insu"]').removeClass('on');

			//모델
			$listSearchWrap.find('input[name="model"]').val('');
			$listSearchWrap.find('button.option[data-name="model"]').removeClass('on');

			//연료
			$listSearchWrap.find('button.option[data-name="fuel"]').removeClass('on');

			//차량옵션
			$listSearchWrap.find('button.option[data-name="option"]').removeClass('on');

			//출고일
			$listSearchWrap.find('button.option[data-name="model"]').removeClass('on');

			//차량색상
			$listSearchWrap.find('input[name="color"]').val('');
			$listSearchWrap.find('button.option[data-name="color"]').removeClass('on');

			//제조사
			$listSearchWrap.find('input[name="company"]').val('');
			$listSearchWrap.find('button.option[data-name="company"]').removeClass('on');

			// 금연차
			$listSearchWrap.find('input[name="nosmoke"]')[0].checked = true;

			$searchForm.trigger('reset');
		})
		.on('click', '.list-content-wrap a.delivery-info, .list-content-wrap a.re-search', function(e) {
			e.preventDefault();

			$searchWrap.removeClass('list detail').addClass('re-search');
			$('header').addClass('no-shadow');
			$('body').removeClass('header-hide');
			if(searchMap.searchMarker) {
				searchMap.center = searchMap.searchMarker.getPosition();
				$searchMap.find('.marker.rentshop-info').removeClass('on').trigger('mouseleave');
			}
			setTimeout(function() {
				searchMap.map['relayout']();
				searchMap.map['panTo'](searchMap.center);
				$searchWrap.trigger('setAddress');
			}, 400);
		})
		.on('reveal', '.list-content-wrap .list-scroll .page li.item', function(e) {
			e.stopPropagation();
			var $item = $(this);
			$item.addClass('reveal');
			setTimeout(function() {
				$item.find('.info .numberAnimation').trigger('animate');
			}, 200);
		})
		.on('click', '.list-content-wrap .distance-info button', function() {
			var $btn = $(this);
			$searchForm.find('input[name="distance"]').val($btn.data('distance'));
			$searchWrap.trigger('search');
		})
		.on('click', '.list-content-wrap a.filter', function(e) {
			e.preventDefault();

			$searchWrap.toggleClass('filter');
			clearTimeout(searchMap.timeout);
			searchMap.timeout = setTimeout(function() {
				searchMap.map['relayout']();
				searchMap.map['setCenter'](searchMap.center);
			}, 300);
		})
		.on('click', '.list-content-wrap .list-scroll .page li.item a.item-link', function(e) {
			e.stopPropagation();
			e.preventDefault();

			var $link = $(this);
			var $li = $link.parent();

			if(!$li.hasClass('on')) {
				scrollTarget.data('onScroll', scrollTarget.scrollTop());
				$listScrollContent.find('li.item.on').removeClass('on active').find('.button-area .numberAnimation').data('current', 0).text(0);
				$li.addClass('on active');
				setTimeout(function() {
					$li.find('.button-area .numberAnimation').trigger('animate');
				}, 200);
				setTimeout(function() {
					$li.removeClass('active');
				}, 2000);

			} else {
				$li.removeClass('on active');
				scrollTarget.data('onScroll', -1);
			}

			if(!isMobile) {
				$li.trigger('goRentshop');
			}
			$searchWrap.removeClass('filter');
		})
		.on('click', '.list-content-wrap .list-scroll .page li a.reservation', function(e) {
			e.preventDefault();

			if(!$reservationForm.data('running')) {
				$reservationForm.data('running', true);

				var $reservation = $(this);
				var vehicleIdx = $reservation.attr('href').split('#')[1];
				var insu = $reservation.data('insu');
				var addr = $searchForm.find('input[name="pickupAddr"]').val();
				var raddr = $searchForm.find('input[name="returnAddr"]').val();

				$reservationForm.find('input[name="vehicle_idx"]').val(vehicleIdx);
				if(typeof insu !== 'undefined') {
					$reservationForm.find('input[name="insu"]').val(insu);
				} else {
					$reservationForm.find('input[name="insu"]').val('');
				}
				$reservationForm.find('input[name="addr"]').val(addr);
				$reservationForm.find('input[name="raddr"]').val(raddr);

				$.getJSON($reservationForm.attr('action'), $reservationForm.serializeArray())
					.done(function(data) {
						if(data['num'] < 1) {
							alert(data['err']);
							return;
						}

						location.href = '/rent/payment.php?idx=' + data.idx;
					})
					.complete(function() {
						$reservationForm.data('running', false);
					});
			}
		})
		.on('keyup', '.list-search-wrap .autocomplete input', function(e) {
			if(e.keyCode == 13) {
				$searchForm.trigger('resetList').submit();
			}
		})
		.on('click', '.list-search-wrap .autocomplete button', function() {
			$searchForm.trigger('resetList').submit();
		})
	;

	scrollTarget.on('scroll', function() {
		var innerHeight = scrollTarget.outerHeight();
		var scrollHeight = $listScrollContent.outerHeight();
		var scrollTop = scrollTarget.scrollTop();

		//on된 아이템 제거하기
		var onScroll = scrollTarget.data('onScroll');
		if(typeof onScroll !== 'undefined' && onScroll > -1 && Math.abs(scrollTop - onScroll) > 250) {
			var $li = $listScrollContent.find('li.item.on').removeClass('on active');
			if(!isMobile)
				$li.trigger('goRentshop');
			scrollTarget.data('onScroll', -1);
		}

		//아이템 불러오기
		if($searchWrap.hasClass('list') && scrollTop + innerHeight > scrollHeight - 260) {
			$searchForm.submit();
		}

		//아이템 보이기
		var revealItems = [];
		for(var i = 0; i < window.listItemOffset.length; i++) {
			if(scrollTop + innerHeight > window.listItemOffset[i].offset) {
				revealItems.push(window.listItemOffset[i].$item);

				window.listItemOffset.splice(i, 1);
				i--;
			}
		}

		for(i = 0; i < revealItems.length; i++) {
			revealItems[i].trigger('reveal');
		}
	});

	addLocationTask(function(location) {
		// var ptype = $('#ptype').val();
		// $('body').addClass('ptype'+ptype);
		$('body').addClass('ptype2');

		setTimeout(function() {
			searchMap.center = new daum.maps['LatLng'](location.lat, location.lng);
			searchMap.map = new daum.maps.Map(searchMap.dom, {
				center: searchMap.center,
				level: 5
			});

			var zoomControl = new daum.maps.ZoomControl();
			searchMap.map['addControl'](zoomControl, daum.maps['ControlPosition']['BOTTOMRIGHT']);

			daum.maps.event.addListener(searchMap.map, 'dragstart', function() {
				$mapPointWrap.removeClass('on');
			});

			daum.maps.event.addListener(searchMap.map, 'dragend', function() {
				$mapPointWrap.addClass('on');
				searchMap.center = this.getCenter();
			});

			daum.maps.event.addListener(searchMap.map, 'click', function(e) {
				$mapPointWrap.addClass('on');
				searchMap.center = e['latLng'];
				searchMap.map['panTo'](searchMap.center);
			});

			setTimeout(function() {
				$mapPointWrap.addClass('on');
			}, 200);
		}, 300);
	});
});
