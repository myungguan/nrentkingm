<?
/**
 * 멤버사 > 차량관리 > 특별요금관리
 * admin.rentking.co.kr/scar.php?code=price_extra
 * 특별 요금 관리 페이지
 */
$mode = $_REQUEST['mode'];
if ($mode == 'd') {
	mysql_query("UPDATE vehicle_price_extra SET dt_delete = NOW() WHERE idx = {$_REQUEST['idx']}");

	echo "<script>alert('삭제완료'); </script>";
//	exit;
}

$query = "SELECT idx FROM rentshop WHERE idx={$_SESSION['rentshop_idx']}";
$rentshop = mysql_fetch_assoc(mysql_query($query));

$q = "SELECT COUNT(*) FROM vehicle_price_extra WHERE rentshop_idx = {$rentshop['idx']} AND dt_delete IS NULL";
$rs = mysql_query($q);
$total_record = mysql_result($rs, 0, 0);

$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 25;

$page_per_block = 10;
$page = $_GET['page'] ? $_GET['page'] : 1;

/* 정렬 기본 */
if (!$sortcol)
	$sortcol = "idx";

if (!$sortby)
	$sortby = "DESC";
/* 정렬 기본 */

//HTTP QUERY STRING
$qArr['page'] = $page;
$qArr['code'] = $code;
$qArr['sortcol'] = $sortcol;
$qArr['sortby'] = $sortby;

if ($total_record == 0) {
	$first = 0;
	$last = 0;
} else {
	$first = $numper * ($page - 1);
	$last = $numper * $page;
}

$sql = "SELECT 
			vpe.*, 
			(SELECT 
				COUNT(*) 
			FROM vehicles v 
			WHERE 
				v.dt_delete IS NULL 
				AND v.price_extra_idx = vpe.idx) car 
		FROM 
			vehicle_price_extra vpe 
		WHERE 
			vpe.rentshop_idx = {$rentshop['idx']} 
			AND vpe.dt_delete IS NULL";
$sql_order = " ORDER BY {$sortcol} {$sortby}";
$sql_limit = " LIMIT {$first}, {$last}";
$sql = $sql . $sql_order . $sql_limit;

$r = mysql_query($sql);
while ($row = mysql_fetch_array($r)) {
	$data[] = $row;
}
?>
<div class="topBtn">
	<a href="/scar.php?code=price_extrar"><span class="greenBtn">특별요금 등록</span></a>
</div>
<span class="subTitle">등록된 요금 : 총 <?= number_format($total_record) ?>건</span>
<div>
	<table class="listTableColor">
		<thead>
		<tr>
			<th>No.</th>
			<th>제목</th>
			<th>금액</th>
			<th>인상/할인</th>
			<th>사용차량</th>
			<th>기간</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<? if ($total_record < 1) { ?>
			<tr>
				<td colspan="7">데이터가 없습니다.</td>
			</tr>
		<? } else {
			$idx = $total_record - (($page - 1) * $numper);
			for ($is = 0; $is < count($data); $is++) {
				$row = $data[$is]; ?>
				<tr>
					<td><?= $idx--; ?></td>
					<td><a href="../scar.php?code=price_extram&idx=<?= $row['idx'] ?>"><?= $row['title'] ?></td>
					<td><?= number_format($row['price']) ?></td>
					<td><? if ($row['type'] == 1) { ?>인상<? }
						if ($row['type'] == 2) { ?>할인<? } ?></td>
					<td><?= number_format($row['car']) ?></td>
					<td><?= date("Y-m-d", strtotime($row['dt_start'])) ?> ~ <?= date("Y-m-d", strtotime($row['dt_end'])) ?></td>
					<td><a class="blackBtn" onclick="delok('삭제하시겠습니까?','/scar.php?code=<?= $code; ?>&mode=d&idx=<?= $row['idx']; ?>&page=<?=$page?>');">삭제</a></td>
				</tr>
				<?
			}
		} ?>
		</tbody>
	</table>
	<div class="paging">
		<?= paging_admin($page, $total_record, $numper, $page_per_block, $qArr); ?>
	</div>
</div>