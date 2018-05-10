<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

$oldReservation = 0;
$oldSales = 0;

$query = "SELECT SUM(1) sum FROM payments m WHERE m.dan < 5 AND m.dt_create < '$sdate 00:00:00'";
$oldReservation = mysql_fetch_assoc(mysql_query($query))['sum'];

$query = "SELECT SUM(a.account - a.isouts) sum FROM payment_accounts a WHERE a.tbtype = 'I' AND a.account > a.isouts AND a.dt_create < '$sdate 00:00:00'";
$oldSales = mysql_fetch_assoc(mysql_query($query))['sum'];

$query = "SELECT DATEDIFF('$edate', (
	SELECT
		MIN(LEFT(m.dt_create, 10)) min
	FROM payments m
	WHERE
		m.dan < 5 AND
		m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
))+1 days";
$days = mysql_fetch_assoc(mysql_query($query))['days'];

$query = "SELECT
		LEFT( m.dt_create, 7 ) month,
		SUM(1) reservation,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) reservation_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) reservation_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY month
	ORDER BY month";
$dataReservationMonth = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataReservationMonth[] = $row;
}

$query = "SELECT
		LEFT( a.dt_create, 7 ) month,
		SUM( a.account - a.isouts ) payment,
		SUM(CASE WHEN mt.retype=1 THEN a.account - a.isouts ELSE 0 END) payment_short,
		SUM(CASE WHEN mt.retype=2 THEN a.account - a.isouts ELSE 0 END) payment_long
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		a.tbtype = 'I'
		AND a.account > a.isouts
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
	GROUP BY month
	ORDER BY month";
$dataSalesMonth = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataSalesMonth[] = $row;
}

$query = "SELECT
		LEFT( m.dt_create, 10) day,
		SUM(1) reservation,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) reservation_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) reservation_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY day
	ORDER BY day";
$dataReservationDay = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataReservationDay[] = $row;
}

$query = "SELECT
		LEFT( a.dt_create, 10 ) day,
		SUM( a.account - a.isouts ) payment,
		SUM(CASE WHEN mt.retype=1 THEN a.account - a.isouts ELSE 0 END) payment_short,
		SUM(CASE WHEN mt.retype=2 THEN a.account - a.isouts ELSE 0 END) payment_long
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		a.tbtype = 'I'
		AND a.account > a.isouts
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
	GROUP BY day
	ORDER BY day";
$dataSalesDay = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataSalesDay[] = $row;
}

$query = "SELECT
		DAYOFWEEK(m.dt_create) dow,
		SUM(1) * 7 / $days reservation,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) * 7 / $days reservation_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) * 7 / $days reservation_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY dow
	ORDER BY dow";
$dataReservationWeek = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataReservationWeek[] = $row;
}

$query = "SELECT
		DAYOFWEEK(m.dt_create) dow,
		SUM( a.account - a.isouts ) * 7 / $days payment,
		SUM(CASE WHEN mt.retype=1 THEN a.account - a.isouts ELSE 0 END) * 7 / $days payment_short,
		SUM(CASE WHEN mt.retype=2 THEN a.account - a.isouts ELSE 0 END) * 7 / $days payment_long
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		a.tbtype = 'I'
		AND a.account > a.isouts
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
	GROUP BY dow
	ORDER BY dow";
$dataSalesWeek = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataSalesWeek[] = $row;
}

$query = "SELECT
		HOUR(m.dt_create) hour,
		SUM(1) / $days reservation,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) / $days reservation_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) / $days reservation_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY hour
	ORDER BY hour";
$dataReservationHour = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataReservationHour[] = $row;
}

$query = "SELECT
		HOUR(m.dt_create) hour,
		SUM( a.account - a.isouts ) / $days payment,
		SUM(CASE WHEN mt.retype=1 THEN a.account - a.isouts ELSE 0 END) / $days payment_short,
		SUM(CASE WHEN mt.retype=2 THEN a.account - a.isouts ELSE 0 END) / $days payment_long
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		a.tbtype = 'I'
		AND a.account > a.isouts
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
	GROUP BY hour
	ORDER BY hour";
$dataSalesHour = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataSalesHour[] = $row;
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

<div style="height:250px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart1" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart2" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>

<div style="height:250px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart3" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart4" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>

<div style="height:250px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart5" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart6" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>

