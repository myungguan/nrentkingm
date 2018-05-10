<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

$query = "
	SELECT
		CASE WHEN mt.retype = 1 THEN '단기' ELSE '장기' END type,
		COUNT(1) total
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY type
	ORDER BY type DESC
";
$dataReservation = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataReservation[] = $row;
}

$query = "
	SELECT
		CASE WHEN mt.retype = 1 THEN '단기' ELSE '장기' END type,
		SUM( a.account - a.isouts ) total
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		a.tbtype = 'I'
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
		AND a.account > a.isouts
	GROUP BY type
	ORDER BY type DESC
";
$dataSales = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataSales[] = $row;
}

$query = "
	SELECT
		CASE WHEN m.ptype = 1 THEN '배달대여' ELSE '지점방문' END type,
		COUNT(1) total
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY type
	ORDER BY type
";
$dataPTypeReservation = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataPTypeReservation[] = $row;
}

$query = "
	SELECT
		CASE WHEN m.ptype = 1 THEN '배달대여' ELSE '지점방문' END type,
		SUM( a.account - a.isouts ) total
	FROM payment_accounts a
		LEFT JOIN payments m ON a.payment_idx = m.idx
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		a.tbtype = 'I'
		AND a.dt_create >= '$sdate 00:00:00' AND a.dt_create <= '$edate 23:59:59'
		AND a.account > a.isouts
	GROUP BY type
	ORDER BY type
";
$dataPTypeSales = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataPTypeSales[] = $row;
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

<div style="height:300px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart1" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart2" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>
<div style="height:300px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart3" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart4" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var chartOption = function(title) {
			return $.extend(true, {}, defaultChartOption, {
				type: 'pie',
				titles: [{text: title}],
				titleField: '타입',
				valueField: '합계',
				legend: {
					enabled: false
				},
				autoMargins: false,
				marginTop: 40,
				marginBottom: 5,
				marginLeft: 0,
				marginRight: 0,
				pullOutRadius: 15
			});
		};

		var chart1 = AmCharts.makeChart( "chart1", $.extend(true, {}, chartOption('장기/단기 예약'), {
			dataProvider: [
				<?
				$current = 0;
				foreach($dataReservation as $data) {?>
				{
					'타입': '<?=$data['type'] ?>',
					'합계': <?=$data['total'] ?>,
					<?if($current++ == 0) {?>
					color: defaultChartOption.colors[2]
					<?} else {?>
					color: defaultChartOption.colors[3]
					<?}?>
				},
				<?}?>
			]
		}) );

		var chart2 = AmCharts.makeChart( "chart2", $.extend(true, {}, chartOption('장기/단기 매출'), {
			dataProvider: [
				<?
				$current = 0;
				foreach($dataSales as $data) {?>
				{
					'타입': '<?=$data['type'] ?>',
					'합계': <?=$data['total'] ?>,
					<?if($current++ == 0) {?>
					color: defaultChartOption.colors[0]
					<?} else {?>
					color: defaultChartOption.colors[1]
					<?}?>
				},
				<?}?>
			]
		}) );

		var chart3 = AmCharts.makeChart( "chart3", $.extend(true, {}, chartOption('배달대여/지점방문 예약'), {
			dataProvider: [
				<?
				$current = 0;
				foreach($dataPTypeReservation as $data) {?>
				{
					'타입': '<?=$data['type'] ?>',
					'합계': <?=$data['total'] ?>,
					<?if($current++ == 0) {?>
					color: defaultChartOption.colors[2]
					<?} else {?>
					color: defaultChartOption.colors[3]
					<?}?>
				},
				<?}?>
			]
		}) );

		var chart4 = AmCharts.makeChart( "chart4", $.extend(true, {}, chartOption('배달대여/지점방문 매출'), {
			dataProvider: [
				<?
				$current = 0;
				foreach($dataPTypeSales as $data) {?>
				{
					'타입': '<?=$data['type'] ?>',
					'합계': <?=$data['total'] ?>,
					<?if($current++ == 0) {?>
					color: defaultChartOption.colors[0]
					<?} else {?>
					color: defaultChartOption.colors[1]
					<?}?>
				},
				<?}?>
			]
		}) );
	});
</script>
