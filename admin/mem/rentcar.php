<?
/**
 * admin.rentking.co.kr/mem.php?code=rentcar
 * 어드민 > 고객관리 > 멤버사
 * 어드민 멤버사 리스트 페이지
 */

$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 25;

$page_per_block = 10;
$page = $_GET['page'] ? $_GET['page'] : 1;

/* 정렬 기본 */
if (!$sortcol)
	$sortcol = "dt_create";

if (!$sortby)
	$sortby = "desc";

/* //정렬 기본 */


//HTTP QUERY STRING
$keyword = trim($keyword);
$qArr['numper'] = $numper;
$qArr['page'] = $page;
$qArr['code'] = $code;
$qArr['sortcol'] = $sortcol;
$qArr['sortby'] = $sortby;
$qArr['keyword'] = $keyword;
$qArr['loca'] = $loca;

$q = "
	SELECT
		'[FIELD]'
	FROM rentshop r
	WHERE 1=1 "
;
if ($keyword) {
	$q = $q . " AND (r.name like '%".mysql_escape_string($keyword)."%' OR r.affiliate like '%".mysql_escape_string($keyword)."%')";
}
if ($loca) {
	$q = "{$q} AND r.addr1 like '{$loca}%'";
}

//카운터쿼리
$sql = str_replace("'[FIELD]'", "COUNT(r.idx)", $q);
$r = mysql_query($sql);
$total_record = mysql_result($r, 0, 0);
mysql_free_result($r);

if ($total_record == 0) {
	$first = 0;
	$last = 0;
} else {
	$first = $numper * ($page - 1);
	$last = $numper * $page;
}

