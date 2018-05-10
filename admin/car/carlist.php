<?
/**
 * 어드민 > 차량관리 > 차종별 등록현황
 * admin.rentking.co.kr/car.php?code=carlist
 * 차종별 등록현황 페이지
 */
$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 10;
$page_per_block = 10;
$page = $_GET['page'] ? $_GET['page'] : 1;

/* 정렬 기본 */
if (!$sortcol) {
	$sortcol = "idx";
}

if (!$sortby) {
	$sortby = "desc";
}

/* //정렬 기본 */


//HTTP QUERY STRING
$qArr['numper'] = $numper;
$qArr['page'] = $page;
$qArr['code'] = $code;
$qArr['sortcol'] = $sortcol;
$qArr['sortby'] = $sortby;
$qArr['idx'] = $idx;

$q = "SELECT '[FIELD]' FROM vehicle_models WHERE idx IN (SELECT model_idx FROM vehicles WHERE 1)";

//카운터쿼리
$sql = str_replace("'[FIELD]'", "COUNT(vehicle_models.idx)", $q);
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
$_sql = str_replace("'[FIELD]'", "vehicle_models.*", $q);

$_tArr = explode(",", $sortcol);
if (is_array($_tArr) && count($_tArr)) {
	foreach ($_tArr as $v) {
		$orderbyArr[] = "{$v} {$sortby}";
	}
	$orderby = implode(", ", $orderbyArr);
}

$sql_order = " ORDER BY {$orderby}";
$sql_limit = " LIMIT {$first}, {$numper}";
$sql = $_sql . $sql_order . $sql_limit;

$r = mysql_query($sql);
while ($row = mysql_fetch_array($r)) {
	$data[] = $row;
}

//엑셀쿼리
$sql_excel = $_sql . $sql_order;
$_SESSION['sql_excel'] = $sql_excel;
?>
<div class="topBtn maB">
	<span class="greenBtn" onclick="location.href='../excel/excel_down.php?act=carlist';">EXCEL</span>
</div>
<div id='printdiv'>
	<table class="listTableColor4">
		<colgroup>
			<col width="50px;">
		</colgroup>
		<thead>
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">차량모델</th>
			<th rowspan="2">등록대수</th>
			<th colspan="4">예약현황</th>
		</tr>
		<tr>
			<th style="width:120px;">예약중</th>
			<th style="width:120px;">대여중</th>
			<th style="width:120px;">대기중</th>
			<th style="width:120px;">계</th>
		</tr>
		</thead>
		<tbody>
		<?
		$article_num = $total_record - (($page - 1) * $numper);
		for ($is = 0; $is < count($data); $is ++) {
			$row = $data[$is];

			$qs = "SELECT * FROM vehicles WHERE model_idx='{$row['idx']}' AND dt_delete IS NULL";
			$rs = mysql_query($qs);
			$isits = mysql_num_rows($rs);

			$qs = "SELECT m.idx FROM payments m
					LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
					LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
				WHERE m.dan='1' AND v.model_idx='{$row['idx']}'";
			$rs = mysql_query($qs);
			$t1 = mysql_num_rows($rs);

			$qs = "SELECT m.idx FROM payments m
					LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
					LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
				WHERE m.dan='2' AND v.model_idx='{$row['idx']}'";
			$rs = mysql_query($qs);
			$t2 = mysql_num_rows($rs);
			?>
			<tr onClick="location.href='/car.php?code=rentshoplist_s1&searchKeyword=<?=$row['name']?>';">
				<td><?=$article_num;?></td>
				<th style="text-align:left;padding:0 5px;"><?=$row['name']?></th>
				<td><?=number_format($isits);?></td>
				<td><?=number_format($t1);?></td>
				<td><?=number_format($t2);?></td>
				<td><?=number_format($isits - $t1 - $t2);?></td>
				<td><?=number_format($isits);?></td>
			</tr>
			<?
			$article_num --;
		}
		?>
		</tbody>
	</table>
	<!-- paging     16.11.25추가  -->
	<div class="paging">
		<?=paging_admin($page, $total_record, $numper, $page_per_block, $qArr);?>
	</div>
</div>
<!-- // paging     16.11.25추가 -->
