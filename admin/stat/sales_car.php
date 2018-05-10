<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

$chart = [];

$modelname = getModelnameGeneral();

$query = "
	SELECT
		$modelname category,
		COUNT(1) total,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) total_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON vs.company_idx = c.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY category
	ORDER BY total DESC
	LIMIT 0, 10
";
$dataModelReservation = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataModelReservation[] = $row;
}
$chart[] = array('title' => '모델별 예약', 'color' => 2, 'data' => $dataModelReservation, 'unit' => '건');

$query = "
	SELECT
		$modelname category,
		SUM(a.account - a.isouts) total,
		SUM(CASE WHEN mt.retype=1 THEN a.account - a.isouts ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype=2 THEN a.account - a.isouts ELSE 0 END) total_long
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON vs.company_idx = c.idx
	WHERE
		a.tbtype = 'I'
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
		AND a.account > a.isouts
	GROUP BY category
	ORDER BY total DESC
	LIMIT 0, 10
";
$dataModelSales = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataModelSales[] = $row;
}
$chart[] = array('title' => '모델별 매출', 'color' => 0, 'data' => $dataModelSales, 'unit' => '원');

$query = "
	SELECT
		c.idx,
		c.sname category,
		COUNT(1) total,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) total_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON vs.grade_idx = c.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
		AND c.idx IS NOT NULL
	GROUP BY c.idx
	ORDER BY c.idx
";
$dataGradeReservation = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataGradeReservation[] = $row;
}
$chart[] = array('title' => '등급별 예약', 'color' => 2, 'data' => $dataGradeReservation, 'unit' => '건');

$query = "
	SELECT
		c.idx,
		c.sname category,
		SUM(a.account - a.isouts) total,
		SUM(CASE WHEN mt.retype=1 THEN a.account - a.isouts ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype=2 THEN a.account - a.isouts ELSE 0 END) total_long
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON vs.grade_idx = c.idx
	WHERE
		a.tbtype = 'I'
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
		AND a.account > a.isouts
		AND c.idx IS NOT NULL
	GROUP BY c.idx
	ORDER BY c.idx
";
$dataGradeSales = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataGradeSales[] = $row;
}
$chart[] = array('title' => '등급별 매출', 'color' => 0, 'data' => $dataGradeSales, 'unit' => '원');

$query = "
	SELECT
		c.sname category,
		COUNT(1) total,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) total_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON vs.company_idx = c.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
		AND c.sname IS NOT NULL
	GROUP BY sname
	ORDER BY total DESC
	LIMIT 0, 10
";
$dataCompanyReservation = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataCompanyReservation[] = $row;
}
$chart[] = array('title' => '제조사별 예약', 'color' => 2, 'data' => $dataCompanyReservation, 'unit' => '건');

$query = "
	SELECT
		c.sname category,
		SUM(a.account - a.isouts) total,
		SUM(CASE WHEN mt.retype=1 THEN a.account - a.isouts ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype=2 THEN a.account - a.isouts ELSE 0 END) total_long
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON vs.company_idx = c.idx
	WHERE
		a.tbtype = 'I'
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
		AND a.account > a.isouts
		AND c.sname IS NOT NULL
	GROUP BY sname
	ORDER BY total DESC
	LIMIT 0, 10
";
$dataCompanySales = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataCompanySales[] = $row;
}
$chart[] = array('title' => '제조사별 매출', 'color' => 0, 'data' => $dataCompanySales, 'unit' => '원');

$query = "
	SELECT
		c.sname category,
		COUNT(1) total,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) total_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON v.fuel_idx = c.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
		AND c.sname IS NOT NULL
	GROUP BY sname
	ORDER BY total DESC
	LIMIT 0, 10
";
$dataFuelReservation = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataFuelReservation[] = $row;
}
$chart[] = array('title' => '연료타입별 예약', 'color' => 2, 'data' => $dataFuelReservation, 'unit' => '건');

