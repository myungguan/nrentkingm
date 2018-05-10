<?php

if(!$sdate)
{	$sdate = date("Y-m-d", mktime(0, 0, 0, date("m")-11, 1));	}

if(!$edate)
{	$edate = date("Y-m-d");	}

$query = "SELECT LEFT(MIN(dt), 10) day FROM log_pageview WHERE dt >= '$sdate 00:00:00'";
$minDate = mysql_fetch_row(mysql_query($query))[0];

$query = "SELECT
		t.day,
		COUNT(1) visit
	FROM (
		SELECT DISTINCT session_id, DATE(dt) day
		FROM log_pageview
		WHERE dt >= '$minDate 00:00:00' AND dt <= '$edate 23:59:59'
		 ) t
	GROUP BY day
	ORDER BY day";
$dataVisitDay = [];
$dataVisitDayArr = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataVisitDay[] = $row;
	$dataVisitDayArr[$row['day']] = $row['visit'];
}

$dataVisitMonth = [];
$dataVisitMonthArr = [];
$total = count($dataVisitDay);
for($i = 0; $i < $total; $i++) {
	$month = substr($dataVisitDay[$i]['day'], 0, 7);
	if(isset($dataVisitMonthArr[$month])) {
		$dataVisitMonthArr[$month] += $dataVisitDay[$i]['visit'];
	} else {
		$dataVisitMonthArr[$month] = $dataVisitDay[$i]['visit'];
	}
}

foreach($dataVisitMonthArr as $month => $visit) {
	$dataVisitMonth[] = array('month' => $month, 'visit' => $visit);
}

$query = "SELECT
		t.day,
		COUNT(1) search
	FROM (
		SELECT DISTINCT session_id, DATE(dt) day
		FROM log_search
		WHERE dt >= '$minDate 00:00:00' AND dt <= '$edate 23:59:59'
	) t
	GROUP BY day
	ORDER BY day";
$dataSearchDay = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataSearchDay[] = $row;
}

$dataSearchMonth = [];
$dataSearchMonthArr = [];
$total = count($dataSearchDay);
for($i = 0; $i < $total; $i++) {
	$month = substr($dataSearchDay[$i]['day'], 0, 7);
	if(isset($dataVisitMonthArr[$month])) {
		$dataSearchMonthArr[$month] += $dataSearchDay[$i]['search'];
	} else {
		$dataSearchMonthArr[$month] = $dataSearchDay[$i]['search'];
	}
}

foreach($dataSearchMonthArr as $month => $visit) {
	$dataSearchMonth[] = array('month' => $month, 'search' => $visit);
}

$query = "
	SELECT
		t.day,
		COUNT(1) orderpage
	FROM (
		SELECT DISTINCT session_id, DATE(dt) day
		FROM log_pageview
		WHERE
			dt >= '$minDate 00:00:00' AND dt <= '$edate 23:59:59'
			AND url LIKE '/rent/payment%'
		 ) t
	GROUP BY day
	ORDER BY day";
$dataOrderDay = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataOrderDay[] = $row;
}

$dataOrderMonth = [];
$dataOrderMonthArr = [];
$total = count($dataOrderDay);
for($i = 0; $i < $total; $i++) {
	$month = substr($dataSearchDay[$i]['day'], 0, 7);
	if(isset($dataOrderMonthArr[$month])) {
		$dataOrderMonthArr[$month] += $dataOrderDay[$i]['orderpage'];
	} else {
		$dataOrderMonthArr[$month] = $dataOrderDay[$i]['orderpage'];
	}
}

foreach($dataOrderMonthArr as $month => $visit) {
	$dataOrderMonth[] = array('month' => $month, 'orderpage' => $visit);
}

$query = "SELECT
		LEFT( m.dt_create, 10) day,
		SUM(1) reservation
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		m.dan < 5
		AND m.dt_create >= '$minDate 00:00:00' AND m.dt_create <= '$edate 23:59:59'
	GROUP BY day
	ORDER BY day";
