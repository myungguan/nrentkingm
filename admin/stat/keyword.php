<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

$query = "SELECT
		(CASE
			WHEN lv.referer LIKE '%search.naver%' THEN REPLACE(SUBSTRING(lv.referer, LOCATE('query=', lv.referer)+6, CASE WHEN LOCATE('&', lv.referer, LOCATE('query=', lv.referer)) = 0 THEN 100 ELSE LOCATE('&', lv.referer, LOCATE('query=', lv.referer)) - LOCATE('query=', lv.referer) END - 6), ' ', '')
			WHEN lv.referer LIKE '%search.daum%' THEN REPLACE(SUBSTRING(lv.referer, LOCATE('q=', lv.referer)+2, CASE WHEN LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) = 0 THEN 100 ELSE LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) - LOCATE('q=', lv.referer) END - 2), ' ', '')
			WHEN lv.referer LIKE '%www.bing.com/search%' THEN REPLACE(SUBSTRING(lv.referer, LOCATE('q=', lv.referer)+2, CASE WHEN LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) = 0 THEN 100 ELSE LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) - LOCATE('q=', lv.referer) END - 2), ' ', '')
		END) query,
		COUNT(*) visit
	FROM
		log_visit lv
	WHERE
		lv.dt >= '$sdate 00:00:00' AND lv.dt <= '$edate 23:59:59'
		AND (
			lv.referer LIKE '%search.naver%' OR
			lv.referer LIKE '%search.daum%' OR
			 lv.referer LIKE '%www.bing.com/search%'
		)
	GROUP BY query
	ORDER BY visit DESC LIMIT 0, 200";
$r = mysql_query($query);

$dataStat = [];
while($row = mysql_fetch_assoc($r)) {
	$dataStat[$row['query']] = array('visit' => $row['visit'], 'reservation' => 0);
}

$query = "SELECT
		(CASE
			WHEN lv.referer LIKE '%search.naver%' THEN REPLACE(SUBSTRING(lv.referer, LOCATE('query=', lv.referer)+6, CASE WHEN LOCATE('&', lv.referer, LOCATE('query=', lv.referer)) = 0 THEN 100 ELSE LOCATE('&', lv.referer, LOCATE('query=', lv.referer)) - LOCATE('query=', lv.referer) END - 6), ' ', '')
			WHEN lv.referer LIKE '%search.daum%' THEN REPLACE(SUBSTRING(lv.referer, LOCATE('q=', lv.referer)+2, CASE WHEN LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) = 0 THEN 100 ELSE LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) - LOCATE('q=', lv.referer) END - 2), ' ', '')
			WHEN lv.referer LIKE '%www.bing.com/search%' THEN REPLACE(SUBSTRING(lv.referer, LOCATE('q=', lv.referer)+2, CASE WHEN LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) = 0 THEN 100 ELSE LOCATE('&', lv.referer, LOCATE('q=', lv.referer)) - LOCATE('q=', lv.referer) END - 2), ' ', '')
		END) query,
		COUNT(*) reservation
	FROM
		(SELECT DISTINCT mt.session_id FROM reservation mt LEFT JOIN payments m ON m.reservation_idx=mt.idx WHERE m.idx IS NOT NULL AND  m.dan < 5 AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59') m
		LEFT JOIN log_visit lv ON m.session_id = lv.session_id
	WHERE
		m.session_id IS NOT NULL
		AND (
			lv.referer LIKE '%search.naver%' OR
			lv.referer LIKE '%search.daum%' OR
			 lv.referer LIKE '%www.bing.com/search%'
		)
	GROUP BY query";
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	if(isset($dataStat[$row['query']]))
		$dataStat[$row['query']]['reservation'] = $row['reservation'] ? $row['reservation'] : 0;
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
				colors: [
					defaultChartOption.colors[1], defaultChartOption.colors[3]
				],
				titles: [
					$.extend(true, {}, defaultChartOption.titles, {id: 'title'+idx, text: title})
				],
				graphs: [
					{
						id: 'visit'+idx,
						type: 'column',
						valueAxis: 'visitAxis',
						lineThickness: 0,
						title: '방문',
						valueField: '방문',
						balloonText: '',
						fillAlphas: 0.9,
						showBalloonAt: 'close',
						balloonFunction: function(item, graph) {
							return '<div style="text-align:left;">'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[1] + ';">방문: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['방문']).numberFormat(0) + '</span></p>'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[3] + ';">예약: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['예약']).numberFormat(0) + '</span></p>'
								+ '</div>'
						},
						balloonColor: defaultChartOption.chartCursor.cursorColor
					},
					{
						id: 'reservation'+idx,
						type: 'column',
						valueAxis: 'reservationAxis',
						lineThickness: 0,
						title: '예약',
						valueField: '예약',
						balloonText: '',
						fillAlphas: 0.9
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
					$.extend(true, {}, defaultChartOption.valueAxes, {id:'visitAxis', position: 'top'}),
					$.extend(true, {}, defaultChartOption.valueAxes, {id:'reservationAxis'})
				]
			})
		};

		var chart1 = AmCharts.makeChart('chart1', $.extend(true, {}, chartOption(2, '유입검색어 Top 200', 'YYYY-MM', false, 'MM'), {
			type: 'serial',
			rotate: true,
			dataDateFormat: 'YYYY-MM',
			categoryField: '검색어',
			dataProvider: [
				<?foreach($dataStat as $key => $value) {?>
				{
					'검색어': '<?=$key?>',
					'방문': <?=$value['visit']?>,
					'예약': <?=$value['reservation']?>,
					dataZero: 0
				},
				<?}?>
			]
		}));
	});
</script>