<div style="height:250px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart7" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart8" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var chartOption = function(idx, title, color, dateFormat, parseDate, unit, precision, minPeriod) {
			return $.extend(true, {}, defaultChartOption, {
				type: 'serial',
				titles: [
					$.extend(true, {}, defaultChartOption.titles, {id: 'title'+idx, text: title})
				],
				colors:[
					defaultChartOption.colors[color],
					defaultChartOption.colors[color+1]
				],
				graphs: [
					{
						id: 'dataLong'+idx,
						type: 'column',
						lineThickness: 0,
						title: '장기',
						valueField: '장기',
						balloonText: '',
						fillAlphas: 0.9
					},
					{
						id: 'dataShort'+idx,
						type: 'column',
						lineThickness: 0,
						title: '단기',
						valueField: '단기',
						balloonText: '',
						fillAlphas: 0.9
					},
					{
						id: 'data'+idx,
						type: 'column',
						lineThickness: 0,
						lineColor: defaultChartOption.chartCursor.cursorColor,
						title: '합계',
						valueField: 'dataZero',
						balloonFunction: function(item, graph) {
							var html = '<div style="text-align:left;font-weight:bold;">'
								+ '<p style="margin:0;color:' + defaultChartOption.colors[color+1] + ';">단기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['단기']).numberFormat(precision) + unit + '</span></p>'
								+ '<p style="margin:0;color:' + defaultChartOption.colors[color] + ';">장기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['장기']).numberFormat(precision) + unit + '</span></p>'
								+ '<p style="margin:4px 0 0;padding:4px 0 0;border-top:solid 1px #999;">합계: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['합계']).numberFormat(precision) + unit + '</span></p>';
							if(item.dataContext['누적'])
								html += '<p style="margin:4px 0 0;padding:4px 0 0;border-top:solid 1px #999;color:' + defaultChartOption.balloonColor + ';">누적: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['누적']).numberFormat(precision) + unit + '</span></p>'
							html += '</div>';
							return html;
						},
						fillAlphas: 0,
						clustered: false,
						visibleInLegend: false
					},
					{
						id: 'sum'+idx,
						bullet: 'round',
						bulletAlpha: 0.9,
						hideBulletsCount: 36,
						lineThickness: 3,
						title: '누적',
						valueField: '누적',
						valueAxis: 'sumAxis',
						fillAlphas: 0,
						lineAlpha: 0.9,
						lineColor: defaultChartOption.balloonColor,
						balloonText: '',
						visibleInLegend: false
					}
				],
				categoryAxis: {
					parseDates: parseDate,
					minPeriod: minPeriod ? minPeriod : 'DD',
					gridPosition: 'start',
					dateFormats: [
						{period: 'fff', format: dateFormat},
						{period: 'ss', format: dateFormat},
						{period: 'mm', format: dateFormat},
						{period: 'hh', format: dateFormat},
						{period: 'DD', format: dateFormat},
						{period: 'WW', format: dateFormat},
						{period: 'MM', format: dateFormat},
						{period: 'YYYY', format: dateFormat}
					],
					autoGuides: {
						days: [],
						fillColor: defaultChartOption.chartCursor.cursorColor,
						fillAlpha: 0.1,
						lineAlpha: 0
					},
//					maximumDate: moment().format('YYYY-MM-DD')
				},
				valueAxes: [
					$.extend(true, {}, defaultChartOption.valueAxes, {stackType: 'regular'})
				],
				chartCursor: {
					categoryBalloonDateFormat: dateFormat
				},
				chartScrollbar: {
					enabled: true,
					graph: 'data'+idx
				}
			})
		};

		var chart1 = AmCharts.makeChart('chart1', $.extend(true, {}, chartOption(2, '월별 예약', 2, 'YYYY-MM', true, '건', 0, 'MM'), {
			chartScrollbar: {
				enabled: false
			},
			dataDateFormat: 'YYYY-MM',
			categoryField: '월',
			dataProvider: [
				<?
				$average = 0;
				$sum = $oldReservation;
				foreach($dataReservationMonth as $data) {
					$average += $data['reservation'];
					$sum += $data['reservation'];
					?>
				{
					'월': '<?=$data['month']?>',
					'단기': <?=$data['reservation_short']?>,
					'장기': <?=$data['reservation_long']?>,
					'합계': <?=$data['reservation']?>,
					'누적': <?=$sum?>,
					dataZero: 0
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					stackType: 'regular',
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataReservationMonth)?>,
						label: Number(<?=$average / count($dataReservationMonth)?>).numberFormat(2)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'sumAxis',
					minimum:0,
					position: 'right',
					gridAlpha: 0
				})
			]
		}));

		var chart2 = AmCharts.makeChart('chart2', $.extend(true, {}, chartOption(2, '월별 매출', 0, 'YYYY-MM', true, '원', 0, 'MM'), {
			chartScrollbar: {
				enabled: false
			},
			dataDateFormat: 'YYYY-MM',
			categoryField: '월',
			dataProvider: [
				<?
				$average = 0;
				$sum = $oldSales;
				foreach($dataSalesMonth as $data) {
					$average += $data['payment'];
					$sum += $data['payment']
					?>
				{
					'월': '<?=$data['month']?>',
					'단기': <?=$data['payment_short']?>,
					'장기': <?=$data['payment_long']?>,
					'합계': <?=$data['payment']?>,
					'누적': <?=$sum?>,
					dataZero: 0
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					stackType: 'regular',
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataSalesMonth)?>,
						label: Number(<?=$average / count($dataSalesMonth)?>).numberFormat(0)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'sumAxis',
					minimum:0,
					position: 'right',
					gridAlpha: 0
				})
			]
		}));

		var chart3 = AmCharts.makeChart('chart3', $.extend(true, {}, chartOption(4, '일별 예약', 2, 'YYYY-MM-DD', true, '건', 0), {
			dataDateFormat: 'YYYY-MM-DD',
			categoryField: '날짜',
			categoryAxis: {
				autoGuides: {days:[0]}
			},
			dataProvider: [
				<?
				$average = 0;
				$sum = $oldReservation;
				foreach($dataReservationDay as $data) {
					$average += $data['reservation'];
					$sum += $data['reservation'];
					?>
				{
					'날짜': '<?=$data['day']?>',
					'단기': <?=$data['reservation_short']?>,
					'장기': <?=$data['reservation_long']?>,
					'합계': <?=$data['reservation']?>,
					'누적': <?=$sum?>,
					dataZero: 0
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					stackType: 'regular',
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / $days?>,
						label: Number(<?=$average / $days?>).numberFormat(2)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'sumAxis',
					minimum:0,
					position: 'right',
					gridAlpha: 0
				})
			]
		}));
		chart3.addListener('rendered', function(e) {
			e.chart.zoomToDates(moment('<?=$edate?>').subtract(1, 'M').toDate(), moment('<?=$edate?>').add(1, 'd').toDate());
		});

		var chart4 = AmCharts.makeChart('chart4', $.extend(true, {}, chartOption(4, '일별 매출', 0, 'YYYY-MM-DD', true, '원', 0), {
			dataDateFormat: 'YYYY-MM-DD',
			categoryField: '날짜',
			categoryAxis: {
				autoGuides: {days:[0]}
			},
			dataProvider: [
				<?
				$average = 0;
				$sum = $oldSales;
				foreach($dataSalesDay as $data) {
					$average += $data['payment'];
					$sum += $data['payment'];
					?>
				{
					'날짜': '<?=$data['day']?>',
					'단기': <?=$data['payment_short']?>,
					'장기': <?=$data['payment_long']?>,
					'합계': <?=$data['payment']?>,
					'누적': <?=$sum ?>,
					dataZero: 0
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					stackType: 'regular',
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / $days?>,
						label: Number(<?=$average / $days?>).numberFormat(0)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'sumAxis',
					minimum:0,
					position: 'right',
					gridAlpha: 0
				})
			]
		}));
		chart4.addListener('rendered', function(e) {
			e.chart.zoomToDates(moment('<?=$edate?>').subtract(1, 'M').toDate(), moment('<?=$edate?>').add(1, 'd').toDate());
		});

		var chart5 = AmCharts.makeChart('chart5', $.extend(true, {}, chartOption(4, '요일별 평균 예약', 2, 'YYYY-MM-DD', false, '건', 2), {
			chartScrollbar: {
				enabled: false
			},
			categoryField: '요일',
			guides: [
				{
					fillAlpha: 0.1,
					fillColor: defaultChartOption.chartCursor.cursorColor,
					category: '일',
					toCategory: '일',
					expand: true
				}
			],
			dataProvider: [
				<?
				$average = 0;
				foreach($dataReservationWeek as $data) {
					$average += $data['reservation'];
					switch($data['dow']) {
						case 1:	$data['dow'] = '일';	break;
						case 2:	$data['dow'] = '월';	break;
						case 3:	$data['dow'] = '화';	break;
						case 4:	$data['dow'] = '수';	break;
						case 5:	$data['dow'] = '목';	break;
						case 6:	$data['dow'] = '금';	break;
						case 7:	$data['dow'] = '토';	break;
					}
					?>
				{
					'요일': '<?=$data['dow']?>',
					'단기': <?=$data['reservation_short']?>,
					'장기': <?=$data['reservation_long']?>,
					'합계': <?=$data['reservation']?>,
					dataZero: 0
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					stackType: 'regular',
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / 7 ?>,
						label: Number(<?=$average / 7?>).numberFormat(2)
					})]
				})
			]
		}));

		var chart6 = AmCharts.makeChart('chart6', $.extend(true, {}, chartOption(4, '요일별 평균 매출', 0, 'YYYY-MM-DD', false, '원', 0), {
			chartScrollbar: {
				enabled: false
			},
			categoryField: '요일',
			guides: [
				{
					fillAlpha: 0.1,
					fillColor: defaultChartOption.chartCursor.cursorColor,
					category: '일',
					toCategory: '일',
					expand: true
				}
			],
			dataProvider: [
				<?
				$average = 0;
				foreach($dataSalesWeek as $data) {
					$average += $data['payment'];
					switch($data['dow']) {
						case 1:	$data['dow'] = '일';	break;
						case 2:	$data['dow'] = '월';	break;
						case 3:	$data['dow'] = '화';	break;
						case 4:	$data['dow'] = '수';	break;
						case 5:	$data['dow'] = '목';	break;
						case 6:	$data['dow'] = '금';	break;
						case 7:	$data['dow'] = '토';	break;
					}
					?>
				{
					'요일': '<?=$data['dow']?>',
					'단기': <?=$data['payment_short']?>,
					'장기': <?=$data['payment_long']?>,
					'합계': <?=$data['payment']?>,
					dataZero: 0
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					stackType: 'regular',
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / 7 ?>,
						label: Number(<?=$average / 7?>).numberFormat(0)
					})]
				})
			]
		}));

		var chart7 = AmCharts.makeChart('chart7', $.extend(true, {}, chartOption(4, '시간대별 평균 예약', 2, 'YYYY-MM-DD', false, '건', 2), {
			chartScrollbar: {
				enabled: false
			},
			categoryField: '시간',
			guides: [
				{
					fillAlpha: 0.1,
					fillColor: defaultChartOption.chartCursor.cursorColor,
					category: '0',
					toCategory: '8',
					expand: true
				},
				{
					fillAlpha: 0.1,
					fillColor: defaultChartOption.chartCursor.cursorColor,
					category: '18',
					toCategory: '23',
					expand: true
				}
			],
			dataProvider: [
				<?
				$average = 0;
				foreach($dataReservationHour as $data) {
					$average += $data['reservation'];
					?>
				{
					'시간': '<?=$data['hour']?>',
					'단기': <?=$data['reservation_short']?>,
					'장기': <?=$data['reservation_long']?>,
					'합계': <?=$data['reservation']?>,
					dataZero: 0
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					stackType: 'regular',
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / 24 ?>,
						label: Number(<?=$average / 24?>).numberFormat(2)
					})]
				})
			]
		}));

		var chart8 = AmCharts.makeChart('chart8', $.extend(true, {}, chartOption(4, '시간대별 평균 매출', 0, 'YYYY-MM-DD', false, '원', 0), {
			chartScrollbar: {
				enabled: false
			},
			categoryField: '시간',
			guides: [
				{
					fillAlpha: 0.1,
					fillColor: defaultChartOption.chartCursor.cursorColor,
					category: '0',
					toCategory: '8',
					expand: true
				},
				{
					fillAlpha: 0.1,
					fillColor: defaultChartOption.chartCursor.cursorColor,
					category: '18',
					toCategory: '23',
					expand: true
				}
			],
			dataProvider: [
				<?
				$average = 0;
				foreach($dataSalesHour as $data) {
					$average += $data['payment'];
					?>
				{
					'시간': '<?=$data['hour']?>',
					'단기': <?=$data['payment_short']?>,
					'장기': <?=$data['payment_long']?>,
					'합계': <?=$data['payment']?>,
					dataZero: 0
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					stackType: 'regular',
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / 24 ?>,
						label: Number(<?=$average / 24?>).numberFormat(0)
					})]
				})
			]
		}));
	});
</script>