//데이터쿼리
$_sql = str_replace("'[FIELD]'", "
	r.name,
	r.idx,
	r.affiliate,
	r.dname1,
	r.dphone1,
	r.totalcar,
	r.isok,
	r.idx rentshop_idx,
	(SELECT COUNT(idx) FROM vehicles WHERE rentshop_idx = r.idx AND dt_delete IS NULL) isit,
	r.bank,
	r.bankaccount,
	r.bankname,
	r.dname2,
	r.dcp2,
	r.dname3,
	SUBSTRING_INDEX(r.addr1, ' ', 2) addr,
	r.dcp3", $q);

$_tArr = explode(",", $sortcol);
if (is_array($_tArr) && count($_tArr)) {
	foreach ($_tArr as $v) {
		$orderbyArr[] = "{$v} {$sortby}";
	}
	$orderby = implode(", ", $orderbyArr);
}

$sql_order = " ORDER BY {$orderby}";
$sql_limit = " LIMIT {$first},{$numper}";
$sql = $_sql . $sql_order . $sql_limit;
$r = mysql_query($sql);
while ($row = mysql_fetch_array($r)) {
	$data[] = $row;
}

//엑셀쿼리
$sql_excel = $_sql . $sql_order;
$_SESSION['sql_excel'] = $sql_excel;
?>
<!--//16.10.27 제휴렌트카 -> 멤버사텍스트변경-->
<form name="searchform" id="searchform" action="../mem.php?code=<?= $code; ?>" method="get">
	<input type="hidden" name="code" value="<?=$code ?>" />
	<div style="margin-top:10px;">
		<input type="text" maxlength="50" name="keyword" class="" value="<?=$keyword?>" placeholder="회사명(지점)">
		<span class="blackBtn" onclick="$('#searchform').submit();">검색</span>

		<span class="blackBtn" onclick="location.href='../mem.php?code=<?= $code; ?>';">전체보기</span>
		<a href="../mem.php?code=<?= $code; ?>r" style="float:right;"><span class="redBtn">멤버사등록</span></a>
<!--		<span class="greenBtn" onclick="sms_reg();" style="float:right;margin-right:4px;">SMS발송</span>-->
		<span class="greenBtn" onclick="location.href='../excel/excel_down.php?act=rentcar';" style="float:right;margin-right:4px;">EXCEL</span>
	</div>
</form>

<div id="chart1" style="height:200px;margin-top:16px;border:solid 1px #cdd0d5;box-sizing:border-box;background:#fff;">
</div>

<div class="bottomTable">
	<table class="listTable2">
		<thead>
		<tr>
			<th>No.</th>
			<th>회사명(지점)</th>
			<th>지역(시/구)</th>
			<th>대표자</th>
			<th>제휴담당자</th>
			<th>제휴담당자<br />(Mobile)</th>
			<th>보유</th>
			<th>등록</th>
			<th>인증상태</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?
		for ($is = 0; $is < count($data); $is++) {
			$row = $data[$is];
			?>
			<!--161130 링크 추가-->
			<tr class="hv">
				<td><?= $row['idx']; ?></td>
				<td style="text-align:left;padding:0 5px;"><a href="/mem.php?code=<?= $code; ?>m&idx=<?= $row['idx']; ?>"><?= $row['name'] ?>(<?= $row['affiliate']; ?>)</a></td>
				<td style="text-align:left;padding:0 5px;"><?= $row['addr']; ?></td>
				<td style="text-align:left;padding:0 5px;"><?= $row['dname1']; ?></td>
				<td style="text-align:left;padding:0 5px;"><?= $row['dname2']; ?></td>
				<td style="text-align:left;padding:0 5px;"><?=phone_number_format($row['dcp2'])?></td>
				<td><?= $row['totalcar']; ?></td>
				<td><?= $row['isit']; ?></td>
				<td><?= $row['isok'] == 'Y' ? 'ON' : 'OFF' ?></td>
				<td><a href="#<?=$row['rentshop_idx']?>" onclick="MM_openBrWindow('/settlement/rentshop.php?rentshop=<?=$row['rentshop_idx']?>','rentshop_<?=$row['rentshop_idx'] ?>','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;" class="blackBtn_small">정산보기</a></td>
			</tr>
		<? } ?>
		<!--//161130 링크 추가-->
		</tbody>
	</table>
	<!-- paging     16.11.07 추가  -->
	<div class="paging">
		<?= paging_admin($page, $total_record, $numper, $page_per_block, $qArr); ?>
	</div>
	<!-- //paging -->
</div>

<?
$query = "SELECT
		t.city,
		SUM(1) total,
		SUM(CASE WHEN t.isok <> 'Y' THEN 1 ELSE 0 END) off,
		SUM(CASE WHEN t.isit < 1 THEN 1 ELSE 0 END) no
	FROM (
		SELECT
			SUBSTRING_INDEX(CASE
				WHEN r.addr1 LIKE '서울%' THEN '서울'
				WHEN r.addr1 LIKE '부산%' THEN '부산'
				WHEN r.addr1 LIKE '대구%' THEN '대구'
				WHEN r.addr1 LIKE '인천%' THEN '인천'
				WHEN r.addr1 LIKE '광주%' THEN '광주'
				WHEN r.addr1 LIKE '대전%' THEN '대전'
				WHEN r.addr1 LIKE '울산%' THEN '울산'
				WHEN r.addr1 LIKE '경기%' THEN '경기'
				WHEN r.addr1 LIKE '강원%' THEN '강원'
				WHEN r.addr1 LIKE '충청북도%' THEN '충북'
				WHEN r.addr1 LIKE '충청남도%' THEN '충남'
				WHEN r.addr1 LIKE '전라북도%' THEN '전북'
				WHEN r.addr1 LIKE '전라남도%' THEN '전남'
				WHEN r.addr1 LIKE '경상북도%' THEN '경북'
				WHEN r.addr1 LIKE '경상남도%' THEN '경남'
				WHEN r.addr1 LIKE '제주%' THEN '제주'
				WHEN r.addr1 LIKE '세종%' THEN '세종'
				ELSE r.addr1
			END, ' ', 1) city,
			(SELECT COUNT(idx) FROM vehicles WHERE rentshop_idx = r.idx AND dt_delete IS NULL) isit,
			r.isok
		FROM
			rentshop r
	) t
	GROUP BY city
	ORDER BY total DESC";
$memberByCity = [];
$r = mysql_query($query);
$total = array('total' => 0, 'off' => 0, 'no' => 0);
while($row = mysql_fetch_assoc($r)) {
	$memberByCity[] = $row;
	$total['total'] += $row['total'];
	$total['off'] += $row['off'];
	$total['no'] += $row['no'];
}
?>

<script type="text/javascript">
	$(function() {
		var chartOption = function(idx, title, dateFormat, parseDate, unit, minPeriod) {
			return $.extend(true, {}, defaultChartOption, {
				type: 'serial',
				titles: [
					$.extend(true, {}, defaultChartOption.titles, {id: 'title'+idx, text: title})
				],
				graphs: [
					{
						id: 'total'+idx,
						type: 'column',
						lineThickness: 0,
						title: '전체',
						valueField: '전체',
						fillAlphas: 0.9,
						lineColor: defaultChartOption.colors[0],
						balloonText: '',
						balloonFunction: function(item, graph) {
							var html = '<div style="text-align:left;font-weight:bold;">'
								+ '<p style="margin:0;color:' + defaultChartOption.colors[0] + ';">전체: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['전체']).numberFormat(0) + '</span></p>'
								+ '<p style="margin:0;color:' + defaultChartOption.balloonColor + ';">OFF: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['OFF']).numberFormat(0) + '</span></p>'
								+ '<p style="margin:0;color:' + defaultChartOption.colors[3] + ';">미등록: <span style="float:right;margin-left:10px;">' + Number(item.dataContext['미등록']).numberFormat(0) + '</span></p>'
							html += '</div>';
							return html;
						},
						balloonColor: defaultChartOption.balloonColor
					},
					{
						id: 'off'+idx,
						type: 'column',
						lineThickness: 0,
						title: 'OFF',
						valueField: 'OFF',
						fillAlphas: 0.9,
						lineColor: defaultChartOption.balloonColor,
						balloonText: '',
						balloonColor: defaultChartOption.balloonColor
					},
					{
						id: 'no'+idx,
						type: 'column',
						lineThickness: 0,
						title: '미등록',
						valueField: '미등록',
						fillAlphas: 0.9,
						lineColor: defaultChartOption.colors[2],
						balloonText: '',
						balloonColor: defaultChartOption.balloonColor
					}
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
					},
//					maximumDate: moment().format('YYYY-MM-DD')
				},
				valueAxes: [
					$.extend(true, {}, defaultChartOption.valueAxes, {})
				],
				chartCursor: {
					categoryBalloonDateFormat: dateFormat
				},
				chartScrollbar: {
					enabled: false
				}
			})
		};

		var chart1 = AmCharts.makeChart('chart1', $.extend(true, {}, chartOption(2, '지역별 멤버사 (전체: <?=$total['total']?>, OFF: <?=$total['off']?>, 차량미등록: <?=$total['no']?>)', 'YYYY-MM', false, '', 'MM'), {
			dataDateFormat: 'YYYY-MM',
			categoryField: '도시',
			dataProvider: [
				<?foreach($memberByCity as $data) {?>
				{
					'도시': '<?=$data['city']?>',
					'전체': <?=$data['total']?>,
					'OFF': <?=$data['off']?>,
					'미등록': <?=$data['no'] ?>
				},
				<?}?>
			]
		}));
		chart1.addListener('changed', function(e) {
			e.chart.cursorDataContext = e.chart.dataProvider[e.index];
		});
		chart1.addListener('rendered', function(e) {
			e.chart.chartDiv.addEventListener('click', function() {
				if(typeof e.chart.cursorDataContext !== 'undefined' && typeof e.chart.cursorDataContext['도시'] !== 'undefined') {
					location.href='../mem.php?code=<?= $code; ?>&loca='+e.chart.cursorDataContext['도시'];
				}

			});
		})
	});
</script>
