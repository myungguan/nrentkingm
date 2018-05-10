<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../adminhead.php";

$idx = $_REQUEST['idx'];
$ar_mem = sel_query_all("member", " where idx='$idx'");

$mode = $_REQUEST['mode'];
if ($mode == 'chmem') {
	$ch_memgrade = $_REQUEST['ch_memgrade'];
	if ($ch_memgrade == $ar_mem[memgrade]) {
		echo "<script>alert('변경할 사항이 없음'); history.back(); </script>";
		exit;
	}


	$value[memgrade] = $ch_memgrade;
	update("member", $value, " where idx='$idx'");
	unset($value);

	echo "<script>alert('정보변경 완료'); history.back(); </script>";
	exit;
}
if ($mode == 'chcon') {
	$canconnect = $_REQUEST['canconnect'];
	if ($canconnect == $ar_mem[canconnect]) {
		echo "<script>alert('변경할 사항이 없음'); history.back(); </script>";
		exit;
	}

	$value[canconnect] = $canconnect;
	update("member", $value, " where idx='$idx'");
	unset($value);

	echo "<script>alert('정보변경 완료'); history.back(); </script>";
	exit;
}

if ($mode == 'chpasswd') {
	$passwd = $_REQUEST['passwd'];
	if (!$passwd) {
		echo "<script>alert('비밀번호 입력'); history.back(); </script>";
		exit;
	}

	$value[passwd] = $passwd;
	update("member", $value, " where idx='$idx'");
	unset($value);

	echo "<script>alert('정보변경 완료'); history.back(); </script>";
	exit;
}
if ($mode == 'www') {
	$log = "";
	$value[name] = $_POST['name'];
	if ($ar_mem[name] != $value[name]) {
		$log .= "이름 : $ar_mem[name] => $value[name]\n\r";
	}


	$value[cp] = $cp;
	if ($ar_mem[cp] != $value[cp]) {
		$log .= "핸드폰 : $ar_mem[cp] => $value[cp]\n\r";
	}

	if ($passwd != '') {
		$value[passwd] = $passwd;
		$log .= "비밀번호 : $value[passwd] \n\r";
	}

	$value[birth] = $_POST['birth'];
	$value[sex] = $_POST['sex'];
//	$value[birthtype] = $_POST['birthtype'];

	$value[canconnect] = $canconnect;
	if ($ar_mem[canconnect] != $value[canconnect]) {
		$log .= "접속여부 : $ar_mem[canconnect] => $value[canconnect]\n\r";
	}

	$value[smsser] = $_POST['smsser'];
	if ($ar_mem[smsser] != $value[smsser]) {
		$log .= "문자수신 : $ar_mem[smsser] => $value[smsser]\n\r";
	}


	$value[mailser] = $_POST['mailser'];
	if ($ar_mem[mailser] != $value[mailser]) {
		$log .= "메일수신 : $ar_mem[mailser] => $value[mailser]\n\r";
	}
//	$value[pushser] = $_POST['pushser'];
//	if ($ar_mem[pushser] != $value[pushser]) {
//		$log .= "앱푸쉬수신 : $ar_mem[pushser] => $value[pushser]\n\r";
//	}


	update("member", $value, " where idx='$idx'");
	unset($value);

	echo "<script>alert('변경완료'); location.replace('$PHP_SELF?idx=$idx'); </script>";
	exit;

}
if ($mode == 'changepa') {
	$passwd = substr(time(), 2, 6);
	$value[passwd] = $passwd;
	update("member", $value, " where idx='$idx'");
	unset($value);

	echo "<script>alert('변경완료'); location.replace('$PHP_SELF?idx=$idx'); </script>";
	exit;
}
?>
<script type="text/javascript">
	function init() {
		var doc = document.getElementById('content');
		if (doc.offsetHeight == 0) {
		} else {
			pageheight = doc.offsetHeight;

			parent.document.getElementById("iframebody").height = pageheight + "px";
		}
	}

	function ch_mem(mode) {
		if (confirm('변경하시겠습니까?')) {
			document.form1.mode.value = mode;
			document.form1.submit();
		}
	}

	window.onload = function () {
		init();
	};
