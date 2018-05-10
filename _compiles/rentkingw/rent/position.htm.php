<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/rent/position.htm 000003443 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="searchWrap">
		<div class="map-wrap">
			<div id="searchMap"></div>
		</div>
	</div>

	<script type="text/javascript">
		$(function() {
			var order = <?php echo $TPL_VAR["order"]?>;
			var $searchWrap = $('#searchWrap');
			var $searchMap = $('#searchMap');
			var searchMap = {
				map: null,
				center: null,
				timeout: null
			};

			var geocoder = new daum.maps['services']['Geocoder']();
			var ps = new daum.maps['services']['Places']();

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

			var setMap = function(order, lat, lng) {
				searchMap.center = new daum.maps['LatLng'](lat, lng);
				searchMap.map = new daum.maps.Map($searchMap[0], {
					center: searchMap.center,
					level: 4
				});

				var content = '<div class="info">';
				if(order['ptype'] == 2) {
					content += '<div class="name">' + order['rentshop_name'] + '(' + order['affiliate']+ ')</div>' +
						'<div class="address">' + order['addr1'] + order['addr2'] + '</div>' +
						'<div class="time">' +
						'<div class="title">운영시간</div>' +
						'<div class="data">평일(' + order['s1time1'] + ' ~ ' + order['s1time2'] + ')<br />주말/공휴일(' + order['s2time1'] + ' ~ ' + order['s2time2'] + ')</div>' +
						'</div>'
				} else {
					content += '<div class="name">배달 위치</div>' +
						'<div class="address">' + order['addr'] + '</div>';
				}

				content += '</div>';
				var marker = '<div class="marker rentshop-info on" id="rentshop' + order['idx'] + '">' + content + '</div>';
				var customOverlay = new daum.maps.CustomOverlay({
					position: searchMap.center,
					content: marker,
					yAnchor: 1
				});

				customOverlay.setMap(searchMap.map);
			};

			if(order['ptype'] == 2) {
				setMap(order, order['lat'], order['lng']);
			} else {
				geocoder['addr2coord'](order['addr'], function(status, result) {
					if (status === daum.maps['services']['Status']['OK']) {
						setMap(order, result.addr[0].lat, result.addr[0].lng);
					} else {
						ps['keywordSearch'](addr, function(status, data) {
							if (status === daum.maps['services']['Status']['OK']) {
								setMap(order, data['places'][0].latitude, data['places'][0].longitude);
							} else {
								alert('위치를 찾을 수 없습니다.');
							}
						})
					}
				});
			}


		})
	</script>

	<?
		$pageType='web';
		if($TPL_VAR['global']['production']) {
			include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/log/analytics.php");
			include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/log/naverkeyword.php");
		}
	?>
</body>
</html>