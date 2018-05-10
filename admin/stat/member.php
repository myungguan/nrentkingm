<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

$query = "SELECT DATEDIFF('$edate', (
	SELECT
		MIN(LEFT(m.signdate, 10)) min
	FROM
		member m
	WHERE
		m.memgrade = 100
		AND m.id <> 'outmember'
		AND m.signdate >= '$sdate 00:00:00' AND m.signdate <= '$edate 23:59:59'
))+1 days";
$days = mysql_fetch_assoc(mysql_query($query))['days'];

$query = "SELECT COUNT(1) total
	FROM member m
	WHERE
		m.memgrade = 100
		AND m.id <> 'outmember'
		AND m.signdate < '$sdate 00:00:00'";
$r = mysql_query($query);
$old = mysql_fetch_assoc($r)['total'];

$query = "SELECT
		LEFT( m.signdate, 7 ) month
		, COUNT(1) total
	FROM
		member m
	WHERE
		m.memgrade = 100
		AND m.id <> 'outmember'
		AND m.signdate >= '$sdate 00:00:00' AND m.signdate <= '$edate 23:59:59'
	GROUP BY month
	ORDER BY month";
$dataJoinMonth = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataJoinMonth[] = $row;
}

$query = "SELECT
		LEFT( m.signdate, 10 ) day
		, COUNT(1) total
	FROM
		member m
	WHERE
		m.memgrade = 100
		AND m.id <> 'outmember'
		AND m.signdate >= '$sdate 00:00:00' AND m.signdate <= '$edate 23:59:59'
	GROUP BY day
	ORDER BY day";
$dataJoinDay = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataJoinDay[] = $row;
}

$query = "SELECT
	CASE
		WHEN age < 21 THEN '0_21세 미만'
		WHEN age < 26 THEN '1_21~25세'
		WHEN age < 30 THEN '2_26~29세'
		WHEN age < 40 THEN '3_30대'
		WHEN age < 50 THEN '4_40대'
		WHEN age < 60 THEN '5_50대'
		ELSE '6_60세 이상'
	END agetype,
	COUNT(1) member
FROM (
		SELECT
			FLOOR(DATEDIFF(NOW(), mem.birth) / 365) age
		FROM
			member mem
		WHERE
			mem.memgrade = 100
			AND mem.signdate >= '$sdate 00:00:00' AND mem.signdate <= '$edate 23:59:59'
		GROUP BY mem.idx
	) t
GROUP BY agetype";
$dataAge = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataAge[] = $row;
}

$query = "SELECT
		gender,
		COUNT(1) member
	FROM (
			SELECT
				CASE
					WHEN mem.sex = 'M' THEN '남성'
					ELSE '여성'
				END gender
			FROM
				member mem
			WHERE
				mem.memgrade = 100
				AND mem.signdate >= '$sdate 00:00:00' AND mem.signdate <= '$edate 23:59:59'
			GROUP BY mem.idx
		) t
	GROUP BY gender";
$dataGender = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataGender[] = $row;
}

?>
<form id="search" name="search" action="/stat.php" method="get">
	<input type="hidden" name="code" value="<?=$code ?>" />
	<table class="detailTable2">
		<tbody>
		<tr>
			<th>가입기간</th>
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

