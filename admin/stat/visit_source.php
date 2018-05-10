<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

if($sdate < '2017-08-01')
	$sdate = '2017-08-01';

$referer = "(CASE
		WHEN lv.user_agent LIKE '%rentking%' THEN 'App'
		WHEN lv.user_agent LIKE '%promotion/%' THEN SUBSTR(SUBSTR(user_agent, LOCATE('promotion/', user_agent)), 11, 100) 
		WHEN lv.referer = '' OR lv.referer LIKE 'http://www.rentking.co.kr/%' OR lv.referer LIKE 'http://m.rentking.co.kr/%' OR lv.referer LIKE 'https://www.rentking.co.kr/%' OR lv.referer LIKE 'https://m.rentking.co.kr/%'  THEN '직접방문'
		WHEN lv.url LIKE '/?gclid%' THEN 'Google'
		WHEN lv.referer LIKE '%google.co%' THEN 'Google'
		WHEN lv.referer LIKE '%search.naver%' AND (lv.url LIKE '/?NaPm%' OR lv.url LIKE '/?n_media=%') THEN 'Naver검색'
		WHEN lv.referer LIKE '%search.naver%' THEN 'Naver검색'
		WHEN lv.referer LIKE '%kin.naver%' THEN 'Naver지식인'
		WHEN lv.referer LIKE '%naver%' THEN 'Naver기타'
		WHEN lv.referer LIKE '%daum.net%' THEN 'Daum'
		WHEN lv.referer LIKE '%facebook%' THEN 'Facebook'
		WHEN lv.referer LIKE '%linkprice.com%' THEN 'LinkPrice'
		WHEN lv.referer LIKE '%ilikeclick.com%' THEN 'ILikeClick'
		WHEN lv.referer LIKE '%journalle.com%' THEN '10PING'
	#	WHEN lv.referer LIKE '%dreamad.co.kr%' THEN 'dreamad'
	#	WHEN lv.referer LIKE '%ncclick.co.kr%' THEN 'ncclick'
	#	WHEN lv.referer LIKE '%dwclick.co.kr%' THEN 'dwclick'
		ELSE '기타'
	END)";

$query = "SELECT
		$referer r,
		COUNT(*) total
	FROM
		(SELECT DISTINCT session_id FROM log_pageview WHERE dt >= '$sdate 00:00:00' AND dt <= '$edate 23:59:59') lp
		LEFT JOIN log_visit lv ON lp.session_id = lv.session_id
	GROUP BY r
	ORDER BY total DESC";
$r = mysql_query($query);

$dataStat = [];
while($row = mysql_fetch_assoc($r)) {
	$dataStat[$row['r']] = array('total' => $row['total'], 'reservation' => 0, 'join_member' => 0, 'search' => 0);
}

$query = "SELECT
		$referer r,
		COUNT(*) join_member
	FROM
		member m
		LEFT JOIN log_visit lv ON m.join_session_id = lv.session_id
	WHERE
		m.join_session_id IS NOT NULL
		AND m.signdate >= '$sdate 00:00:00' AND m.signdate <= '$edate 23:59:59'
	GROUP BY r";
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	if(isset($dataStat[$row['r']]))
		$dataStat[$row['r']]['join_member'] = $row['join_member'];
}

$query = "SELECT
		$referer r,
		COUNT(*) search
	FROM
		(SELECT DISTINCT session_id FROM log_search WHERE dt >= '$sdate 00:00:00' AND dt <= '$edate 23:59:59') ls
		LEFT JOIN log_visit lv ON ls.session_id = lv.session_id
	GROUP BY r";
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	if(isset($dataStat[$row['r']]))
		$dataStat[$row['r']]['search'] = $row['search'];
}

$query = "SELECT
		$referer r,
		COUNT(*) reservation
	FROM payments p
		LEFT JOIN reservation rv ON p.reservation_idx = rv.idx
		LEFT JOIN log_visit lv ON rv.session_id = lv.session_id
	WHERE
		p.dan < 5 AND p.dt_create >= '$sdate 00:00:00' AND p.dt_create <= '$edate 23:59:59'
		AND lv.session_id IS NOT NULL
	GROUP BY r";
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	if(isset($dataStat[$row['r']]))
		$dataStat[$row['r']]['reservation'] = $row['reservation'];
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

<div id="chart1" style="height:<?=count($dataStat) * 80?>px;margin-top:16px;border:solid 1px #cdd0d5;background:#fff;"></div>

<script type="text/javascript">
	$(function() {
		var chartOption = function(idx, title, dateFormat, parseDate, minPeriod) {
			return $.extend(true, {}, defaultChartOption, {
				colors: [
					defaultChartOption.colors[1], defaultChartOption.colors[0], defaultChartOption.colors[3], defaultChartOption.colors[2]
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
							return '<div style="text-align:left;width:140px;">'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[1] + ';">방문: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['방문']).numberFormat(0) + '</span></p>'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[0] + ';">검색: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['검색']).numberFormat(0) + '(' + (Number(item.dataContext['검색'])/Number(item.dataContext['방문'])*100).numberFormat(1) +'%)</span></p>'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[3] + ';">회원가입: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['회원가입']).numberFormat(0) + '(' + (Number(item.dataContext['회원가입'])/Number(item.dataContext['방문'])*100).numberFormat(1) +'%)</span></p>'
								+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[2] + ';">예약: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['예약']).numberFormat(0) + '(' + (Number(item.dataContext['예약'])/Number(item.dataContext['방문'])*100).numberFormat(1) +'%)</span></p>'
								+ '</div>'
						},
						balloonColor: defaultChartOption.chartCursor.cursorColor
					},
					{
						id: 'search'+idx,
						type: 'column',
						valueAxis: 'visitAxis',
						lineThickness: 0,
						title: '검색',
						valueField: '검색',
						balloonText: '',
						fillAlphas: 0.9,
						showBalloonAt: 'close',
						balloonColor: defaultChartOption.chartCursor.cursorColor
					},
					{
						id: 'join'+idx,
						type: 'column',
						valueAxis: 'reservationAxis',
						lineThickness: 0,
						title: '회원가입',
						valueField: '회원가입',
						balloonText: '',
						fillAlphas: 0.9,
						showBalloonAt: 'close',
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

		var chart1 = AmCharts.makeChart('chart1', $.extend(true, {}, chartOption(2, '방문 소스', 'YYYY-MM', false, 'MM'), {
			type: 'serial',
			rotate: true,
			dataDateFormat: 'YYYY-MM',
			categoryField: '소스',
			dataProvider: [
				<?foreach($dataStat as $key => $value) {?>
				{
					'소스': '<?=$key?>',
					'방문': <?=$value['total']?>,
					'검색': <?=$value['search']?>,
					'회원가입': <?=$value['join_member']?>,
					'예약': <?=$value['reservation']?>,
					dataZero: 0
				},
				<?}?>
			]
		}));
	});
</script>
