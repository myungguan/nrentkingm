<?
/**
 * 어드민 > 고객관리 > 회원 > 문자발송 || 어드민 > 고객관리 > 법인(단체) > 문자발송
 * admin.rentking.co.kr/popup/sms.php
 * 어드민 고객 문자 발송
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../adminhead.php";

if ($mainfid != 'A') {
	$fid = $mainfid;
} else {
	if (!$fid && $selectfid) {
		$fid = $selectfid;
	}
}

$str = $_REQUEST['str'];
$ar_str = explode("|R|", $str);
$mode = $_REQUEST['mode'];
if ($mode == 'del_sms') {
	$renum = $_REQUEST['renum'];
	$message = $_REQUEST['message'];
	$lms = $_REQUEST['lms'];

	if ($_SESSION['sql_excel']) {
		$q = $_SESSION['sql_excel'];
		$r = mysql_query($q);
		while ($row = mysql_fetch_array($r)) {

			if ($row['cp']) {
				sendSMS($row['cp'], $message, $lms, "");
			}
		}
	}
	echo "<script type='text/javascript'>alert('발송완료'); window.close(); </script>";
	exit;
}
?>
<script language="javascript">
	function textCounter(theField, aa) {
		var strCharCounter = 0;
		var intLength = theField.value.length;

		for (var i = 0; i < intLength; i++) {
			var charCode = theField.value.charCodeAt(i);
			if (charCode > 128) {
				strCharCounter += 2;
			} else {
				strCharCounter++;
			}
		}
		$("#bs" + aa).html(strCharCounter + 'Byte');
	}
	function setsms() {
		if ($("#renum").val() == '') {
			alert('발송번호를 입력하세요');
			return;
		}

		if ($("#message").val() == '') {
			alert('내용을 입력하세요');
			return;
		}
		var answer = confirm('발송하시겠습니까?');
		if (answer == true) {
			return true;
		} else {
			return false;
		}
	}
</script>
<body>
<div id="pop_contents">
	<div class="h3_wrap">
		<h3>* 문자발송</h3>
	</div>
	<table cellpadding=0 cellspacing=0 border=0 width=100%>
		<tr>
			<Td width='30%' valign='top' align='center'>
				<form method="post" action="<?= $PHP_SELF; ?>" onsubmit="return setsms();" name="sendform">
					<input type="hidden" name="mode" value="del_sms">
					<input type="hidden" name="str" value="<?= $str; ?>">
					<div style='background-color:#ededed;width:200px;padding:5px;'>
						<p style='padding-top:5px;font-weight:bold;text-align:center;'>보내는번호 : <input type='text' name='renum' id='renum' size='20' value='<?= $ar_kinit['site_phone']; ?>'>
							<input type='checkbox' name='lms' value='l'> LSM</p>
						<textarea name="message" id="message" style='width:178px;height:258px;border:1px solid #e0e0e0;margin:0 auto;' onKeyUp="textCounter(sendform.message,1);" style="ime-mode:active"><?= $ar_da['memo3']; ?></textarea>
						<p style='padding-top:5px;font-weight:bold;text-align:center;' id="bs1">00Byte</p>
					</div>
					<p style='text-align:center;'><input type='submit' value='발송하기'/>
				</form>


			</td>
			<td width="80%" valign='top'>
				<?
				//데이터 쿼리 세션에서 쿼리 가져옴
				if ($_SESSION['sql_excel']) {
					$q = $_SESSION['sql_excel'];
					$r = mysql_query($q);
					$isit = mysql_num_rows($r);
				}
				?>
				<table class="detailTable2">


					<tr>
						<th>발송대상</th>
						<td><?= $isit; ?>건</td>
					</tr>
					</td>
					</tr>
				</table>
</div><!-- pop_contents -->
</body>
</html>