<script type="text/javascript">
	$(function() {
		var chartOption = function(idx, title, dateFormat, parseDate, unit, minPeriod) {
			return $.extend(true, {}, defaultChartOption, {
				type: 'serial',
				titles: [
					$.extend(true, {}, defaultChartOption.titles, {id: 'title'+idx, text: title})
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
					}
				},
				chartCursor: {
					categoryBalloonDateFormat: dateFormat
				},
				chartScrollbar: {
					enabled: true,
					graph: 'total'
				}
			})
		};

		var chart1 = AmCharts.makeChart('chart1', $.extend(true, {}, chartOption(2, '월별 회원 가입', 'YYYY-MM', true, '명', 'MM'), {
			dataDateFormat: 'YYYY-MM',
			categoryField: '월',
			graphs: [
				{
					id: 'total',
					type: 'column',
					lineThickness: 0,
					title: '가입',
					valueField: '가입',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[1],
					balloonText: '가입: [[total]]명',
					balloonFunction: function(item, graph) {
						return '<div style="text-align:left;font-weight:bold;">'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[1] + ';">가입: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['가입']).numberFormat(0) + '명</span></p>'
							+ '<p style="margin:4px 0 0;padding:4px 0 0;border-top:solid 1px #999;color:' + defaultChartOption.balloonColor + ';">누적: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['누적']).numberFormat(0) + '명</span></p>'
							+ '</div>'
					},
					balloonColor: defaultChartOption.balloonColor
				},
				{
					id: 'sum',
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
					balloonText: ''
				}
			],
			dataProvider: [
				<?
				$sum = $old;
				$average = 0;
				foreach($dataJoinMonth as $data) {
					$average += $data['total'];
					$sum += $data['total'];
					?>
				{
					'월': '<?=$data['month']?>',
					'가입': <?=$data['total']?>,
					'누적': <?=$sum ?>
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'totalAxis',
					minimum: 0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataJoinMonth)?>,
						label: Number(<?=$average / count($dataJoinMonth)?>).numberFormat(0)

					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'sumAxis',
					minimum:0,
					position: 'right',
					gridAlpha: 0})
			]
		}));
		chart1.addListener('rendered', function(e) {
			e.chart.zoomToIndexes(e.chart.dataProvider.length - 30, e.chart.dataProvider.length - 1);
		});

		var chart2 = AmCharts.makeChart('chart2', $.extend(true, {}, chartOption(2, '일별 회원 가입', 'YYYY-MM-DD', true, '명', 'DD'), {
			dataDateFormat: 'YYYY-MM-DD',
			categoryField: '날짜',
			categoryAxis: {
				autoGuides: {days:[0]}
			},
			graphs: [
				{
					id: 'total',
					type: 'column',
					lineThickness: 0,
					title: '가입',
					valueField: '가입',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[1],
					balloonText: '가입: [[total]]명',
					balloonFunction: function(item, graph) {
						return '<div style="text-align:left;font-weight:bold;">'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[1] + ';">가입: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['가입']).numberFormat(0) + '명</span></p>'
							+ '<p style="margin:4px 0 0;padding:4px 0 0;border-top:solid 1px #999;color:' + defaultChartOption.balloonColor + ';">누적: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['누적']).numberFormat(0) + '명</span></p>'
							+ '</div>'
					},
					balloonColor: defaultChartOption.balloonColor
				},
				{
					id: 'sum',
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
					balloonText: ''
				}
			],
			dataProvider: [
				<?
				$sum = $old;
				$average = 0;
				foreach($dataJoinDay as $data) {
					$sum += $data['total'];
					$average += $data['total'];
					?>
				{
					'날짜': '<?=$data['day']?>',
					'가입': <?=$data['total']?>,
					'누적': <?=$sum ?>
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'totalAxis',
					minimum: 0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / $days?>,
						label: Number(<?=$average / $days?>).numberFormat(0)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'sumAxis',
					minimum:0,
					position: 'right',
					gridAlpha: 0})
			]
		}));
		chart2.addListener('rendered', function(e) {
			e.chart.zoomToDates(moment('<?=$edate?>').subtract(1, 'M').toDate(), moment('<?=$edate?>').add(1, 'd').toDate());
		});

		var chart3 = AmCharts.makeChart('chart3', $.extend(true, {}, chartOption(2, '연령(현재 기준)별 회원 가입', 'YYYY-MM-DD', false, '명', 'DD'), {
			categoryField: '나이',
			categoryAxis: {
				autoGuides: {days:[0]}
			},
			graphs: [
				{
					id: 'total',
					type: 'column',
					lineThickness: 0,
					title: '가입',
					valueField: '가입',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[1],
					balloonText: '',
					balloonFunction: function(item, graph) {
						return '<div style="text-align:left;font-weight:bold;">'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[1] + ';">가입: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['가입']).numberFormat(0) + '명</span></p>'
							+ '</div>'
					},
					balloonColor: defaultChartOption.balloonColor
				}
			],
			dataProvider: [
				<?
				foreach($dataAge as $data) {
				?>
				{
					'나이': '<?=substr($data['agetype'], 2)?>',
					'가입': <?=$data['member']?>
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'totalAxis',
					minimum: 0,
					stackType: 'regular'
				})
			],
			chartScrollbar: {
				enabled: false
			}
		}));

		var chart4 = AmCharts.makeChart('chart4', $.extend(true, {}, chartOption(2, '성별 회원 가입', 'YYYY-MM-DD', false, '명', 'DD'), {
			categoryField: '성별',
			categoryAxis: {
				autoGuides: {days:[0]}
			},
			graphs: [
				{
					id: 'total',
					type: 'column',
					lineThickness: 0,
					title: '가입',
					valueField: '가입',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[1],
					balloonText: '',
					balloonFunction: function(item, graph) {
						return '<div style="text-align:left;font-weight:bold;">'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[1] + ';">가입: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['가입']).numberFormat(0) + '명</span></p>'
							+ '</div>'
					},
					balloonColor: defaultChartOption.balloonColor
				}
			],
			dataProvider: [
				<?
				foreach($dataGender as $data) {
				?>
				{
					'성별': '<?=$data['gender']?>',
					'가입': <?=$data['member']?>
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'totalAxis',
					minimum: 0,
					stackType: 'regular'
				})
			],
			chartScrollbar: {
				enabled: false
			}
		}));
	});
</script>
