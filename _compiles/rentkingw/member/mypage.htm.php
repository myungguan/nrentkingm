<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/member/mypage.htm 000006235 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
<?php $this->print_("side_member",$TPL_SCP,1);?>

		<div id="content">
			<div class="conBanner">
				<div class="txt">
<?php if($TPL_VAR["global"]["memgrade"]=='100'||$TPL_VAR["global"]["memgrade"]=='12'){?>
					<h2>내정보</h2>
					<strong>보안 철저! 내정보 입니다!</strong>
					<p>렌트킹은 회원님들의 개인정보를 철저하게 보호하고 있습니다!</p>
<?php }else{?>
					<h2>법인정보</h2>
					<strong>보안 철저! 내정보 입니다!</strong>
					<p>렌트킹은 회원님들의 개인정보를 철저하게 보호하고 있습니다!</p>
<?php }?>
				</div><!--//txt-->
				<span><img src="/imgs/rentking.w/mypageTopicon.jpg" alt="Icon"></span>
			</div><!--//conBanner-->

			<table class="list">
				<colgroup>
					<col width="120px">
					<col>
					<col>
				</colgroup>
				<tr>
					<th>이름</th>
					<td colspan="2"><?php echo $TPL_VAR["member"]["name"]?></td>
				</tr>
				<tr>
					<th>아이디</th>
					<td colspan="2"><?php echo $TPL_VAR["member"]["id"]?></td>
				</tr>
				<tr>
					<th>비밀번호</th>
					<td>**********</td>
					<td style="text-align:right;">
						<button type="button" class="btn-point" onclick="$('#popupPw').trigger('open');">변경하기</button>
					</td>
				</tr>
				<tr>
					<th>휴대폰번호</th>
					<td><?php echo phone_number_format($TPL_VAR["member"]["cp"])?></td>
					<td style="text-align:right;">
						<button type="button" class="btn-point" onclick="$('#popupPhone').trigger('open');">변경하기</button>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<!--비밀번호 변경 팝업-->
	<div class="pop-wrap" id="popupPw">
		<form class="pop-content" name="mod_form1" id="mod_form1" style="width:340px;">
			<div class="pop-header">
				<h3>비밀번호 변경</h3>
				<button class="pop-close">닫기</button>
			</div>
			<div class="pop-body">
				<table class="list">
					<tr>
						<th>현재 비밀번호</th>
						<td><input type="password" name="nowpasswd" id="nowpasswd"></td>
					</tr>
					<tr>
						<th>변경할 비밀번호</th>
						<td><input type="password" name="passwd" id="passwd"></td>
					</tr>
					<tr>
						<th>비밀번호 확인</th>
						<td><input type="password" name="repasswd" id="repasswd"></td>
					</tr>
				</table>
				<div style="text-align:center;margin-top:20px;">
					<button type="button" class="btn-normal pop-close">취소하기</button>
					<button type="button" class="btn-point" onclick="set_passwd('mod_form1');">변경하기</button>
				</div>
			</div>
		</form>
	</div>
	<!--//비밀번호 변경 팝업-->

	<!--전화번호변경팝업-->
	<div class="pop-wrap" id="popupPhone">
		<form class="pop-content" name="mod_form1" id="mod_cpform" style="width:400px;">
			<input type='hidden' name='cpcheck' id='cpcheck' value=''>
			<div class="pop-header">
				<h3>휴대폰번호 변경</h3>
				<button class="pop-close">닫기</button>
			</div>
			<div class="pop-body">
				<table class="list">
					<tr>
						<th>휴대폰번호</th>
						<td>
							<input type="text" name="cp" id="cp" size="">
							<button type="button" class="btn-point" onclick="set_sms()">인증번호 발송</button>
						</td>
					</tr>
					<tr>
						<th>인증번호</th>
						<td><input type="text" name="returnnum" id='returnnum'></td>
					</tr>
				</table>
				<div style="text-align:center;margin-top:20px;">
					<button type="button" class="btn-normal pop-close">취소하기</button>
					<button type="button" class="btn-point" onclick="set_cp('mod_cpform');">변경하기</button>
				</div>
			</div>
		</form>
	</div>
	<!--//전화번호변경팝업-->

<?php $this->print_("footer",$TPL_SCP,1);?>


	<script type="text/javascript">
		function set_passwd(f) {
			if ($("#nowpasswd").val() == '') {
				alert('현재 사용중인 비밀번호를 입력하세요');
				$("#nowpasswd").focus();
				return;
			}
			if ($("#passwd").val() == '') {
				alert('변경하실 비밀번호를 입력하세요');
				$("#passwd").focus();
				return;
			}
			if ($("#passwd").val() != $("#repasswd").val()) {
				alert('비밀번호가 맞지 않습니다');
				return;
			}

			if (confirm('비밀번호를 변경하시겠습니까?')) {
				var param = $("#" + f).serialize();

				$.getJSON('/mo/proajax.php?modtype=mem_mod&han=passwd&' + param, function (result) {
					if (result.res == 'ok') {
						alert('비밀번호가 변경되었습니다');
						$('#popupPw').trigger('close');
					}
					else {
						alert(result.resmsg);
					}
				});
			}
		}

		function set_sms() {
			if ($("#cp").val() == '') {
				alert('휴대폰번호를 입력하세요');
				return;
			}

			var param = "cp=" + $("#cp").val() + "&memg=" + $("#memgrade").val();

			$.getJSON('/mo/proajax.php?modtype=mem_common&han=cpcheck_mod&' + param, function (result) {
				if (result.res == 'ok') {
					alert('인증번호가 발송되었습니다');
					$("#cpcheck").val(result.data);
				}
				else {
					alert('다른회원이 사용중인 휴대폰번호 입니다.');
				}
			});
		}

		function set_cp(f) {
			if ($("#cp").val() == '') {
				alert('휴대폰번호를입력하세요');
				$("#cp").focus();
				return;
			}
			if ($("#returnnum").val() == '') {
				alert('인증번호를 입력하세요');
				$("#returnnum").focus();
				return;
			}
			if (confirm('휴대폰번호를 변경하시겠습니까?')) {
				var param = $("#" + f).serialize();
				$.getJSON('/mo/proajax.php?modtype=mem_mod&han=cp&' + param, function (result) {
					if (result.res == 'ok') {
						alert('변경되었습니다');
						$('#popupPhone').trigger('close');

					}
					else {
						alert(result.resmsg);
					}
				});
			}
		}
	</script>
</body>
</html>