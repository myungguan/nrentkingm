<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}


//예약기간별 통계
$query = "SELECT
		CASE
			WHEN t.days < 1 THEN 1
			WHEN t.days >= 1 AND t.days < 7 THEN t.days
			WHEN t.days >= 7 AND t.days < 14 THEN 7
			WHEN t.days >= 14 AND t.days < 30 THEN 14
			WHEN t.days >= 30 AND t.days < 90 THEN 30
			WHEN t.days >= 90 AND t.days < 180 THEN 90
			ELSE 180
		END d,
		COUNT(*) total
	FROM (
		SELECT
			CEIL(TIMESTAMPDIFF(HOUR, mt.sdate, mt.edate) / 24) days
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		WHERE
			m.dan < 4
			AND mt.idx IS NOT NULL
			AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	) t
	GROUP BY d
	ORDER BY d";
$dataDuration = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataDuration[] = $row;
}

//예약/픽업일 기간별 통계
$query = "SELECT
	t3.*,
	t3.취소 / t3.전체 취소율
FROM (
	SELECT
		t2.term 기간,
		COUNT(1) 전체,
		SUM(CASE WHEN t2.dan <= 3 THEN 1 ELSE 0 END) 전체예약,
		SUM(CASE WHEN t2.dan = 1 THEN 1 ELSE 0 END) 예약,
		SUM(CASE WHEN t2.dan = 2 THEN 1 ELSE 0 END) 대여,
		SUM(CASE WHEN t2.dan = 3 THEN 1 ELSE 0 END) 반납,
		SUM(CASE WHEN t2.dan > 3 THEN 1 ELSE 0 END) 취소
	FROM (
		SELECT
			CASE
				WHEN t.term <=6 THEN 6
				WHEN t.term > 6 AND t.term <= 12 THEN 12
				WHEN t.term > 12 AND t.term <= 24 THEN 24
				WHEN t.term > 24 AND t.term <= 48 THEN 48
				WHEN t.term > 48 AND t.term <= 96 THEN 96
				WHEN t.term > 96 AND t.term <= 168 THEN 168
				WHEN t.term > 168 AND t.term <= 336 THEN 336
				WHEN t.term > 336 AND t.term <= 720 THEN 720
				ELSE 1440
			END term,
			t.dan,
			t.retype
		FROM (
			SELECT
				TIMESTAMPDIFF(HOUR, m.dt_create, mt.sdate) term,
				m.dan,
				mt.retype
			FROM payments m
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			WHERE
				mt.sdate IS NOT NULL
				AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
			) t
		) t2
		GROUP BY t2.term
	) t3
ORDER BY t3.기간";
$dataReservationPickup = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataReservationPickup[] = $row;
}

//가입자 예약 전환
$query = "SELECT
		CASE WHEN t.reservation > 0 THEN '예약' ELSE '예약안함' END type,
		COUNT(1) total
	FROM (
		SELECT
			m.name,
			(SELECT COUNT(1) FROM payments WHERE /* dan < 5 AND */ member_idx = m.idx /* AND dt_create >= '$sdate 00:00:00' AND dt_create <= '$edate 23:59:59' */) reservation
		FROM
			member m
		WHERE
			m.id <> 'outmember'
			AND m.memgrade = 100
			AND m.signdate >= '$sdate 00:00:00' AND m.signdate <= '$edate 23:59:59'
		) t
	GROUP BY type
	ORDER BY total";
$dataConversion = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataConversion[] = $row;
}

//취소율
$query = "SELECT
		CASE WHEN m.dan < 5 THEN '예약' ELSE '취소' END type,
		COUNT(1) total
	FROM payments m
	WHERE
		m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY type
	ORDER BY type DESC";
$dataCancel = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataCancel[] = $row;
}

//재구매
$query = "SELECT
		CASE WHEN t.sum > 2 THEN 3 ELSE t.sum END type,
		COUNT(1) total
	FROM (
		SELECT
			m.member_idx,
			COUNT(1) sum
		FROM payments m
		WHERE
			m.dan < 5
			AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
		GROUP BY m.member_idx
	) t
	GROUP BY type
	ORDER BY type DESC";
$dataReservation = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataReservation[] = $row;
}

