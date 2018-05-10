<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

$query = "SELECT DATEDIFF('$edate', (
	SELECT MIN(DATE(dt)) min
	FROM log_search
	WHERE dt >= '$sdate 00:00:00' AND dt <= '$edate 23:59:59'
))+1 days";
$days = mysql_fetch_assoc(mysql_query($query))['days'];

$query = "SELECT
		t.month,
		COUNT(1) total
	FROM (
		SELECT LEFT( dt, 7 ) month, session_id
		FROM log_search
		WHERE dt >= '$sdate 00:00:00' AND dt <= '$edate 23:59:59'
		GROUP BY session_id
	) t
	GROUP BY month
	ORDER BY month";
$dataSessionMonth = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataSessionMonth[] = $row;
}

$query = "SELECT
		t.day,
		COUNT(1) total
	FROM (
		SELECT DATE(dt) day, session_id
		FROM log_search
		WHERE dt >= '$sdate 00:00:00' AND dt <= '$edate 23:59:59'
		GROUP BY session_id
	) t
	GROUP BY day
	ORDER BY day";
$dataSessionDay = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataSessionDay[] = $row;
}

$area = "CASE
		WHEN t.addr LIKE '서울%' THEN REPLACE(t.addr, '서울특별시', '서울')
		WHEN t.addr LIKE '부산%' THEN REPLACE(t.addr, '부산광역시', '부산')
		WHEN t.addr LIKE '대구%' THEN REPLACE(t.addr, '대구광역시', '대구')
		WHEN t.addr LIKE '인천%' THEN REPLACE(t.addr, '인천광역시', '인천')
		WHEN t.addr LIKE '광주%' THEN REPLACE(t.addr, '광주광역시', '인천')
		WHEN t.addr LIKE '대전%' THEN REPLACE(t.addr, '대전광역시', '대전')
		WHEN t.addr LIKE '울산%' THEN REPLACE(t.addr, '울산광역시', '울산')
		WHEN t.addr LIKE '경기%' THEN REPLACE(t.addr, '경기도', '경기')
		WHEN t.addr LIKE '강원%' THEN REPLACE(t.addr, '강원도', '강원')
		WHEN t.addr LIKE '충청북도%' THEN REPLACE(t.addr, '충청북도', '충북')
		WHEN t.addr LIKE '충청남도%' THEN REPLACE(t.addr, '충청남도', '충남')
		WHEN t.addr LIKE '전라북도%' THEN REPLACE(t.addr, '전라북도', '전북')
		WHEN t.addr LIKE '전라남도%' THEN REPLACE(t.addr, '전라남도', '전남')
		WHEN t.addr LIKE '경상북도%' THEN REPLACE(t.addr, '경상북도', '경북')
		WHEN t.addr LIKE '경상남도%' THEN REPLACE(t.addr, '경상남도', '경남')
		WHEN t.addr LIKE '제주%' THEN REPLACE(t.addr, '제주특별자치도', '제주')
		WHEN t.addr LIKE '세종%' THEN REPLACE(t.addr, '세종특별자치시', '세종')
		ELSE t.addr
	END";

$query = "SELECT
		$area area,
		COUNT(1) total
	FROM (
		SELECT
			session_id, SUBSTRING_INDEX(addr, ' ', 1) addr
		FROM log_search
		WHERE
			dt >= '$sdate 00:00:00' AND dt <= '$edate 23:59:59'
			AND count = 1
			AND addr <> ''
		GROUP BY session_id, addr
	) t
	GROUP BY area
	ORDER BY total DESC";
$dataSearchArea = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataSearchArea[] = $row;
}

$query = "SELECT t2.area, t2.result/t2.search total
	FROM (
		SELECT
			$area area,
			COUNT(1) search,
			SUM(total) result
		FROM (
			SELECT
				session_id, SUBSTRING_INDEX(addr, ' ', 1) addr, total
			FROM log_search
			WHERE
				dt >= '$sdate 00:00:00' AND dt <= '$edate 23:59:59'
				AND count = 1
				AND addr <> ''
		) t
		GROUP BY
			area) t2
	ORDER BY total DESC";
