<?
/**
 * 어드민 > 차량관리 > 모델관리
 * admin.rentking.co.kr/admin/car.php?code=plist
 * 차량 모델관리 페이지
 */
$mode = $_REQUEST['mode'];
if ($mode == 'd') {

	$idx = $_REQUEST['idx'];

	mysql_query("update vehicle_models set dt_delete=NOW() WHERE idx='$idx'");

	echo "<script>alert('삭제완료'); location.replace('/car.php?code=$code'); </script>";
	exit;
}
$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 10;

$page_per_block = 10;
$page = $_GET['page'] ? $_GET['page'] : 1;

/* 정렬 기본 */
if (!$sortcol)
	$sortcol = "idx";

if (!$sortby)
	$sortby = "desc";

/* //정렬 기본 */
$grade_idx = $_REQUEST['grade_idx'];

//HTTP QUERY STRING
$qArr['numper'] = $numper;
$qArr['page'] = $page;
$qArr['code'] = $code;
$qArr['sortcol'] = $sortcol;
$qArr['sortby'] = $sortby;
$qArr['company_idx'] = $company_idx;
$qArr['grade_idx'] = $grade_idx;
$qArr['fuel_idx'] = $fuel_idx;
$qArr['name'] = mysql_escape_string($name);

$q = "SELECT '[FIELD]' FROM vehicle_models vs";
if ($fuel_idx) {
	$q .= ' LEFT JOIN vehicle_model_fuel_codes vsfc ON vs.idx = vsfc.vehicle_model_idx';
	$q .= " WHERE vs.dt_delete IS NULL ";
	$q .= " AND vsfc.code_idx = ".$fuel_idx;
}else{
	$q .= " WHERE vs.dt_delete IS NULL ";
}

if ($company_idx) {
	$q = $q . " AND vs.company_idx='{$company_idx}'";
}
if ($grade_idx) {
	$q = $q . " AND vs.grade_idx='{$grade_idx}'";
}
if ($name) {
	$q = $q . " AND vs.name like '%".mysql_escape_string($name)."%'";
}
//카운터쿼리
$sql = str_replace("'[FIELD]'", "COUNT(vs.idx)", $q);
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

$select_query = "
(
	SELECT GROUP_CONCAT(c.sname SEPARATOR ', ')
	FROM vehicle_model_fuel_codes vsfc2
	LEFT JOIN codes c ON vsfc2.code_idx = c.idx
	WHERE vsfc2.vehicle_model_idx = vs.idx
) concat_fuel,
vs.*
";

//데이터쿼리
$_sql = str_replace("'[FIELD]'", $select_query, $q);

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
<Div style='margin-top:10px;'>
	<form id="search" name="search" action="../car.php" method="get">
		<input type="hidden" name="code" value="<?=$code;?>" />
	<table class="detailTable2">
	<tr>
		<th>제조사</th>
		<td colspan='3'><select name="company_idx" class="mWt200">
		<option value="">제조사 선택</option>
		<?
		for($i=0;$i<sizeof($ar_ttype1_idx);$i++) {
			$se = "";
			if($ar_ttype1_idx[$i]==$company_idx) {
				$se = "selected";	
			}
			echo "<option value='{$ar_ttype1_idx[$i]}' $se>{$ar_ttype1_sname[$ar_ttype1_idx[$i]]}</option>";
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<th>등급</th>
		<td><select name="grade_idx" class="mWt200">
		<option value="">등급 선택</option>
		<?
		for($i=0;$i<sizeof($ar_ttype2_idx);$i++) {
			$se = "";
			if($ar_ttype2_idx[$i]==$grade_idx) {
				$se = "selected";	
			}
			echo "<option value='{$ar_ttype2_idx[$i]}' $se>{$ar_ttype2_sname[$ar_ttype2_idx[$i]]}</option>";
		}
		?>
		</select>
		</td>
		<th>연료</th>
		<td><select name="fuel_idx" class="mWt200">
		<option value="">등급 선택</option>
		<?
		for($i=0;$i<sizeof($ar_ttype3_idx);$i++) {
			$se = "";
			if($ar_ttype3_idx[$i]==$fuel_idx) {
				$se = "selected";	
			}
			echo "<option value='{$ar_ttype3_idx[$i]}' $se>{$ar_ttype3_sname[$ar_ttype3_idx[$i]]}</option>";
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<th>차량명</th>
		<td colspan='3'><input type='text' maxlength="50" name='name' value='<?=$name;?>'></td>
	</tr>
	</table>
	<div class="btn_wrap btn_center btn_bottom">
		<a href="javascript:"><span class="greenBtn btn_submit" data-form="#search">검색하기</span></a>
		<a href="../car.php?code=regi"><span class="greenBtn">등록하기</span></a>
	</div>
	<input type="submit" style="display:none;"/>
	</form>
</div><!-- // .form_wrap -->
<div>
	<span class="subTitle">* 총 <?=number_format($total_record);?>대의 표준차량이 등록 되어 있습니다.</span>
	<table class="listTableColor">
		<thead>
			<tr>
				<th>No.</th>
				<th>제조사</th>
				<th>모델명</th>
				<th>등급</th>
				<th>연료</th>
				<!--161130 style="width:50px;" 추가-->
				<th style="width:50px;">비고</th>
				<!--161130 style="width:50px;" 추가-->
			</tr>
		</thead>
		<tbody>
		<?
		for($is=0;$is<count($data);$is++){
			$row = $data[$is];
		?>
			<tr>
				<td><?=$row['idx'];?></td>
				<td style="text-align:left;padding:0 5px;"><?=$ar_ttype1_sname[$row['company_idx']];?></td>
				<td style="text-align:left;padding:0 5px;"><a href="../car.php?code=regi&idx=<?=$row['idx'];?>&page=<?=$page?>&company_idx=<?=$company_idx?>&grade_idx=<?=$grade_idx?>&fuel_idx=<?=$fuel_idx?>&name=<?=$name?>" style="color:#000000"><?=$row['name'];?></a></td>
				<td><?=$ar_ttype2_sname[$row['grade_idx']];?></td>
				<td style="text-align:left;padding:0 5px;"><?=$row['concat_fuel']?></td>
				<td><span class="blackBtn_small" onclick="delok('삭제하시겠습니까?','../car.php?code=<?=$code;?>&mode=d&idx=<?=$row['idx'];?>');">삭제</span></td>
			</tr>
			<?}?>
		</tbody>
	</table>
</div>
<!-- paging     16.11.25추가  -->
<div class="paging">
	<?=paging_admin($page, $total_record, $numper, $page_per_block, $qArr);?>
</div>
<!-- // paging     16.11.25추가 -->
