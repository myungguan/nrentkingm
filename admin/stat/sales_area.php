<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

$query = "SELECT
		CASE
			WHEN t1.address LIKE '서울%' THEN '서울'
			WHEN t1.address LIKE '부산%' THEN '부산'
			WHEN t1.address LIKE '대구%' THEN '대구'
			WHEN t1.address LIKE '인천%' THEN '인천'
			WHEN t1.address LIKE '광주%' THEN '광주'
			WHEN t1.address LIKE '대전%' THEN '대전'
			WHEN t1.address LIKE '울산%' THEN '울산'
			WHEN t1.address LIKE '경기%' THEN '경기'
			WHEN t1.address LIKE '강원%' THEN '강원'
			WHEN t1.address LIKE '충청북도%' THEN '충북'
			WHEN t1.address LIKE '충청남도%' THEN '충남'
			WHEN t1.address LIKE '전라북도%' THEN '전북'
			WHEN t1.address LIKE '전라남도%' THEN '전남'
			WHEN t1.address LIKE '경상북도%' THEN '경북'
			WHEN t1.address LIKE '경상남도%' THEN '경남'
			WHEN t1.address LIKE '제주%' THEN '제주'
			WHEN t1.address LIKE '세종%' THEN '세종'
			ELSE t1.address
		END addr,
		SUM(t1.reservation) reservation,
		SUM(t1.reservation_short) reservation_short,
		SUM(t1.reservation_long) reservation_long
	FROM (
		SELECT
			SUBSTRING_INDEX(r.addr1, ' ', 1) address,
#			SUBSTRING_INDEX(CASE WHEN m.ptype = 2 THEN r.addr1 ELSE mt.addr END, ' ', 1) address,
			SUM(1) reservation,
			SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) reservation_short,
			SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) reservation_long
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
			LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		WHERE
			m.dan < 5
			AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
			AND r.idx IS NOT NULL
		GROUP BY
			address
	) t1
	GROUP BY
		addr";
$dataReservation = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataReservation[$row['addr']] = array('reservation' => $row['reservation'], 'reservation_short' => $row['reservation_short'], 'reservation_long' => $row['reservation_long']);
}

$query = "SELECT
		CASE
			WHEN t1.address LIKE '서울%' THEN '서울'
			WHEN t1.address LIKE '부산%' THEN '부산'
			WHEN t1.address LIKE '대구%' THEN '대구'
			WHEN t1.address LIKE '인천%' THEN '인천'
			WHEN t1.address LIKE '광주%' THEN '광주'
			WHEN t1.address LIKE '대전%' THEN '대전'
			WHEN t1.address LIKE '울산%' THEN '울산'
			WHEN t1.address LIKE '경기%' THEN '경기'
			WHEN t1.address LIKE '강원%' THEN '강원'
			WHEN t1.address LIKE '충청북도%' THEN '충북'
			WHEN t1.address LIKE '충청남도%' THEN '충남'
			WHEN t1.address LIKE '전라북도%' THEN '전북'
			WHEN t1.address LIKE '전라남도%' THEN '전남'
			WHEN t1.address LIKE '경상북도%' THEN '경북'
			WHEN t1.address LIKE '경상남도%' THEN '경남'
			WHEN t1.address LIKE '제주%' THEN '제주'
			WHEN t1.address LIKE '세종%' THEN '세종'
			ELSE t1.address
		END addr,
		SUM(t1.payment) payment,
		SUM(t1.payment_short) payment_short,
		SUM(t1.payment_long) payment_long
	FROM (
		SELECT
			SUBSTRING_INDEX(r.addr1, ' ', 1) address,
#			SUBSTRING_INDEX(CASE WHEN m.ptype = 2 THEN r.addr1 ELSE mt.addr END, ' ', 1) address,
			SUM( a.account - a.isouts ) payment,
			SUM(CASE WHEN mt.retype=1 THEN a.account - a.isouts ELSE 0 END) payment_short,
			SUM(CASE WHEN mt.retype=2 THEN a.account - a.isouts ELSE 0 END) payment_long
		FROM payment_accounts a
			LEFT JOIN payments m ON a.payment_idx = m.idx
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
			LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		WHERE
			a.tbtype = 'I'
			AND a.account > a.isouts
			AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
			AND r.idx IS NOT NULL
		GROUP BY
			address
	) t1
	GROUP BY
		addr";