$dataSearchResult = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataSearchResult[] = $row;
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
<!--
<div style="height:300px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart3" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart4" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>
-->
<div style="height:250px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart5" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart6" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var chartIdx = 0;
		var chartOption = function(title, dateFormat, parseDate, unit, minPeriod, data1, data2) {
			chartIdx++;
			var option = {
				type: 'serial',
				titles: [
					$.extend(true, {}, defaultChartOption.titles, {id: 'title'+chartIdx, text: title})
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
//					maximumDate: moment().format('YYYY-MM-DD')
				},
				chartCursor: {
					categoryBalloonDateFormat: dateFormat
				},
				chartScrollbar: {
					enabled: true,
					graph: 'total'+chartIdx
				}
			};

			var graphs = [];
			if(data1) {
				graphs.push({
					id: 'total'+chartIdx,
					type: 'column',
					lineThickness: 0,
					title: data1,
					valueField: data1,
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[1],
					balloonFunction: function(item, graph) {
						var balloon = '<div style="text-align:left;font-weight:bold;">'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[1] + ';">' + data1 + ': <span style="float:right;margin-left:10px;">' + Number(item.dataContext[data1]).numberFormat(0) + unit + '</span></p>';
						if(data2) {
							balloon += '<p style="margin:4px 0 0;padding:4px 0 0;border-top:solid 1px #999;color:' + defaultChartOption.balloonColor + ';">' + data2 + ': <span style="float:right;margin-left:10px;">' + Number(item.dataContext[data2]).numberFormat(0) + unit + '</span></p>';
						}
						balloon += '</div>';

						return balloon
					},
					balloonColor: defaultChartOption.balloonColor
				})
			}

			if(data2) {
				graphs.push({
					id: 'sum'+chartIdx,
					bullet: 'round',
					bulletAlpha: 0.9,
					hideBulletsCount: 36,
					lineThickness: 3,
					title: data2,
					valueField: data2,
					valueAxis: 'sumAxis',
					fillAlphas: 0,
					lineAlpha: 0.9,
					lineColor: defaultChartOption.balloonColor,
					balloonText: ''
				})
			}
			option['graphs'] = graphs;

			return $.extend(true, {}, defaultChartOption, option);
		};

		var chart1 = AmCharts.makeChart('chart1', $.extend(true, {}, chartOption('월별 검색 세션', 'YYYY-MM', true, '', 'MM', '세션', null), {
			dataDateFormat: 'YYYY-MM',
			categoryField: '월',
			dataProvider: [
				<?
				$average = 0;
				foreach($dataSessionMonth as $data) {
				$average += $data['total'];
				?>
				{
					'월': '<?=$data['month']?>',
					'세션': <?=$data['total']?>
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'totalAxis',
					minimum: 0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataSessionMonth)?>,
						label: Number(<?=$average / count($dataSessionMonth)?>).numberFormat(0)

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

		var chart2 = AmCharts.makeChart('chart2', $.extend(true, {}, chartOption('일별 검색 세션', 'YYYY-MM-DD', true, '', 'DD', '세션', null), {
			dataDateFormat: 'YYYY-MM-DD',
			categoryField: '날짜',
			categoryAxis: {
				autoGuides: {days:[0]}
			},
			dataProvider: [
				<?
				$average = 0;
				foreach($dataSessionDay as $data) {
				$average += $data['total'];
				?>
				{
					'날짜': '<?=$data['day']?>',
					'세션': <?=$data['total']?>
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

		var chart5 = AmCharts.makeChart('chart5', $.extend(true, {}, chartOption('지역별 검색 세션', 'YYYY-MM-DD', false, '', null, '세션', null), {
			categoryField: '지역',
			categoryAxis: {
				autoWrap: false,
				minHorizontalGap: 0,
				autoGuides: {days:[0]}
			},
			dataProvider: [
				<?
				foreach($dataSearchArea as $data) {
				?>
				{
					'지역': '<?=$data['area']?>',
					'세션': <?=$data['total']?>
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'totalAxis',
					minimum: 0
				})
			],
			chartScrollbar: {
				enabled: false
			}
		}));

		var chart6 = AmCharts.makeChart('chart6', $.extend(true, {}, chartOption('지역별 평균 노출 차량', 'YYYY-MM-DD', false, '개', null, '차량', null), {
			categoryField: '지역',
			categoryAxis: {
				autoWrap: false,
				minHorizontalGap: 0,
				autoGuides: {days:[0]}
			},
			dataProvider: [
				<?
				foreach($dataSearchResult as $data) {
				?>
				{
					'지역': '<?=$data['area']?>',
					'차량': <?=$data['total']?>
				},
				<?}?>
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {
					id: 'totalAxis',
					minimum: 0
				})
			],
			chartScrollbar: {
				enabled: false
			}
		}));
	});
</script>
