<?
/**
 * 어드민 > 고객관리 > 회원 > 상세조회(팝업) > 회원가입정보_회원메모(팝업)
 * admin.rentking.co.kr/popup/member_memo.php?idx=1131
 * 회원 메모 팝업
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../adminhead.php";

$idx = $_REQUEST['idx'];
$mode = $_REQUEST['mode'];

$ar_mem = sel_query_all("member", "WHERE idx='{$idx}'");
if ($mode == 'w') {
	$rmemo = $_POST['rmemo'];
	$rtype = $_POST['rtype'];

	$memo = $ar_mem['rmemo'];

	if ($memo != '') {
		$memo = "{$memo}\n\r\n\r";
	}

	$memo = $memo . date("Y-m-d H:i:s") . "에 {$g_memname}님이 추가한내용\n\r";

	if ($rtype != $ar_mem['rtype']) {
		$memo = $memo . $ar_mem['rtype'] . "==>" . $rtype . "\n\r";
	}
	$memo = $memo . $rmemo;
	$value['rmemo'] = addslashes($memo);

	update("member", $value, " WHERE idx='$idx'");
	unset($value);

	echo "<script type='text/javascript'>alert('완료'); opener.location.reload(); window.close(); </script>";

	exit;
}
?>
<div id="pop_contents">
	<form id="regiform" name="regiform" action="../popup/member_memo.php" method="post" enctype="multipart/form-data">
		<input type='hidden' name='mode' value='w'>
		<input type='hidden' name='idx' value='<?= $idx; ?>'>
		<div class="form_wrap">
			<table class="detailTable2">
				<tr>
					<th>
						기존메모
					</th>
					<td colspan='3'>
						<?= nl2br($ar_mem['rmemo']); ?>
					</td>
				</tr>
				<tr>
					<th>
						추가메모
					</th>
					<td colspan='3'>
						<textarea name='rmemo' style='width:90%;height:200px;'></textarea>
					</td>
				</tr>
			</table>
			<div class="btn_wrap btn_center btn_bottom">
				<span class="btn_green btn_submit" data-form="#regiform"><a href="javascript:">수정하기</a></span>
			</div>
	</form><!-- // form[name="regiform"]  -->
</div><!-- // .form_wrap  -->