$dataPayment = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataPayment[$row['addr']] = array('payment' => $row['payment'], 'payment_short' => $row['payment_short'], 'payment_long' => $row['payment_long']);
}
?>
<form id="search" name="search" action="/stat.php" method="get">
	<input type="hidden" name="code" value="<?=$code ?>" />
	<table class="detailTable2">
		<tbody>
		<tr>
			<th>기간</th>
			<td>
				<input type='text' name='sdate' value='<?=$sdate;?>' size='10' id='sdate' class="datePicker" data-parent="#container" readonly> ~
				<input type='text' name='edate' value='<?=$edate;?>' size='10' id="edate" class="datePicker" data-parent="#container" readonly>
				<button type="button" class="datePickerRange redBtn" data-duration="1d" data-start="#sdate" data-end="#edate" style="height:23px;line-height:22px;width:auto;">오늘</button>
				<button type="button" class="datePickerRange redBtn" data-duration="1w" data-start="#sdate" data-end="#edate" style="height:23px;line-height:22px;width:auto;">1주일</button>
				<button type="button" class="datePickerRange redBtn" data-duration="1M" data-start="#sdate" data-end="#edate" style="height:23px;line-height:22px;width:auto;">1개월</button>
				<button type="button" class="datePickerRange redBtn" data-duration="3M" data-start="#sdate" data-end="#edate" style="height:23px;line-height:22px;width:auto;">3개월</button>
				<button type="button" class="datePickerRange redBtn" data-duration="6M" data-start="#sdate" data-end="#edate" style="height:23px;line-height:22px;width:auto;">6개월</button>
				<button type="button" class="datePickerRange redBtn" data-duration="1y" data-start="#sdate" data-end="#edate" style="height:23px;line-height:22px;width:auto;">1년</button>
				<a href="javascript:" class="greenBtn btn_submit" data-form="#search" style="height:23px;line-height:23px;">검색하기</a>
			</td>
		</tr>
		</tbody>
	</table>
</form>

<div style="height:600px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart1" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart2" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>

<script src="/js/ammap/ammap.js"></script>
<script type="text/javascript">
	$(function() {
		var areaCode = {
			'서울': 'KR-11',
			'부산': 'KR-26',
			'대구': 'KR-27',
			'인천': 'KR-28',
			'광주': 'KR-29',
			'대전': 'KR-30',
			'울산': 'KR-31',
			'경기': 'KR-41',
			'강원': 'KR-42',
			'충북': 'KR-43',
			'충남': 'KR-44',
			'전북': 'KR-45',
			'전남': 'KR-46',
			'경북': 'KR-47',
			'경남': 'KR-48',
			'제주': 'KR-49',
			'세종': 'KR-50'
		};

		var chartOption = function(idx, title, unit) {
			return $.extend(true, {}, defaultChartOption, {
				type: 'map',
				colorSteps: 10,
				useObjectColorForBalloon: false,
				balloon: {
					adjustBorderColor: false,
					borderColor: '#DD1A1A'
				},
				balloonLabelFunction: function(mapObject, ammap) {
					return '<div style="text-align:left;">'
						+ '<p style="margin:0;font-weight:bold;text-align:center;">'+ mapObject.title + '</p>'
						+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[1] + ';">단기: <span style="float:right;margin-left:10px;">' + Number(mapObject.short).numberFormat(0) + unit + '</span></p>'
						+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[0] + ';">장기: <span style="float:right;margin-left:10px;">' + Number(mapObject.long).numberFormat(0) + unit + '</span></p>'
						+ '<p style="margin:4px 0 0;padding:4px 0 0;font-weight:bold;border-top:solid 1px #999;">합계: <span style="float:right;margin-left:10px;">' + Number(mapObject.value).numberFormat(0) + unit + '</span></p>'
						+ '</div>'
				},
				valueLegend: {
					position: 'absolute',
					left: 10,
					bottom: 570,
					width:160,
					minValue: '0',
					showAsGradient: true
				},
				zoomControl: {
					zoomControlEnabled: false,
					minZoomLevel: 0.5,
					panControlEnabled: false,
					homeButtonEnabled: false
				},
				dataProvider: {
					autoResize: false,
					mapURL: '/js/ammap/maps/svg/southKoreaLow.svg',
					zoomLevel: 0.85
				},
				titles: [$.extend(true, {}, defaultChartOption.titles, {text: title})]
			});
		};

		var data = {
			<?foreach($dataReservation as $addr => $data) {?>
			'<?=$addr?>': {
				value: <?=$data['reservation'] ?>,
				short: <?=$data['reservation_short']?>,
				long: <?=$data['reservation_long']?>
			},
			<?}?>
		};
		var areas = [];
		for(var addr in areaCode) {
			if(typeof data[addr] !== 'undefined') {
				areas.push({
					id: areaCode[addr],
					title: addr,
					value: data[addr]['value'],
					short: data[addr]['short'],
					long: data[addr]['long']
				});
			}
		}

		AmCharts.makeChart( "chart1", $.extend(true, chartOption(1, '지역별 예약', '건'), {
			dataProvider: {areas: areas},
			areasSettings: {
				color: '#BBBBBB',
				colorSolid: defaultChartOption.colors[2]
			}
		}));

		data = {
			<?foreach($dataPayment as $addr => $data) {?>
			'<?=$addr?>': {
				value: <?=$data['payment'] ?>,
				short: <?=$data['payment_short']?>,
				long: <?=$data['payment_long']?>
			},
			<?}?>
		};
		areas = [];
		for(addr in areaCode) {
			if(typeof data[addr] !== 'undefined') {
				areas.push({
					id: areaCode[addr],
					title: addr,
					value: data[addr]['value'],
					short: data[addr]['short'],
					long: data[addr]['long']
				});
			}
		}

		AmCharts.makeChart( "chart2", $.extend(true, chartOption(1, '지역별 매출', '원'), {
			dataProvider: {areas: areas},
			areasSettings: {
				color: '#BBBBBB',
				colorSolid: defaultChartOption.colors[0]
			}
		}));
	});
</script>