//예약전환 시간
$query = "SELECT
		CASE
			WHEN t.type = 1 THEN '6시간 이내'
			WHEN t.type = 2 THEN '12시간 이내'
			WHEN t.type = 3 THEN '1일 이내'
			WHEN t.type = 4 THEN '2일 이내'
			WHEN t.type = 5 THEN '4일 이내'
			WHEN t.type = 6 THEN '7일 이내'
			ELSE '7일 초과'
		END type,
		COUNT(1) total
	FROM (
		SELECT
			CASE
				WHEN TIMESTAMPDIFF(HOUR, mem.signdate, m.dt_create) <= 6 THEN 1
				WHEN TIMESTAMPDIFF(HOUR, mem.signdate, m.dt_create) <= 12 THEN 2
				WHEN TIMESTAMPDIFF(HOUR, mem.signdate, m.dt_create) <= 24 THEN 3
				WHEN TIMESTAMPDIFF(HOUR, mem.signdate, m.dt_create) <= 48 THEN 4
				WHEN TIMESTAMPDIFF(HOUR, mem.signdate, m.dt_create) <= 96 THEN 5
				WHEN TIMESTAMPDIFF(HOUR, mem.signdate, m.dt_create) <= 168 THEN 6
				ELSE 7
			END	type
		FROM payments m
			LEFT JOIN member mem ON m.member_idx = mem.idx
		WHERE
			m.dan < 5
			AND m.dt_create >= '$sdate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	) t
	GROUP BY t.type
	ORDER BY t.type";
$dataConversionTime = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataConversionTime[] = $row;
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

<div id="chart0" style="height:200px;margin-top:16px;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>

<div id="chart5" style="height:200px;margin-top:16px;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>

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

		var chart0 = AmCharts.makeChart('chart0', $.extend(true, {}, defaultChartOption, {
			type: 'serial',
			categoryField: '기간',
			titles: [
				$.extend(true, {}, defaultChartOption.titles, {id: 'title', text: '대여일수별 예약 (1일 1시간 = 2일)'})
			],
			colors: [defaultChartOption.colors[1]],
			graphs: [
				{
					id: 'total',
					type: 'column',
					valueAxis: 'valueAxis',
					lineThickness: 0,
					title: '예약',
					valueField: '예약',
					balloonText: '[[value]]건',
//					balloonFunction: function(item, graph) {
//						return '<div style="text-align:left;font-size:12px;">'
//							+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[0] + ';">전체: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['전체']).numberFormat(0) + '건</span></p>'
//							+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[1] + ';">예약: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['예약']).numberFormat(0) + '건</span></p>'
//							+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[1] + ';">대여: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['대여']).numberFormat(0) + '건</span></p>'
//							+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[1] + ';">반납: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['반납']).numberFormat(0) + '건</span></p>'
//							+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[2] + ';">취소: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['취소']).numberFormat(0) + '건</span></p>'
//							+ '<p style="margin:4px 0 0;padding:4px 0 0;font-weight:bold;;color:' + defaultChartOption.colors[3] + ';border-top:solid 1px #999;">취소율: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['취소율']).numberFormat(2) + '%</span></p>'
//							+ '</div>'
//					},
					fillAlphas: 0.9
				}
			],
			chartCursor: {
				categoryBalloonFunction: function(value) {
					if(value < 7)
						return value + '일 이하';
					else if(value == 7)
						return '1주일 이상';
					else if(value == 14)
						return '2주일 이상';
					else if(value == 30)
						return '1개월 이상';
					else if(value == 90)
						return '3개월 이상';
					else
						return '6개월 이상';
				}
			},
			categoryAxis: {
				forceShowField: '기간',
				gridPosition: 'start',
				labelColorField: 'color',
				labelFunction: function(value) {
					if(value < 7)
						return value + '일 이하';
					else if(value == 7)
						return '1주일 이상';
					else if(value == 14)
						return '2주일 이상';
					else if(value == 30)
						return '1개월 이상';
					else if(value == 90)
						return '3개월 이상';
					else
						return '6개월 이상';
				}
			},
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {id:'valueAxis'}),
			],
			dataProvider: [
				<?foreach($dataDuration as $data) {?>
				{
					'기간': '<?=$data['d']?>',
					'예약': <?=$data['total']?>
				},
				<?}?>
			]
		}));

		var chart1 = AmCharts.makeChart('chart1', $.extend(true, {}, chartOption('가입자 예약 전환'), {
			colors: [
				defaultChartOption.colors[1],
				'#BBBBBB'
			],
			dataProvider: [
				<?
				$current = 0;
				foreach($dataConversion as $data) {?>
				{
					'타입': '<?=$data['type'] ?>',
					'합계': <?=$data['total'] ?>,
					<?if($current++ == 0) {?>
					pulled: true
					<?}?>
				},
				<?}?>
			]
		}) );

		var chart2 = AmCharts.makeChart('chart2', $.extend(true, {}, chartOption('취소율'), {
			colors: [
				defaultChartOption.colors[1],
				'#BBBBBB'
			],
			dataProvider: [
				<?
				$current = 0;
				foreach($dataCancel as $data) {?>
				{
					'타입': '<?=$data['type'] ?>',
					'합계': <?=$data['total'] ?>,
					<?if($current++ == 0) {?>
					pulled: true
					<?}?>
				},
				<?}?>
			]
		}) );

		var chart3 = AmCharts.makeChart('chart3', $.extend(true, {}, chartOption('재구매 회원'), {
			dataProvider: [
				<?
				$total = count($dataReservation);
				$current = 0;
				foreach($dataReservation as $data) {
					$current++;
					?>
				{
					'타입': '<?=$data['type'] . ($data['type'] < 3 ? '건' : '건 이상') ?>',
					'합계': <?=$data['total'] ?>,
					<?if($current < $total) {?>
					pulled: true
					<?} else {?>
					color: '#BBBBBB'
					<?}?>
				},
				<?}?>
			]
		}) );

		var chart4 = AmCharts.makeChart('chart4', $.extend(true, {}, chartOption('가입후 예약전환 시간'), {
			dataProvider: [
				<?
				$total = count($dataConversionTime);
				$current = 0;
				foreach($dataConversionTime as $data) {
				$current++;
				?>
				{
					'타입': '<?=$data['type'] ?>',
					'합계': <?=$data['total'] ?>,
					<?if($current == 1) {?>
					pulled: true,
					<? } ?>

					<?if($current == $total) {?>
					color: '#BBBBBB'
					<?}?>
				},
				<?}?>
			]
		}) );

		var chart5 = AmCharts.makeChart('chart5', $.extend(true, {}, defaultChartOption, {
			type: 'serial',
			categoryField: '기간',
			titles: [
				$.extend(true, {}, defaultChartOption.titles, {id: 'title', text: '픽업전 예약일'})
			],
			graphs: [
				{
					id: 'total',
					type: 'column',
					valueAxis: 'valueAxis',
					lineThickness: 0,
					title: '전체',
					valueField: '전체',
					balloonText: '',
					fillAlphas: 0.9
				},
				{
					id: 'reservation',
					type: 'column',
					valueAxis: 'valueAxis',
					lineThickness: 0,
					title: '예약/대여/반납',
					valueField: '전체예약',
					balloonText: '',
					fillAlphas: 0.9
				},
				{
					id: 'cancel',
					type: 'column',
					valueAxis: 'valueAxis',
					lineThickness: 0,
					title: '취소',
					valueField: '취소',
					balloonText: '',
					fillAlphas: 0.9
				},
				{
					id: 'rate',
					type: 'column',
					valueAxis: 'percentAxis',
					lineThickness: 0,
					title: '취소율',
					valueField: '취소율',
					balloonText: '',
					balloonFunction: function(item, graph) {
						return '<div style="text-align:left;font-size:12px;">'
							+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[0] + ';">전체: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['전체']).numberFormat(0) + '건</span></p>'
							+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[1] + ';">예약: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['예약']).numberFormat(0) + '건</span></p>'
							+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[1] + ';">대여: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['대여']).numberFormat(0) + '건</span></p>'
							+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[1] + ';">반납: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['반납']).numberFormat(0) + '건</span></p>'
							+ '<p style="margin:0;font-weight:bold;color:' + defaultChartOption.colors[2] + ';">취소: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['취소']).numberFormat(0) + '건</span></p>'
							+ '<p style="margin:4px 0 0;padding:4px 0 0;font-weight:bold;;color:' + defaultChartOption.colors[3] + ';border-top:solid 1px #999;">취소율: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['취소율']).numberFormat(2) + '%</span></p>'
							+ '</div>'
					},
					fillAlphas: 0.9
				}
			],
			chartCursor: {
				categoryBalloonFunction: function(valueText) {
					if(valueText < 24)
						return '~ ' + valueText + '시간';
					else if(valueText <= 720)
						return '~ ' + (valueText / 24) + '일';
					else
						return (720 / 24) + '일 ~';
				}
			},
			categoryAxis: {
				forceShowField: '기간',
				gridPosition: 'start',
				labelColorField: 'color',
				labelFunction: function(valueText) {
					if(valueText < 24)
						return '~ ' + valueText + '시간';
					else if(valueText <= 720)
						return '~ ' + (valueText / 24) + '일';
					else
						return (720 / 24) + '일 ~';
				}
			},
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {id:'valueAxis'}),
				$.extend(true, {}, defaultChartOption.valueAxes, {id:'percentAxis', position: 'right', gridAlpha:0, minimum:0})
			],
			dataProvider: [
				<?foreach($dataReservationPickup as $data) {?>
				{
					'기간': '<?=$data['기간']?>',
					'전체': <?=$data['전체']?>,
					'전체예약': <?=$data['전체예약']?>,
					'예약': <?=$data['예약']?>,
					'대여': <?=$data['대여']?>,
					'반납': <?=$data['반납']?>,
					'취소': <?=$data['취소']?>,
					'취소율': <?=$data['취소율']*100?>
				},
				<?}?>
			]
		}));
	});
</script>
