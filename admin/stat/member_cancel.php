<?php

if(!$sdate)
{
	$sdate = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d').' - 1 months')));
}

if(!$edate)
{	$edate = date("Y-m-d");	}

$query = "SELECT
	t2.*
FROM (
	SELECT
		CONCAT(t.name, '(', t.affiliate, ')') rentshop,
		t.reservation,
		t.cancel,
		t.cancel / t.reservation * 100 rate,
		t.isok
	FROM (
		SELECT
			r.name,
			r.affiliate,
			r.isok,
			SUM(1) reservation,
			SUM(CASE WHEN m.dan > 3 THEN 1 ELSE 0 END) cancel
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
			LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		WHERE
 			r.isok = 'Y'
			AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
		GROUP BY r.name
	) t
	WHERE reservation >= 10
	ORDER BY reservation DESC
) t2
ORDER BY t2.rate DESC";
$r = mysql_query($query);
$dataStat = [];
while($row = mysql_fetch_assoc($r)) {
	$dataStat[] = $row;
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

<div id="chart1" style="height:<?=count($dataStat) * 30?>px;margin-top:16px;border:solid 1px #cdd0d5;background:#fff;"></div>

<script type="text/javascript">
	$(function() {
		var chartOption = function(idx, title, dateFormat, parseDate, minPeriod) {
			return $.extend(true, {}, defaultChartOption, {
				titles: [
					$.extend(true, {}, defaultChartOption.titles, {id: 'title'+idx, text: title})
				],
				colors: [
					defaultChartOption.chartCursor.cursorColor
				],
				graphs: [
					{
						id: 'cancel'+idx,
						type: 'column',
						valueAxis: 'cancelAxis',
						lineThickness: 0,
						title: '취소율',
						valueField: '취소율',
						balloonText: '',
						fillAlphas: 0.9,
						showBalloonAt: 'close',
						balloonFunction: function(item, graph) {
							return '<div style="text-align:left;">'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[0] + ';">예약: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['예약']).numberFormat(0) + '건</span></p>'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.chartCursor.cursorColor + ';">취소: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['취소']).numberFormat(0) + '건</span></p>'
								+ '<p style="margin:4px 0 0;padding:4px 0 0;font-weight:bold;border-top:solid 1px #999;">취소율: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['취소율']).numberFormat(1) + '%</span></p>'
								+ '</div>'
						},
						balloonColor: defaultChartOption.chartCursor.cursorColor
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
					$.extend(true, {}, defaultChartOption.valueAxes, {id:'cancelAxis', position: 'top'})
				]
			})
		};

		var chart1 = AmCharts.makeChart('chart1', $.extend(true, {}, chartOption(2, '멤버사별 취소율(예약 10건 이상)', 'YYYY-MM', false, 'MM'), {
			type: 'serial',
			rotate: true,
			dataDateFormat: 'YYYY-MM',
			categoryField: '멤버사',
			dataProvider: [
				<?foreach($dataStat as $data) {?>
				{
					'멤버사': '<?=$data['rentshop']?>',
					'예약': <?=$data['reservation']?>,
					'취소': <?=$data['cancel']?>,
					'취소율': <?=$data['rate']?>,
					<?if($data['isok'] != 'Y') {?>
					color: defaultChartOption.balloonColor
					<?}?>
				},
				<?}?>
			]
		}));
	});
</script>