</script>
<script>
	function change_rand() {
		var answer = confirm('비밀번호 랜덤으로 변경 하시겠습니까?');
		if (answer == true) {
			location.href = '<?=$PHP_SELF;?>?code=<?=$code;?>&mode=changepa&idx=<?=$idx;?>';
		}
	}
</script>
<script src='<?=$config['daumPostcode'] ?>'></script>
</head>
<body>
	<div id="content" style="position:absolute;left:0;top:0;width:100%;padding-bottom:160px;">
		<form id="form1" name="form1" action="<?=$PHP_SELF;?>" method="post">
			<input type='hidden' name='mode' value='www'>
			<input type='hidden' name='idx' value='<?=$idx;?>'>
			<div class="form_wrap">
				<table class="detailTable2">
					<tr>
						<?php
						$ar_cp = explode("-", $ar_mem[cp]);
						?>
						<th>성명</th>
						<td><input type='text' name='name' size='20' value='<?=$ar_mem['name'];?>'></td>
					</tr>
					<tr>
						<th>휴대폰번호</th>
						<td>
							<input type='text' name='cp' value='<?=$ar_mem['cp'];?>'>
						</td>
					</tr>
					<tr>
						<th>생년월일</th>
						<td>
							<input type="text" name="birth" size="10" value="<?=$ar_mem['birth']?>" class="datePicker" data-min-view-mode="years" data-parent="body"/>
						</td>
					</tr>
					<tr>
						<th>성별</th>
						<td>
							<select name="sex">
								<option value="F" <?=$ar_mem['sex'] == 'F' ? 'selected' : '' ?>>여성</option>
								<option value="M" <?=$ar_mem['sex'] == 'M' ? 'selected' : '' ?>>남성</option>
							</select>
						</td>
					</tr>
					<!--
					<tr>
						<th>SMS수신</th>
						<td>
							<input type='radio' name='smsser' value='Y' <? if ($ar_mem[smsser] == 'Y') {
								echo "checked";
							} ?>> 수신
							<input type='radio' name='smsser' value='N' <? if ($ar_mem[smsser] != 'Y') {
								echo "checked";
							} ?>> 수신안함
						</td>
					</tr>

					<tr>
						<th>메일수신</th>
						<td>
							<input type='radio' name='mailser' value='Y' <? if ($ar_mem[mailser] == 'Y') {
								echo "checked";
							} ?>> 수신
							<input type='radio' name='mailser' value='N' <? if ($ar_mem[mailser] != 'Y') {
								echo "checked";
							} ?>> 수신안함
						</td>
					</tr>
					<tr>
						<th>앱푸쉬</th>
						<td>
							<input type='radio' name='pushser' value='Y' <? if ($ar_mem[pushser] == 'Y') {
											echo "checked";
										} ?>> 수신
							<input type='radio' name='pushser' value='N' <? if ($ar_mem[pushser] != 'Y') {
											echo "checked";
										} ?>> 수신안함
						</td>
					</tr>
	-->
					<tr>
						<th>접속여부</th>
						<td><select class="uch" name='canconnect'>
								<option value='Y' <? if ($ar_mem[canconnect] == 'Y') {
									echo "selected";
								} ?>>접속허용
								</option>
								<option value='N' <? if ($ar_mem[canconnect] == 'N') {
									echo "selected";
								} ?>>접속차단
								</option>
							</select>
						</td>
					</tr>


				</table>
			</div><!-- // .form_wrap -->
			<div class="btn_wrap btn_center btn_bottom">
				<span class="btn_green btn_submit" data-form="#form1"><a href="javascript:">수정하기</a></span>
			</div>
		</form><!-- // form[name="form1"] -->
	</div>
</body>
</html>