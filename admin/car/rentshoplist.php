<?
/**
 * 어드민 > 차량관리 > 업체별등록현황
 * admin.rentking.co.kr/car.php?code=rentshoplist
 * 업체별차량등록현황 페이지
 */
$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 20;

$page_per_block = 10;
$page = $_GET['page'] ? $_GET['page'] : 1;

/* 정렬 기본 */
if ( !$sortcol )
$sortcol = "idx";

if ( !$sortby )
$sortby = "desc";

/* //정렬 기본 */

$searchKeyword = trim($_REQUEST['searchKeyword']);

//HTTP QUERY STRING
$qArr['numper'] = $numper;
$qArr['page'] = $page;
$qArr['code'] = $code;
$qArr['sortcol'] = $sortcol;
$qArr['sortby'] = $sortby;
$qArr['searchKeyword'] = $searchKeyword;


$q = "SELECT '[FIELD]' FROM rentshop r
WHERE 1";
if($searchKeyword) {
	$q .= " AND (r.name LIKE '%".mysql_escape_string($searchKeyword)."%' OR r.affiliate LIKE '%".mysql_escape_string($searchKeyword)."%')";
}

//카운터쿼리
$sql = str_replace("'[FIELD]'", "COUNT(r.idx)", $q);
$r = mysql_query($sql);
$total_record = mysql_result($r,0,0);
mysql_free_result($r);

if($total_record == 0) { 
	$first = 0;
	$last = 0;
} else { 
	$first = $numper*($page-1);
	$last = $numper*$page; 
}

//데이터쿼리
$_sql = str_replace("'[FIELD]'", "r.*, r.name", $q);

$_tArr = explode(",", $sortcol);
if ( is_array($_tArr) && count($_tArr) ) {
	foreach ( $_tArr as $v ) {
		$orderbyArr[] = "{$v} {$sortby}";
	}
	$orderby = implode(", ", $orderbyArr);
}

$sql_order = " ORDER BY {$orderby}";
$sql_limit = " LIMIT {$first}, {$numper}";
$sql = $_sql.$sql_order.$sql_limit;

$r = mysql_query($sql);
while($row = mysql_fetch_array($r)){
	$data[] = $row;
}

//엑셀쿼리
$sql_excel = $_sql.$sql_order;
$_SESSION['sql_excel'] = $sql_excel;
?>
<form name="listform" id="listform" method="get" action="car.php" style="margin:10px 0;height:30px;">
	<input type="hidden" name="code" value="<?= $code ?>" />
	<input type="text" name="searchKeyword" value="<?= $searchKeyword ?>" class="" style="margin-right: 20px;" placeholder="멤버사">
	<span class="greenBtn btn_submit" data-form="#listform">검색하기</span>
	<span class="greenBtn" style="float:right;" onclick="location.href='../excel/excel_down.php?act=rentlist';">EXCEL</span>
</form>

<div  id='printdiv'>
	<table class="listTableColor4">
	<colgroup>
		<col width="50px;">
	</colgroup>
		<thead>
			<tr>
				<th rowspan="2">No.</th>
				<!--16.10.27 제휴사명(지점) -> 지점/멤버사 텍스트수정-->
				<th rowspan="2">지점/멤버사</th>
				<!--//16.10.27 제휴사명(지점) -> 지점/멤버사 텍스트수정-->
				<th rowspan="2">총대수</th>
				<th rowspan="2">등록대수</th>
				<th colspan="<?=(sizeof($ar_ttype2_idx)+1);?>">등록현황</th>
			</tr>
			<tr>
				<?
				for($i=0;$i<sizeof($ar_ttype2_idx);$i++)	{
				?>
				<th style="width:80px;"><?=$ar_ttype2_sname[$ar_ttype2_idx[$i]];?></th>
				<?}?>
				<th style="width:80px;">계</th>
			</tr>
		</thead>
		<tbody>
		<?
		$article_num = $total_record - (($page - 1) * $numper);
		for ($is = 0; $is < count($data); $is++) {
			$row = $data[$is];
			unset($ar_now);
			$qs = "
				SELECT
					v.*,
					grade_idx
				FROM vehicles v
					LEFT JOIN vehicle_models vs ON vs.idx=v.model_idx
				WHERE v.rentshop_idx='{$row['idx']}' AND v.dt_delete IS NULL";
			$rs = mysql_query($qs);
			$total = 0;
			while ($rows = mysql_fetch_array($rs)) {
				if (!$ar_now[$rows['grade_idx']]) {
					$ar_now[$rows['grade_idx']] = 0;
				}
				$ar_now[$rows['grade_idx']]++;
				$total++;
			}
		?>
			<tr>
				<td><?=$article_num;?></td>
				<th style="text-align:left;padding:0 5px;"><a href="/car.php?code=<?=$code;?>_s1&idx=<?=$row['idx'];?>" target="_blank"><?=$row['name'];?> (<?=$row['affiliate'];?>)</a></th>
				<td><?=number_format($row['totalcar']);?></td>
				<td><?=number_format($total);?></td>
				<?
				for($i=0;$i<sizeof($ar_ttype2_idx);$i++)	{
				?>
				<td><?=number_format($ar_now[$ar_ttype2_idx[$i]]);?></td>
				<?}?>
				<td><?=number_format($total);?></td>
			</tr>
		<?
			$article_num--;
		}
		?>
		</tbody>
	</table>
	<!-- paging     161123추가  -->
	<div class="paging">
		<?= paging_admin($page, $total_record, $numper, $page_per_block, $qArr); ?>
	</div>
	<!-- //paging -->
</div>