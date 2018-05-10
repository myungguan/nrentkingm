<?
$mode = $_REQUEST['mode'];
if($mode=='w')	{
	
	$ac_msg = $_REQUEST['ac_msg'];
	$orders = $_REQUEST['orders'];
	$idx = $_REQUEST['idx'];
	$isuse = $_REQUEST['isuse'];

	for($i=0;$i<sizeof($idx);$i++)	{
		$value['ac_msg'] = $ac_msg[$i];
		$value['orders'] = $orders[$i];
		$value['isuse'] = $isuse[$i];
		update("area",$value," WHERE idx='$idx[$i]'");

	}
	
	echo "<script>alert('수정완료'); location.replace('/sho.php?code=$code'); </script>";
	exit;
}
$q = "SELECT * FROM area WHERE LENGTH(ac_code)='2' ORDER BY orders ASC";
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
	<table class="listTableColor">
	<thead>
	<tr>
		<th>NO</th>
		<th>지역명</th>
		<th>지역명대체</th>
		<th>고객안내메세지</th>
		<th>사용여부</th>
		<th>정렬순서</th>
		<th></th>
	</tr>
	</thead>
	<tbody>
	<?
	for($is=0;$is<count($data);$is++){
		$row = $data[$is];
	?>
	<tr>
		<td ><?=($is+1);?></td>
		<td style="text-align:left;padding:0 5px;"><?=$row['ac_name'];?></td>
		<td ><?=$row['ac_alias'];?></td>
		<td ><input type='hidden' name='idx[]' value='<?=$row['idx'];?>'><input type='text' name='ac_msg[]' value='<?=$row['ac_msg'];?>'></td>
		<td>
			<select name='isuse[]'>
			<option value='Y' <? if($row['isuse']=='Y') { echo "selected"; }?>>사용함</option>
			<option value='N' <? if($row['isuse']=='N') { echo "selected"; }?>>사용안함</option>
			</select>
		</td>
		<Td><input type='text' name='orders[]' value='<?=$row['orders'];?>'></td>
		<td><span class="blackBtn_small" onclick="location.href='/sho.php?code=<?=$code;?>m&idx=<?=$row['idx'];?>';">하위지역</span></td>
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