$dataReservationDay = [];
$r = mysql_query($query);
while($row = mysql_fetch_assoc($r)) {
	$dataReservationDay[] = $row;
}

$dataReservationMonth = [];
$dataReservationMonthArr = [];
$total = count($dataOrderDay);
for($i = 0; $i < $total; $i++) {
	$month = substr($dataSearchDay[$i]['day'], 0, 7);
	if(isset($dataReservationMonthArr[$month])) {
		$dataReservationMonthArr[$month] += $dataReservationDay[$i]['reservation'];
	} else {
		$dataReservationMonthArr[$month] = $dataReservationDay[$i]['reservation'];
	}
}

foreach($dataReservationMonthArr as $month => $visit) {
	$dataReservationMonth[] = array('month' => $month, 'reservation' => $visit);
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

<div style="height:250px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart5" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart6" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>

<div style="height:250px;margin-top:16px">
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 8px 0 0;">
		<div id="chart7" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
	<div style="height:100%;width:50%;float:left;box-sizing:border-box;padding:0 0 0 8px;">
		<div id="chart8" style="height:100%;width:100%;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;"></div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var chartIdx = 0;
		var chartOption = function(title, dateFormat, parseDate, unit, minPeriod) {
			chartIdx++;
			return $.extend(true, {}, defaultChartOption, {
				type: 'serial',
				titles: [
					$.extend(true, {}, defaultChartOption.titles, {id: 'title'+chartIdx, text: title})
				],
				colors:[
					defaultChartOption.colors[1],
					defaultChartOption.colors[3]
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
				valueAxes: [
					$.extend(true, {}, defaultChartOption.valueAxes)
				],
				chartCursor: {
					categoryBalloonDateFormat: dateFormat
				},
				chartScrollbar: {
					enabled: true,
					graph: 'data'+chartIdx
				}
			})
		};

		var chart1 = AmCharts.makeChart('chart1', $.extend(true, {}, chartOption('월별 방문 세션', 'YYYY-MM', true, '', 'MM'), {
			dataDateFormat: 'YYYY-MM',
			categoryField: '월',
			dataProvider: [
				<?
				$average = 0;
				foreach($dataVisitMonth as $data) {
				$average += $data['visit'];
				?>
				{
					'월': '<?=$data['month']?>',
					'방문': <?=$data['visit']?>
				},
				<?}?>
			],
			graphs: [
				{
					id: 'data1',
					type: 'column',
					lineThickness: 0,
					title: '방문',
					valueField: '방문',
					balloonText: '[[value]]',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9
				}
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {minimum:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataVisitMonth)?>,
						label: Number(<?=$average / count($dataVisitMonth)?>).numberFormat(0)
					})]
				})
			]
		}));
		chart1.addListener('rendered', function(e) {
			e.chart.zoomToIndexes(e.chart.dataProvider.length - 30, e.chart.dataProvider.length - 1);
		});

		var chart2 = AmCharts.makeChart('chart2', $.extend(true, {}, chartOption('일별 방문 세션', 'YYYY-MM-DD', true, '', 'DD'), {
			dataDateFormat: 'YYYY-MM-DD',
			categoryField: '일',
			categoryAxis: {
				autoGuides: {days:[0]}
			},
			dataProvider: [
				<?
				$average = 0;
				foreach($dataVisitDay as $data) {
				$average += $data['visit'];
				?>
				{
					'일': '<?=$data['day']?>',
					'방문': <?=$data['visit']?>
				},
				<?}?>
			],
			graphs: [
				{
					id: 'data2',
					type: 'column',
					lineThickness: 0,
					title: '방문',
					valueField: '방문',
					balloonText: '[[value]]',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9
				}
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {minimum:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataVisitDay)?>,
						label: Number(<?=$average / count($dataVisitDay)?>).numberFormat(0)
					})]
				})
			]
		}));
		chart2.addListener('rendered', function(e) {
			e.chart.zoomToDates(moment('<?=$edate?>').subtract(1, 'M').toDate(), moment('<?=$edate?>').add(1, 'd').toDate());
		});

		var chart3 = AmCharts.makeChart('chart3', $.extend(true, {}, chartOption('월별 검색 세션', 'YYYY-MM', true, '', 'MM'), {
			dataDateFormat: 'YYYY-MM',
			categoryField: '월',
			dataProvider: [
				<?
				$average = 0;
				$averageP = 0;
				foreach($dataSearchMonth as $data) {
				$average += $data['search'];
				$averageP += $data['search'] / $dataVisitMonthArr[$data['month']] * 100;
				?>
				{
					'월': '<?=$data['month']?>',
					'검색': <?=$data['search']?>,
					'전환율': (<?=$data['search'] / $dataVisitMonthArr[$data['month']] * 100 ?>).numberFormat(2)
				},
				<?}?>
			],
			graphs: [
				{
					id: 'data3',
					type: 'column',
					lineThickness: 0,
					title: '검색',
					valueField: '검색',
					balloonText: '검색: [[value]]',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9
				},
				{
					id: 'data3Rate',
					type: 'column',
					lineThickness: 0,
					title: '전환율',
					valueField: '전환율',
					balloonText: '방문대비 전환율: [[value]]%',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9,
					valueAxis: 'rateAxis'
				}
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {minimum:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataSearchMonth)?>,
						label: Number(<?=$average / count($dataSearchMonth)?>).numberFormat(0)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {id:'rateAxis', position:'right', minimum:0, maximum:100, gridAlpha:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$averageP / count($dataSearchMonth)?>,
						label: Number(<?=$averageP / count($dataSearchMonth)?>).numberFormat(1)
					})]
				})
			]
		}));
		chart3.addListener('rendered', function(e) {
			e.chart.zoomToIndexes(e.chart.dataProvider.length - 30, e.chart.dataProvider.length - 1);
		});

		var chart4 = AmCharts.makeChart('chart4', $.extend(true, {}, chartOption('일별 검색 세션', 'YYYY-MM-DD', true, '', 'DD'), {
			dataDateFormat: 'YYYY-MM-DD',
			categoryField: '일',
			categoryAxis: {
				autoGuides: {days:[0]}
			},
			dataProvider: [
				<?
				$average = 0;
				$averageP = 0;
				foreach($dataSearchDay as $data) {
				$average += $data['search'];
				$averageP += $data['search'] / $dataVisitDayArr[$data['day']] * 100;
				?>
				{
					'일': '<?=$data['day']?>',
					'검색': <?=$data['search']?>,
					'전환율': (<?=$data['search'] / $dataVisitDayArr[$data['day']] * 100 ?>).numberFormat(2)
				},
				<?}?>
			],
			graphs: [
				{
					id: 'data4',
					type: 'column',
					lineThickness: 0,
					title: '검색',
					valueField: '검색',
					balloonText: '[[value]]',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9
				},
				{
					id: 'data4Rate',
					type: 'column',
					lineThickness: 0,
					title: '전환율',
					valueField: '전환율',
					balloonText: '방문대비 전환율: [[value]]%',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9,
					valueAxis: 'rateAxis'
				}
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {minimum:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataSearchDay)?>,
						label: Number(<?=$average / count($dataSearchDay)?>).numberFormat(0)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {id:'rateAxis', position:'right', minimum:0, maximum:100, gridAlpha:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$averageP / count($dataSearchDay)?>,
						label: Number(<?=$averageP / count($dataSearchDay)?>).numberFormat(1)
					})]
				})
			]
		}));
		chart4.addListener('rendered', function(e) {
			e.chart.zoomToDates(moment('<?=$edate?>').subtract(1, 'M').toDate(), moment('<?=$edate?>').add(1, 'd').toDate());
		});

		var chart5 = AmCharts.makeChart('chart5', $.extend(true, {}, chartOption('월별 예약페이지 방문 세션', 'YYYY-MM', true, '', 'MM'), {
			dataDateFormat: 'YYYY-MM',
			categoryField: '월',
			dataProvider: [
				<?
				$average = 0;
				$averageP = 0;
				foreach($dataOrderMonth as $data) {
				$average += $data['orderpage'];
				$averageP += $data['orderpage'] / $dataVisitMonthArr[$data['month']] * 100;
				?>
				{
					'월': '<?=$data['month']?>',
					'방문': <?=$data['orderpage']?>,
					'전환율': (<?=$data['orderpage'] / $dataVisitMonthArr[$data['month']] * 100 ?>).numberFormat(2)
				},
				<?}?>
			],
			graphs: [
				{
					id: 'data5',
					type: 'column',
					lineThickness: 0,
					title: '방문',
					valueField: '방문',
					balloonText: '[[value]]',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9
				},
				{
					id: 'data5Rate',
					type: 'column',
					lineThickness: 0,
					title: '전환율',
					valueField: '전환율',
					balloonText: '방문대비 전환율: [[value]]%',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9,
					valueAxis: 'rateAxis'
				}
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {minimum:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataOrderMonth)?>,
						label: Number(<?=$average / count($dataOrderMonth)?>).numberFormat(0)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {id:'rateAxis', position:'right', minimum:0, maximum:6, gridAlpha:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$averageP / count($dataOrderMonth)?>,
						label: Number(<?=$averageP / count($dataOrderMonth)?>).numberFormat(1)
					})]
				})
			]
		}));
		chart5.addListener('rendered', function(e) {
			e.chart.zoomToIndexes(e.chart.dataProvider.length - 30, e.chart.dataProvider.length - 1);
		});

		var chart6 = AmCharts.makeChart('chart6', $.extend(true, {}, chartOption('일별 예약페이지 방문 세션', 'YYYY-MM-DD', true, '', 'DD'), {
			dataDateFormat: 'YYYY-MM-DD',
			categoryField: '일',
			categoryAxis: {
				autoGuides: {days:[0]}
			},
			dataProvider: [
				<?
				$average = 0;
				$averageP = 0;
				foreach($dataOrderDay as $data) {
				$average += $data['orderpage'];
				$averageP += $data['orderpage'] / $dataVisitDayArr[$data['day']] * 100;
				?>
				{
					'일': '<?=$data['day']?>',
					'방문': <?=$data['orderpage']?>,
					'전환율': (<?=$data['orderpage'] / $dataVisitDayArr[$data['day']] * 100 ?>).numberFormat(2)
				},
				<?}?>
			],
			graphs: [
				{
					id: 'data6',
					type: 'column',
					lineThickness: 0,
					title: '방문',
					valueField: '방문',
					balloonText: '[[value]]',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9
				},
				{
					id: 'data6Rate',
					type: 'column',
					lineThickness: 0,
					title: '전환율',
					valueField: '전환율',
					balloonText: '방문대비 전환율: [[value]]%',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9,
					valueAxis: 'rateAxis'
				}
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {minimum:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataOrderDay)?>,
						label: Number(<?=$average / count($dataOrderDay)?>).numberFormat(0)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {id:'rateAxis', position:'right', minimum:0, maximum:6, gridAlpha:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$averageP / count($dataOrderDay)?>,
						label: Number(<?=$averageP / count($dataOrderDay)?>).numberFormat(1)
					})]
				})
			]
		}));
		chart6.addListener('rendered', function(e) {
			e.chart.zoomToDates(moment('<?=$edate?>').subtract(1, 'M').toDate(), moment('<?=$edate?>').add(1, 'd').toDate());
		});

		var chart7 = AmCharts.makeChart('chart7', $.extend(true, {}, chartOption('월별 예약', 'YYYY-MM', true, '', 'MM'), {
			dataDateFormat: 'YYYY-MM',
			categoryField: '월',
			dataProvider: [
				<?
				$average = 0;
				$averageP = 0;
				foreach($dataReservationMonth as $data) {
				$average += $data['reservation'];
				$averageP += $data['reservation'] / $dataVisitMonthArr[$data['month']] * 100;
				?>
				{
					'월': '<?=$data['month']?>',
					'예약': <?=$data['reservation']?>,
					'전환율': (<?=$data['reservation'] / $dataVisitMonthArr[$data['month']] * 100 ?>).numberFormat(2)
				},
				<?}?>
			],
			graphs: [
				{
					id: 'data7',
					type: 'column',
					lineThickness: 0,
					title: '예약',
					valueField: '예약',
					balloonText: '[[value]]건',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9
				},
				{
					id: 'data7Rate',
					type: 'column',
					lineThickness: 0,
					title: '전환율',
					valueField: '전환율',
					balloonText: '방문대비 전환율: [[value]]%',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9,
					valueAxis: 'rateAxis'
				}
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {minimum:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataReservationMonth)?>,
						label: Number(<?=$average / count($dataReservationMonth)?>).numberFormat(0)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {id:'rateAxis', position:'right', minimum:0, maximum:2, gridAlpha:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$averageP / count($dataReservationMonth)?>,
						label: Number(<?=$averageP / count($dataReservationMonth)?>).numberFormat(1)
					})]
				})
			]
		}));
		chart7.addListener('rendered', function(e) {
			e.chart.zoomToIndexes(e.chart.dataProvider.length - 30, e.chart.dataProvider.length - 1);
		});

		var chart8 = AmCharts.makeChart('chart8', $.extend(true, {}, chartOption('일별 예약', 'YYYY-MM-DD', true, '건', 'DD'), {
			dataDateFormat: 'YYYY-MM-DD',
			categoryField: '일',
			categoryAxis: {
				autoGuides: {days:[0]}
			},
			dataProvider: [
				<?
				$average = 0;
				$averageP = 0;
				foreach($dataReservationDay as $data) {
				$average += $data['reservation'];
				$averageP += $data['reservation'] / $dataVisitDayArr[$data['day']] * 100;
				?>
				{
					'일': '<?=$data['day']?>',
					'예약': <?=$data['reservation']?>,
					'전환율': (<?=$data['reservation'] / $dataVisitDayArr[$data['day']] * 100 ?>).numberFormat(2)
				},
				<?}?>
			],
			graphs: [
				{
					id: 'data8',
					type: 'column',
					lineThickness: 0,
					title: '예약',
					valueField: '예약',
					balloonText: '[[value]]건',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9
				},
				{
					id: 'data8Rate',
					type: 'column',
					lineThickness: 0,
					title: '전환율',
					valueField: '전환율',
					balloonText: '방문대비 전환율: [[value]]%',
					balloonColor: defaultChartOption.balloonColor,
					fillAlphas: 0.9,
					valueAxis: 'rateAxis'
				}
			],
			valueAxes: [
				$.extend(true, {}, defaultChartOption.valueAxes, {minimum:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$average / count($dataReservationDay)?>,
						label: Number(<?=$average / count($dataReservationDay)?>).numberFormat(0)
					})]
				}),
				$.extend(true, {}, defaultChartOption.valueAxes, {id:'rateAxis', position:'right', minimum:0, maximum:2, gridAlpha:0,
					guides: [$.extend(true, {}, defaultChartOption.defaultGuide, {
						value: <?=$averageP / count($dataReservationDay)?>,
						label: Number(<?=$averageP / count($dataReservationDay)?>).numberFormat(1)
					})]
				})
			]
		}));
		chart8.addListener('rendered', function(e) {
			e.chart.zoomToDates(moment('<?=$edate?>').subtract(1, 'M').toDate(), moment('<?=$edate?>').add(1, 'd').toDate());
		});
	});
</script>
