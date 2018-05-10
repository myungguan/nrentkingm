<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../adminhead.php";

$idx = $_REQUEST['idx'];
$mode = $_REQUEST['mode'];

if ( !$idx ) {
	echo "<script>alert('잘못된 접근입니다.'); window.close(); </script>";
	exit;	
}

$query = "
	SELECT
		m.*,
		mt.edate,
		mt.vehicle_idx
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		m.idx = $idx
";
$ar_data = mysql_fetch_assoc(mysql_query($query));

if($mode=='w')	{

	$query = "UPDATE payments SET dan=3, dt_return=NOW(), dt_next_payment=NULL WHERE idx=".$idx;
	mysql_query($query);
	
	$value['status'] = "S";
	update("vehicles",$value," WHERE idx='{$ar_data['vehicle_idx']}'");
	unset($value);

	echo "<script>alert('반납처리가 완료되었습니다'); opener.location.reload(); window.close(); </script>";
	exit;
}

?>
<script>
function foch()	{
	return confirm('반납처리 하시겠습니까?');
}
</script>
<div id="pop_contents">
	<span class="subTitle">* 반납처리</span>
	<form name="regiform" id="regiform" action="<?=$PHP_SELF;?>" onsubmit="return foch();">
	<input type='hidden' name='idx' value='<?=$idx;?>'>
	<input type='hidden' name='mode' value='w'>
	<table class="detailTable2">
	
	<tr>
		<th>반납예정일</th>
		<td><?=$ar_data['edate'];?></td>
		<th>현재시간</th>
		<td><?=date("Y-m-d H:i:s");?></td>
	</tr>
		<!--
	<tr>
		<th>추가결제금액</th>
		<td colspan='3'><input type='text' name='memo'></td>
	</tr>
	<tr>
		<th>추가결제사유</th>
		<td colspan='3'><input type='text' name='memo'></td>
	</tr>
	<tr>
		<th>차량마일리지</th>
		<td colspan='3'><input type='text' name='memo'></td>
	</tr>
	-->
	</table>
	<div class="topBtn" style='text-align:center;'>
		<span class="greenBtn btn_submit" data-form="#regiform"><a href="javascript:">반납처리</a></span>
	</div>

	</form>
</div><!-- // .content -->
