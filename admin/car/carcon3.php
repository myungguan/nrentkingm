<?
/**
 * 어드민 > 차량관리 > 차량속성관리 > 연료타입관리
 * admin.rentking.co.kr/car.php?code=carcon3
 * 차량 속성관리_연료타입관리 페이지
 */
$mode = $_REQUEST['mode'];
if ($mode == 'w') {
	$value['ttype'] = "3";
	$value['sname'] = mysql_escape_string($_REQUEST['sname']);
	$value['dt_update'] = $value['dt_create'] = date('Y-m-d H:i:s');
	insert("codes", $value);

	echo "<script type='text/javascript'>alert('처리완료'); location.replace('car.php?code={$code}'); </script>";
	exit;
}
?>
<script>
function foch(f) {
	var re = check_form(f);
	if(re)	{
		var answer = confirm('등록 하시겠습니까?');
		if(answer==true) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
</script>
<div style="margin:20px 0;">
	<span class="greenBtn_small"><a href="../car.php?code=carcon1">제조사관리</a></span>
	<span class="greenBtn_small"><a href="../car.php?code=carcon2">등급관리</a></span>
	<span class="greenBtn_small"><a href="../car.php?code=carcon3">연료타입관리</a></span>
	<span class="greenBtn_small"><a href="../car.php?code=carcon4">색상관리</a></span>
</div>
<div class="row-fluid">
	<div class="span6">
		<span class="subTitle">등록된연료타입</span>
		<table class="listTable">
		<thead>
		<tr>
			<th>No.</th>
			<th>명칭</th>
		</tr>
		</thead>
		<tbody>
		<?
		for($i=0;$i<sizeof($ar_ttype3_idx);$i++)	{
		?>
		<tr>
			<td><?=$ar_ttype3_idx[$i];?></td>
			<td style='text-align:left;padding-left:5px;'><?=$ar_ttype3_sname[$ar_ttype3_idx[$i]];?></td>
		</tr>
		<?
		}
		?>
		</tbody>
		</table>
	</div>
	<div class="span6">	
		<form name="regiform2" id="regiform2" action="../car.php?code=<?=$code;?>" enctype="multipart/form-data" method="post" onsubmit="return foch('regiform2')";>
		<input type='hidden' name='mode' value='w'>
		<span class="subTitle">연료타입등록</span>
		<table class="detailTable2">
		<tbody>
		<tr>
			<th>연료타입명</th>
			<td><input type='text' maxlength="50" name='sname' valch="yes" msg="연료타입명"></td>
		</tr>
		</tbody>
		</table>
		<div class="btn_wrap btn_center btn_bottom">
			<span class="greenBtn btn_submit" data-form="#regiform2"><a href="javascript:">등록</a></span>
		</div>
		</form>
	</div>
</div>