$query = "
	SELECT
		c.sname category,
		SUM(a.account - a.isouts) total,
		SUM(CASE WHEN mt.retype=1 THEN a.account - a.isouts ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype=2 THEN a.account - a.isouts ELSE 0 END) total_long
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN codes c ON v.fuel_idx = c.idx
	WHERE
		a.tbtype = 'I'
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
		AND a.account > a.isouts
		AND c.sname IS NOT NULL
	GROUP BY sname
	ORDER BY total DESC
	LIMIT 0, 10
";
$dataFuelSales = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataFuelSales[] = $row;
}
$chart[] = array('title' => '연료타입별 매출', 'color' => 0, 'data' => $dataFuelSales, 'unit' => '원');

$query = "
	SELECT
		v.color category,
		COUNT(1) total,
		SUM(CASE WHEN mt.retype = 1 THEN 1 ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype = 1 THEN 0 ELSE 1 END) total_long
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY color
	ORDER BY total DESC
	LIMIT 0, 10
";
$dataColorReservation = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataColorReservation[] = $row;
}
$chart[] = array('title' => '색상별 예약', 'color' => 2, 'data' => $dataColorReservation, 'unit' => '건');

$query = "
	SELECT
		v.color category,
		SUM(a.account - a.isouts) total,
		SUM(CASE WHEN mt.retype=1 THEN a.account - a.isouts ELSE 0 END) total_short,
		SUM(CASE WHEN mt.retype=2 THEN a.account - a.isouts ELSE 0 END) total_long
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
	WHERE
		a.tbtype = 'I'
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
		AND a.account > a.isouts
	GROUP BY color
	ORDER BY total DESC
	LIMIT 0, 10
";
$dataColorSales = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataColorSales[] = $row;
}
$chart[] = array('title' => '색상별 매출', 'color' => 0, 'data' => $dataColorSales, 'unit' => '원');

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

<?
$total = count($chart);
for($i = 0; $i < $total; $i += 2) {?>
	<div style="height:250px;margin-top:16px">
		<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
			<div id="chart<?=$i + 1 ?>" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
		</div>
		<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
			<div id="chart<?=$i + 2 ?>" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
		</div>
	</div>
<?}?>

<script type="text/javascript">
	$(function() {
		var chartOption = function(idx, title, unit, color) {
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
//						balloonText: '[[title]]: [[value]]원',
						balloonFunction: function(item, graph) {
							return '<div style="text-align:left;font-size:13px;">'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[color+1] + ';">단기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['단기']).numberFormat(0) + unit + '</span></p>'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[color] + ';">장기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['장기']).numberFormat(0) + unit + '</span></p>'
								+ '<p style="margin:4px 0 0;padding:4px 0 0;font-weight:bold;border-top:solid 1px #999;">합계: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['합계']).numberFormat(0) + unit + '</span></p>'
								+ '</div>'
						},
						fillAlphas: 0,
						clustered: false,
						visibleInLegend: false
					}
				],
				categoryField: '속성',
				categoryAxis: {
					gridPosition: 'start'
				},
				valueAxes: [
					$.extend(true, {}, defaultChartOption.valueAxes, {stackType: 'regular'})
				],
				chartScrollbar: {
					enabled: false
				}
			})
		};
		<?
		$total = count($chart);
		for($i = 0; $i < $total; $i++) {?>
			var chart<?=$i+1?> = AmCharts.makeChart('chart<?=$i+1?>', $.extend(true, {}, chartOption(<?=$i+1?>, '<?=$chart[$i]['title']?>', '<?=$chart[$i]['unit']?>', <?=$chart[$i]['color']?>), {
				dataProvider: [
					<?foreach($chart[$i]['data'] as $data) {?>
					{
						'속성': '<?=$data['category']?>',
						'단기': <?=$data['total_short']?>,
						'장기': <?=$data['total_long']?>,
						'합계': <?=$data['total']?>,
						dataZero: 0
					},
					<?}?>
				]
			}));
		<?}?>
	});
</script>
