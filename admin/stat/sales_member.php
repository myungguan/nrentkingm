<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

	$query = "SELECT
			r.idx,
			r.isok,
			CONCAT(r.name, '(', r.affiliate, ')') name
		FROM
			rentshop r
		ORDER BY name";
$r = mysql_query($query);
$member = array();
while($row = mysql_fetch_assoc($r)) {
	$member[$row['idx']] = $row;
}

$query = "SELECT
		r.idx,
		COUNT(1) reservation,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) reservation_short,
		SUM(CASE WHEN mt.retype = 2 THEN 1 ELSE 0 END) reservation_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY r.idx";
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$member[$row['idx']]['reservation'] = $row['reservation'];
	$member[$row['idx']]['reservation_short'] = $row['reservation_short'];
	$member[$row['idx']]['reservation_long'] = $row['reservation_long'];
}

$query = "SELECT
		r.idx,
		SUM(a.account - a.isouts) payment,
		SUM(CASE WHEN mt.retype = 1 THEN a.account - a.isouts ELSE 0 END) payment_short,
		SUM(CASE WHEN mt.retype = 2 THEN a.account - a.isouts ELSE 0 END) payment_long
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
	WHERE
		r.idx IS NOT NULL
		AND a.tbtype = 'I'
		AND a.account > a.isouts
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
	GROUP BY r.idx";
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$member[$row['idx']]['payment'] = $row['payment'];
	$member[$row['idx']]['payment_short'] = $row['payment_short'];
	$member[$row['idx']]['payment_long'] = $row['payment_long'];
}

$dataStat = [];
$payment = [];
$name = [];
foreach($member as $mem) {
	if(!$mem['reservation'])		$mem['reservation'] = 0;
	if(!$mem['reservation_short'])	$mem['reservation_short'] = 0;
	if(!$mem['reservation_long'])	$mem['reservation_long'] = 0;
	if(!$mem['payment'])			$mem['payment'] = 0;
	if(!$mem['payment_short'])		$mem['payment_short'] = 0;
	if(!$mem['payment_long'])		$mem['payment_long'] = 0;
	$dataStat[] = $mem;
	$payment[] = $mem['payment'];
	$name[] = $mem['name'];
}

array_multisort($payment, SORT_DESC, $name, SORT_ASC, $dataStat);

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

<div id="chart1" style="height:<?=count($dataStat) * 30?>px;margin-top:16px;border:solid 1px #cdd0d5;background:#fff;"></div>

<script type="text/javascript">
	$(function() {
		var chartOption = function(idx, title, dateFormat, parseDate, minPeriod) {
			return $.extend(true, {}, defaultChartOption, {
				titles: [
					$.extend(true, {}, defaultChartOption.titles, {id: 'title'+idx, text: title})
				],
				graphs: [
					{
						id: 'paymentLong'+idx,
						type: 'column',
						valueAxis: 'paymentAxis',
						lineThickness: 0,
						title: '매출(장기)',
						valueField: '매출_장기',
						balloonText: '',
						fillAlphas: 0.9
					},
					{
						id: 'paymentShort'+idx,
						type: 'column',
						valueAxis: 'paymentAxis',
						lineThickness: 0,
						title: '매출(단기)',
						valueField: '매출_단기',
						balloonText: '',
						fillAlphas: 0.9,
						showBalloonAt: 'close',
						balloonFunction: function(item, graph) {
							return '<div style="text-align:left;">'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[1] + ';">단기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['매출_단기']).numberFormat(0) + '원 (' + Number(item.dataContext['예약_단기']).numberFormat(0) + '건)</span></p>'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[0] + ';">장기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['매출_장기']).numberFormat(0) + '원 (' + Number(item.dataContext['예약_장기']).numberFormat(0) + '건)</span></p>'
								+ '<p style="margin:4px 0 0;padding:4px 0 0;font-weight:bold;border-top:solid 1px #999;">합계: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['매출']).numberFormat(0) + '원 (' + Number(item.dataContext['예약']).numberFormat(0) + '건)</span></p>'
								+ '</div>'
						},
						balloonColor: defaultChartOption.chartCursor.cursorColor
					},
					{
						id: 'reservationLong'+idx,
						type: 'column',
						valueAxis: 'reservationAxis',
						lineThickness: 0,
						title: '예약(장기)',
						valueField: '예약_장기',
						balloonText: '',
						fillAlphas: 0.9,
						hidden: true
					},
					{
						id: 'reservationShort'+idx,
						type: 'column',
						valueAxis: 'reservationAxis',
						lineThickness: 0,
						title: '예약(단기)',
						valueField: '예약_단기',
						balloonText: '',
						fillAlphas: 0.9,
						hidden: true
					}
				],
				categoryAxis: {
					forceShowField: 'name',
					parseDates: parseDate,
					minPeriod: minPeriod ? minPeriod : 'DD',
					gridPosition: 'start',
					labelColorField: 'color',
					dateFormats: [
						{period: 'fff', format: dateFormat},
						{period: 'ss', format: dateFormat},
						{period: 'mm', format: dateFormat},
						{period: 'hh', format: dateFormat},
						{period: 'DD', format: dateFormat},
						{period: 'WW', format: dateFormat},
						{period: 'MM', format: dateFormat},
						{period: 'YYYY', format: dateFormat}
					]
				},
				valueAxes: [
					$.extend(true, {}, defaultChartOption.valueAxes, {id:'paymentAxis', stackType: 'regular', position: 'top'}),
					$.extend(true, {}, defaultChartOption.valueAxes, {id:'reservationAxis', stackType: 'regular'})
				]
			})
		};

		var chart1 = AmCharts.makeChart('chart1', $.extend(true, {}, chartOption(2, '멤버사별 예약/매출', 'YYYY-MM', false, 'MM'), {
			type: 'serial',
			rotate: true,
			dataDateFormat: 'YYYY-MM',
			categoryField: '멤버사',
			dataProvider: [
				<?foreach($dataStat as $data) {?>
				{
					'멤버사': '<?=$data['name']?>',
					'매출_단기': <?=$data['payment_short']?>,
					'매출_장기': <?=$data['payment_long']?>,
					'매출': <?=$data['payment']?>,
					'예약_단기': <?=$data['reservation_short']?>,
					'예약_장기': <?=$data['reservation_long']?>,
					'예약': <?=$data['reservation']?>,
					dataZero: 0,
					<?if($data['isok'] != 'Y') {?>
					color: defaultChartOption.balloonColor
					<?}?>
				},
				<?}?>
			]
		}));
	});
</script>
