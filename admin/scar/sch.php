<?
$ar_rent = sel_query_all("rentshop"," WHERE idx={$_SESSION['rentshop_idx']}");
$mode = $_REQUEST['mode'];
if($mode=='w')	{
	$value[rentshop_idx] = $ar_rent[idx];
	$value[sdate] = $_REQUEST['sdate'];
	$value[edate] = $_REQUEST['edate'];
	$value[dt_create] = date("Y-m-d H:i:s");
	insert("rentshop_off",$value);
	unset($value);

	echo "<script>alert('전체휴차처리가 완료되었습니다'); location.replace('$PHP_SELF?code=$code'); </script>";
	exit;
}
if($mode=='d')	{
	
	$idx = $_REQUEST['idx'];
	mysql_query("DELETE FROM rentshop_off WHERE idx='$idx'");

	echo "<script>alert('삭제하였습니다.'); location.replace('$PHP_SELF?code=$code'); </script>";
	exit;

}	

$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 25;

$page_per_block = 10;
$page = $_GET['page'] ? $_GET['page'] : 1;

/* 정렬 기본 */
if ( !$sortcol )
$sortcol = "dt_create";

if ( !$sortby )
$sortby = "desc";

/* //정렬 기본 */


//HTTP QUERY STRING
$keyword = trim($keyword);
$key = $_REQUEST['key'];
$qArr['numper'] = $numper;
$qArr['page'] = $page;
$qArr['code'] = $code;
$qArr['sortcol'] = $sortcol;
$qArr['sortby'] = $sortby;



$q = "SELECT [FIELD] FROM vehicles WHERE rentshop_idx='$ar_rent[idx]' AND dt_delete IS NULL";


//카운터쿼리
$sql = str_replace("[FIELD]", "COUNT(idx)", $q);
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
$_sql = str_replace("[FIELD]", "*", $q);

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

<script language="javascript" type="text/javascript">
function offch()	{
	if($("#sdates").val()=='' || $("#edates").val()=='')	{
		alert('휴차기간을 입력하세요');
		return false;
	}

	answer = confirm('전체 휴차설정을 하시겠습니까?');
	if(answer==true)	{
		return true;
	}
	else	{
		return false;
	}
}
</script>
			<form name="regiform" id="regiform" method="post" onsubmit="return offch();">
			<input type='hidden' name='mode' value='w'>

			<div>
				<span class="subTitle">전체휴차설정</span>
				<table class="detailTable2">
				<tbody>
				<tr>
					<th>전체휴차설정</th>
					<td>
						<input type='text' name='sdate' id='sdates' class="datePicker" data-parent="#container" readonly> ~
						<input type='text' name='edate' id='edates' class="datePicker" data-parent="#container" readonly>
					</td>
				</tr>
				</tbody>
				</table>
			</div>
			<div class="btn_wrap btn_center btn_bottom">
				<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">설정하기</a></span>
			</div>
			</form>
			<div>
				<span class="subTitle">전체휴차설정내역</span>
				<table class="listTableColor">
				<thead>
				<tr>
					<th>NO</th>
					<th>기간</th>
					<th>등록일</th>
					<th>비고</th>
				</tr>
				</thead>
				<tbody>
<?
$q = "SELECT * FROM rentshop_off WHERE rentshop_idx='$ar_rent[idx]'";
$r = mysql_query($q);
$cou = 1;
while($row = mysql_fetch_array($r))	{
?>
				<tr>
					<td><?=$cou;?></td>
					<td><?=$row[sdate];?> ~ <?=$row[edate];?></td>
					<td><?=$row[wdate];?></td>
					<td><span class="blackBtn" onclick="delok('삭제하시겠습니까?','<?=$PHP_SELF;?>?code=<?=$code;?>&mode=d&idx=<?=$row[idx];?>');">삭제</span></td>
				</tR>
<?
	$cou++;
}
?>
				</tbody>
				</table>
			</div>


			<div>
				<span class="subTitle">개별휴차설정</span>
				<table class="listTableColor">
					<thead>
						<tr>

							<th>모델</th>
							<th>차량번호</th>
							<th>출고일</th>
							<th>마일리지(km)</th>
							<th>일판매금액</th>
							<th>월판매금액</th>
							<th>월분할일<br>판매금액</th>
							<th>비고</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?
					for($is=0;$is<count($data);$is++){
						$row = $data[$is];

						$ar_std = sel_query_all("vehicle_models"," WHERE idx='$row[model_idx]'");
						
						$ac1_1 = unserialize($row[ac1_1]);
						$acl1 = unserialize($row[acl1]);
						
					?>
						<tr>
							<td><?=$ar_std[name];?></td>
							<td><?=$row[carnum];?></td>
							<td><?=$row[outdate];?></td>
							<td><?=$row[rundistan];?></td>
							<td><?=number_format($ac1_1[0]);?></td>
							<td><?=number_format($acl1[0]);?></td>
							<td><?=number_format($acl1[0]/30);?></td>
							<td>
							<?
							$qs = "SELECT * FROM vehicle_off WHERE vehicle_idx='$row[idx]' AND offsdate>='".date("Y-m-d")."'";
							$rs = mysql_query($qs);
							$isits = mysql_num_rows($rs);

							if($isits!=0)	{
								echo "휴차등록 : $isits 건";
							}
							?>
							</td>
							<td><span class="blackBtn" onclick="MM_openBrWindow('/popup/caroff.php?vehicle_idx=<?=$row[idx];?>','caroff<?=$row[idx];?>','scrollbars=yes,width=1150,height=700,top=0,left=0');">휴차설정</span></td>
						</tr>
					<?}?>
					</tbody>
				</table>
				 <div class="paging">
                    <?=paging_admin($page, $total_record, $numper, $page_per_block, $qArr);?>
                </div>
			</div>		