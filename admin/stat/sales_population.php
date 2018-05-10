<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

//연령별 예약
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
		COUNT(1) member,
		SUM(reservation_short) reservation_short,
		SUM(reservation_long) reservation_long
	FROM (
			SELECT
				FLOOR(DATEDIFF(m.dt_create, birth) / 365) age,
				SUM(
					CASE
						WHEN mt.retype = 1 THEN 1
						WHEN mt.retype = 2 THEN 0
						ELSE 0
					END
				) reservation_short,
				SUM(
					CASE
						WHEN mt.retype = 1 THEN 0
						WHEN mt.retype = 2 THEN 1
						ELSE 0
					END
				) reservation_long
			FROM payments m
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				LEFT JOIN member mem ON m.member_idx = mem.idx
			WHERE
				m.dan < 5
				AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
			GROUP BY mem.idx
		) t
	GROUP BY agetype";
$reservationAge = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$reservationAge[] = $row;
}

//연령별 매출
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
	COUNT(1) member,
	SUM(sales_short) sales_short,
	SUM(sales_long) sales_long
FROM (
		SELECT
			FLOOR(DATEDIFF(m.dt_create, birth) / 365) age,
			SUM(
				CASE
					WHEN mt.retype = 1 THEN ma.account - ma.isouts
					ELSE 0
				END
			) sales_short,
			SUM(
				CASE
					WHEN mt.retype = 2 THEN ma.account - ma.isouts
					ELSE 0
				END
			) sales_long
		FROM payment_accounts ma
			LEFT JOIN payments m ON ma.payment_idx = m.idx
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN member mem ON m.member_idx = mem.idx
		WHERE
			ma.tbtype = 'I'
			AND ma.account > ma.isouts
			AND ma.dt_create >= '$sdate 00:00:00' AND ma.dt_create <= '$edate 23:59:59'
		GROUP BY mem.idx
	) t
GROUP BY agetype";
$salesAge = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$salesAge[] = $row;
}

//성별 예약
$query = "SELECT
		gender,
		COUNT(1) member,
		SUM(reservation_short) reservation_short,
		SUM(reservation_long) reservation_long
	FROM (
			SELECT
				CASE
					WHEN mem.sex = 'M' THEN '남성'
					ELSE '여성'
				END gender,
				SUM(
					CASE
						WHEN mt.retype = 1 THEN 1
						WHEN mt.retype = 2 THEN 0
						ELSE 0
					END
				) reservation_short,
				SUM(
					CASE
						WHEN mt.retype = 1 THEN 0
						WHEN mt.retype = 2 THEN 1
						ELSE 0
					END
				) reservation_long
			FROM payments m
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				LEFT JOIN member mem ON m.member_idx = mem.idx
			WHERE
				m.dan < 5
				AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
			GROUP BY mem.idx
		) t
	GROUP BY gender";
$reservationGender = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$reservationGender[] = $row;
}

//성별 매출
$query = "SELECT
	gender,
	COUNT(1) member,
	SUM(sales_short) sales_short,
	SUM(sales_long) sales_long
FROM (
		SELECT
			CASE
				WHEN mem.sex = 'M' THEN '남성'
				ELSE '여성'
			END gender,
			SUM(
				CASE
					WHEN mt.retype = 1 THEN ma.account - ma.isouts
					ELSE 0
				END
			) sales_short,
			SUM(
				CASE
					WHEN mt.retype = 2 THEN ma.account - ma.isouts
					ELSE 0
				END
			) sales_long
		FROM payment_accounts ma
			LEFT JOIN payments m ON ma.payment_idx = m.idx
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN member mem ON m.member_idx = mem.idx
		WHERE
			ma.tbtype = 'I'
			AND ma.account > ma.isouts
			AND ma.dt_create >= '$sdate 00:00:00' AND ma.dt_create <= '$edate 23:59:59'
		GROUP BY mem.idx
	) t
GROUP BY gender";
$salesGender = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$salesGender[] = $row;
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

