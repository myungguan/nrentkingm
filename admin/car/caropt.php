<?
/**
 * 어드민 > 차량관리 > 차량옵션관리
 * admin.rentking.co.kr/car.php?code=caropt
 * 멤버사 차량 옵션관리 페이지
 */
if($_REQUEST['mode'] == 'w') {
	$value['itemname'] = mysql_escape_string($_REQUEST['itemname']);
	$value['bases'] = mysql_escape_string($_REQUEST['bases']);
	$value['ishave'] = $_REQUEST['ishave'];
	$value['dt_create'] = $value['dt_update'] = date('Y-m-d H:i:s');
	insert("vehicle_opt_std", $value);

	echo "<script type='text/javascript'>alert('등록완료'); location.replace('../car.php?code={$code}&fid={$fid}'); </script>";
	exit;
}
if ($_REQUEST['mode'] == 'm') {
	$idx = $_REQUEST['idx'];
	$itemname = $_REQUEST['itemname'];
	$bases = $_REQUEST['bases'];

	for ($i = 0; $i < sizeof($idx); $i++) {
		$ishave = $_REQUEST['ishave'.$idx[$i]];
		$value['itemname'] = mysql_escape_string($itemname[$i]);
		$value['bases'] = mysql_escape_string($bases[$i]);
		$value['ishave'] =$ishave ? 'Y' : 'N';
		$value['dt_update'] = date('Y-m-d H:i:s');
		update("vehicle_opt_std", $value, " WHERE idx='{$idx[$i]}'");
		unset($value);
	}

	echo "<script type='text/javascript'>alert('수정완료'); location.replace('../car.php?code={$code}&fid={$fid}'); </script>";
	exit;
}
?>
<script>
function gogoch()
{
	if($("#itemname").val() == '')
	{
		alert('항목명을 입력하세요');
		return false;
	}

	if(confirm('새로운 항목을 입력하시겠습니까?'))
	{	return true;	}
	else
	{	return false;	}
}
function modch()
{
	var answer = confirm('수정하시겠습니까?');
	if(answer==true)
	{	return true;	}
	else
	{	return false;	}
}
</script>
<div style='margin-top:30px;'>
	<form id="regiform" name="regiform" action="../car.php?code=<?=$code;?>" method="post" ENCTYPE="multipart/form-data" onsubmit="return gogoch();">
	<input type='hidden' name='mode' value='w'>
	<table class="detailTable2">
	<tbody>
	<tr>
		<th>항목명</th>
		<td><input type='text' maxlength="50" name='itemname' id="itemname"></td>
	</tr>
	</tr>
	<tr>
		<th>필수 여부</th>
		<td><label><input type='checkbox' name='ishave' value='Y'>필수</label></td>
	</tr>
	<tr>
		<th>기초정보</th>
		<td>
			<input type='text' name='bases' id="bases" style='width:95%;'>
			<br />[기초 정보는 |R|로 구분하여 입력하세요]
		</td>
	</tr>
	</tbody>
	</table>
	<div class="btn_wrap btn_center btn_bottom">
		<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">등록</a></span>
	</div>
	</form>
</div>
<div style='margin-top:30px;'>
	<form name="mform" id="mform" action="../car.php?code=<?=$code;?>" method="post" onsubmit="return modch();">
	<input type='hidden' name='mode' value='m'>
	
	<span class="subTitle">등록된항목</span>
	<table class="listTable">
	<colgroup>
		<col width="50px;">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">No.</th>
		<th scope="col">항목명</th>
		<th scope="col">기초자료</th>
		<th scope="col">필수</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$q = "SELECT * FROM vehicle_opt_std ORDER BY idx ASC";
	$r = mysql_query($q);
	$cou = 0;
	while($row = mysql_fetch_array($r))	{
	?>
	<tr>
		<td><?=$row['idx'];?></td>
		<td><input type='hidden' name='idx[]' value='<?=$row['idx'];?>'><input type='text' maxlength="50" name='itemname[]' value='<?=$row['itemname'];?>'></td>
		<td><input type='text' name='bases[]' value='<?=$row['bases'];?>' style='width:300px;'></td>
		<td><label><input type='checkbox' name="ishave<?=$row['idx']?>" value="Y" <? if($row['ishave']=='Y') { echo "checked";	}?>>필수</label></td>
	</tr>
	<?php
		$cou++;
	}
	?>
	</table>
	<div class="btn_wrap btn_center btn_bottom">
		<span class="greenBtn btn_submit" data-form="#mform"><a href="javascript:">수정하기</a></span>
	</div>
	</form>
</div><!-- // .list_wrap -->
