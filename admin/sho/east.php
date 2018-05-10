<?
/**
 * admin.rentking.co.kr/sho.php?code=east
 * 어드민 > 사이트관리 > 견적문의
 * 견적문의 리스트 페이지
 */
$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 10;

$page_per_block = 10;
$page = $_GET['page'] ? $_GET['page'] : 1;

/* 정렬 기본 */
if ( !$sortcol )
$sortcol = "idx";

if ( !$sortby )
$sortby = "desc";

/* //정렬 기본 */


//HTTP QUERY STRING
$qArr['numper'] = $numper;
$qArr['page'] = $page;
$qArr['code'] = $code;
$qArr['sortcol'] = $sortcol;
$qArr['sortby'] = $sortby;
$qArr['idx'] = $idx;

$q = "SELECT '[FIELD]' FROM estimates WHERE 1";

//카운터쿼리
$sql = str_replace("'[FIELD]'", "COUNT(estimates.idx)", $q);
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
$_sql = str_replace("'[FIELD]'", "estimates.*", $q);

$_tArr = explode(",", $sortcol);
if ( is_array($_tArr) && count($_tArr) ) {
	foreach ( $_tArr as $v ) {
		$orderbyArr[] = "{$v} {$sortby}";
	}
	$orderby = implode(", ", $orderbyArr);
}

$sql_order = " ORDER BY {$orderby}";
$sql_limit = " LIMIT $first, $numper";
$sql = $_sql.$sql_order.$sql_limit;

$r = mysql_query($sql);
while($row = mysql_fetch_array($r)){
	$data[] = $row;
}

//엑셀쿼리
$sql_excel = $_sql.$sql_order;
$_SESSION['sql_excel'] = $sql_excel;
?>
<div class="h3_wrap">
	<h3>총 : <?=number_format($total_record);?> 게시물</h3>
</div>


<div>
<table class="listTableColor">
<colgroup>
	<col width="5%" />
</colgroup>
<thead>
<tr>
<th class="sd1">번호</th>
<th class="sd1">종류</th>
<th class="sd1">방문여부</th>
<th class="sd1">사용기간</th>
<th class="sd1">고객명</th>
<th class="sd1">휴대폰번호</th>
<th class="sd1">요청일</th>
<th class="sd1"></th>
</tr>
</thead>
<tbody>
<?
$article_num = $total_record - (($page-1)*$numper);
for($is=0;$is<count($data);$is++){
	$row = $data[$is];
?>
<tR>
<Td><?=$article_num;?></tD>
<TD><?=$row['kinds'];?></td>
<Td><?=$row['isdeli'];?></tD>
<td><?=$row['date1'];?> ~ <?=$row[date2];?></tD>
<td><?=$row['name'];?></td>
<td><?=$row['cp'];?></td>
<Td><?=$row['dt_create'];?></td>
<td><a href='sho.php?code=<?=$code;?>v&idx=<?=$row['idx'];?>'><span class="blackBtn_small">조회</span></a></tD>
</tr>
<?
	$article_num--;
}
?>
</tbody>
</table>
<div class="paging">
                    <?=paging_admin($page, $total_record, $numper, $page_per_block, $qArr);?>
                </div>
</div><!-- // .list_wrap -->



