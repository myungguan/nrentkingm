<?
/**
 * 어드민 > 사이트관리 > 지역구분설정 > 하위지역
 * admin.rentking.co.kr/sho.php?code=localm&idx=9
 * 시/도 하위 지역 관리 페이지
 */
$idx = $_REQUEST['idx'];
$ar_data = sel_query_all("area"," WHERE idx='$idx'");
$mode = $_REQUEST['mode'];
if($mode=='w') {
	$ac_msg = $_REQUEST['ac_msg'];
	$idx = $_REQUEST['idx'];
	$isuse = $_REQUEST['isuse'];

	for($i=0;$i<sizeof($idx);$i++)	{
		$value['ac_msg'] = $ac_msg[$i];
		$value['isuse'] = $isuse[$i];
		update("area",$value," WHERE idx='$idx[$i]'");
		unset($value);
	}
	
	echo "<script>alert('수정완료'); location.replace('/sho.php?code=$code&idx=$idx'); </script>";
	exit;
}
$q = "SELECT * FROM area WHERE LEFT(ac_code,2)='{$ar_data['ac_code']}' AND LENGTH(ac_code)=5 ORDER BY idx ASC";
$r = mysql_query($q);
while($row = mysql_fetch_array($r)){
	$data[] = $row;
}
?>
<div style='margin-top:20px;'>
	<div class="h3_wrap">
		<h3>지역관리</h3>		
	</div>
	<form name="listform" id="listform" action="/sho.php?code=<?=$code;?>" method="post">
	<input type='hidden' name='mode' value='w'>
	<input type='hidden' name='idx' value='<?=$idx;?>'>
	<table class="listTableColor">
	<thead>
	<tr>
		<th>NO</th>
		<th>지역명</th>
		<th>사용여부</th>
		<th>고객안내메세지</th>

	</tr>
	</thead>
	<tbody>
	<?
	for($is=0;$is<count($data);$is++){
		$row = $data[$is];
	?>
	<tr>
		<td ><?=($is+1);?></td>
		<td ><?=$row['ac_name'];?></td>
		<td>
			<select name='isuse[]'>
			<option value='Y' <? if($row['isuse']=='Y') { echo "selected"; }?>>사용함</option>
			<option value='N' <? if($row['isuse']=='N') { echo "selected"; }?>>사용안함</option>
			</select>
		</td>
		<td ><input type='hidden' name='idx[]' value='<?=$row['idx'];?>'><input type='text' name='ac_msg[]' value='<?=$row['ac_msg'];?>'></td>
		
						<!--//161115 탈퇴 -> 삭제로 변경-->
	</tr>
	<?}?>
	</tbody>
	</table>
	<div class="topBtn" style='text-align:center;'>
		<span class="greenBtn btn_submit" data-form="#listform"><a href="javascript:">수정하기</a></span>
	</div>
	</form>
</div>