<script type="text/javascript">
	$(function() {
		var chartOption = function(option) {
			return $.extend(true, {}, defaultChartOption, {
				type: 'serial',
				titleField: '타입',
				valueField: '합계',
				legend: {
					enabled: false
				},
				categoryAxis: {
					gridPosition: 'start'
				},
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
			}, option);
		};

		var chart1 = AmCharts.makeChart('chart1', chartOption({
			categoryField: '나이',
			titles: [
				$.extend(true, {}, defaultChartOption.titles, {id: 'title', text: '연령(예약일 기준)대별 예약'})
			],
			graphs: [
				{
					id: 'long3',
					type: 'column',
					lineThickness: 0,
					title: '장기',
					valueField: '장기',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[2],
					balloonText: ''
				},
				{
					id: 'short3',
					type: 'column',
					lineThickness: 0,
					title: '단기',
					valueField: '단기',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[3],
					balloonText: '',
					balloonColor: defaultChartOption.balloonColor,
					balloonFunction: function(item, graph) {
						return '<div style="text-align:left;font-weight:bold;">'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[3] + ';">단기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['단기']).numberFormat(0) + '건</span></p>'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[2] + ';">장기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['장기']).numberFormat(0) + '건</span></p>'
							+ '<p style="margin:4px 0 0;padding:4px 0 0;border-top:solid 1px #999;">합계: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['합계']).numberFormat(0) + '건</span></p>'
							+ '</div>'
					}
				}
			],
			dataProvider: [
				<?
				foreach($reservationAge as $data) {
				?>
				{
					'나이': '<?=substr($data['agetype'], 2)?>',
					'회원': <?=$data['member']?>,
					'단기': <?=$data['reservation_short'] ?>,
					'장기': <?=$data['reservation_long'] ?>,
					'합계': <?=$data['reservation_short'] + $data['reservation_long'] ?>
				},
				<?}?>
			]
		}));

		var chart2 = AmCharts.makeChart('chart2', chartOption({
			categoryField: '나이',
			titles: [
				$.extend(true, {}, defaultChartOption.titles, {id: 'title', text: '연령(예약일 기준)대별 매출'})
			],
			graphs: [
				{
					id: 'long3',
					type: 'column',
					lineThickness: 0,
					title: '장기',
					valueField: '장기',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[0],
					balloonText: ''
				},
				{
					id: 'short3',
					type: 'column',
					lineThickness: 0,
					title: '단기',
					valueField: '단기',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[1],
					balloonText: '',
					balloonColor: defaultChartOption.balloonColor,
					balloonFunction: function(item, graph) {
						return '<div style="text-align:left;font-weight:bold;">'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[1] + ';">단기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['단기']).numberFormat(0) + '원</span></p>'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[0] + ';">장기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['장기']).numberFormat(0) + '원</span></p>'
							+ '<p style="margin:4px 0 0;padding:4px 0 0;border-top:solid 1px #999;">합계: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['합계']).numberFormat(0) + '원</span></p>'
							+ '</div>'
					}
				}
			],
			dataProvider: [
				<?
				foreach($salesAge as $data) {
				?>
				{
					'나이': '<?=substr($data['agetype'], 2)?>',
					'회원': <?=$data['member']?>,
					'단기': <?=$data['sales_short'] ?>,
					'장기': <?=$data['sales_long'] ?>,
					'합계': <?=$data['sales_short'] + $data['sales_long'] ?>
				},
				<?}?>
			]
		}));

		var chart3 = AmCharts.makeChart('chart3', chartOption({
			categoryField: '성별',
			titles: [
				$.extend(true, {}, defaultChartOption.titles, {id: 'title', text: '성별 예약'})
			],
			graphs: [
				{
					id: 'long3',
					type: 'column',
					lineThickness: 0,
					title: '장기',
					valueField: '장기',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[2],
					balloonText: ''
				},
				{
					id: 'short3',
					type: 'column',
					lineThickness: 0,
					title: '단기',
					valueField: '단기',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[3],
					balloonText: '',
					balloonColor: defaultChartOption.balloonColor,
					balloonFunction: function(item, graph) {
						return '<div style="text-align:left;font-weight:bold;">'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[3] + ';">단기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['단기']).numberFormat(0) + '건</span></p>'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[2] + ';">장기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['장기']).numberFormat(0) + '건</span></p>'
							+ '<p style="margin:4px 0 0;padding:4px 0 0;border-top:solid 1px #999;">합계: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['합계']).numberFormat(0) + '건</span></p>'
							+ '</div>'
					}
				}
			],
			dataProvider: [
				<?
				foreach($reservationGender as $data) {
				?>
				{
					'성별': '<?=$data['gender']?>',
					'회원': <?=$data['member']?>,
					'단기': <?=$data['reservation_short'] ?>,
					'장기': <?=$data['reservation_long'] ?>,
					'합계': <?=$data['reservation_short'] + $data['reservation_long'] ?>
				},
				<?}?>
			]
		}));

		var chart4 = AmCharts.makeChart('chart4', chartOption({
			categoryField: '성별',
			titles: [
				$.extend(true, {}, defaultChartOption.titles, {id: 'title', text: '성별 매출'})
			],
			graphs: [
				{
					id: 'long3',
					type: 'column',
					lineThickness: 0,
					title: '장기',
					valueField: '장기',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[0],
					balloonText: ''
				},
				{
					id: 'short3',
					type: 'column',
					lineThickness: 0,
					title: '단기',
					valueField: '단기',
					valueAxis: 'totalAxis',
					fillAlphas: 0.9,
					lineColor: defaultChartOption.colors[1],
					balloonText: '',
					balloonColor: defaultChartOption.balloonColor,
					balloonFunction: function(item, graph) {
						return '<div style="text-align:left;font-weight:bold;">'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[1] + ';">단기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['단기']).numberFormat(0) + '원</span></p>'
							+ '<p style="margin:0;color:' + defaultChartOption.colors[0] + ';">장기: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['장기']).numberFormat(0) + '원</span></p>'
							+ '<p style="margin:4px 0 0;padding:4px 0 0;border-top:solid 1px #999;">합계: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['합계']).numberFormat(0) + '원</span></p>'
							+ '</div>'
					}
				}
			],
			dataProvider: [
				<?
				foreach($salesGender as $data) {
				?>
				{
					'성별': '<?=$data['gender']?>',
					'회원': <?=$data['member']?>,
					'단기': <?=$data['sales_short'] ?>,
					'장기': <?=$data['sales_long'] ?>,
					'합계': <?=$data['sales_short'] + $data['sales_long'] ?>
				},
				<?}?>
			]
		}));

	});
</script>
