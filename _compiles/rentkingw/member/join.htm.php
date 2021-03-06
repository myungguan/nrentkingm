<?php /* Template_ 2.2.8 2018/03/05 11:03:40 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/member/join.htm 000008201 */ ?>
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
<?php if($TPL_VAR["page"]["mtype"]=='p'){?>
			<div class="conBanner">
				<div class="txt">
					<h2>회원가입</h2>
					<strong>렌트킹에 방문해주셔서 감사합니다.</strong>
					<p>렌트킹 회원으로 가입하셔서 더욱 다양한 혜택과 이벤트를 경험하세요!</p>
				</div><!--//txt-->
				<span><img src="/imgs/rentking.w/joinTopIcon.jpg" alt="Icon"></span>
			</div><!--//conBanner-->
<?php }?>
<?php if($TPL_VAR["page"]["mtype"]=='d'){?>
			<div class="conBanner">
				<div class="txt">
					<h2>딜러회원 회원가입</h2>
					<strong>렌트킹에 방문해주셔서 감사합니다.</strong>
					<p>렌트킹 회원으로 가입하셔서 더욱 다양한 혜택과 이벤트를 경험하세요!</p>
				</div><!--//txt-->
				<span>
                            <img src="/imgs/rentking.w/joinTopIcon.jpg" alt="Icon">
                        </span>
			</div>
<?php }?>
			<form name="joinform" id="joinform" method="post" action="/mo/pro.php">
				<input type='hidden' name='modtype' value='mem_join'>
				<input type='hidden' name='mem_type' id='mem_type' value='<?php echo $TPL_VAR["page"]["mtype"]?>'>
				<input type='hidden' name='memgrade' id='memgrade' value='<?php if($TPL_VAR["page"]["mtype"]=="p"){?>100<?php }?><?php if($TPL_VAR["page"]["mtype"]=="c"){?>99<?php }?><?php if($TPL_VAR["page"]["mtype"]=="d"){?>12<?php }?>'>
				<input type='hidden' name='idcheck' id='idcheck' value='N'>
				<input type='hidden' name='cpcheck' id='cpcheck' value='XXXXXX'>
				<input type='hidden' name='cpcheckcp' id='cpcheckcp' value='XXXXXX'>
				<input type='hidden' name='channel' id='channel' value='<?php echo $TPL_VAR["page"]["channel"]?>'>
				<input type='hidden' name='ch_id' value='<?php echo $TPL_VAR["page"]["id"]?>'>

				<table class="list">
					<colgroup>
						<col width="120px">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th>아이디</th>
						<td>
							<input type="text" valch="yes" msg="아이디" class="mWt60p" name="id" id='id' value="<?php echo $TPL_VAR["page"]["email"]?>" placeholder="아이디(이메일주소)">
							<button type="button" class="checkId btn-point">중복확인 </button>
						</td>
					</tr>
					<tr>
						<th>비밀번호</th>
						<td>
							<input type="password" valch="yes" msg="비밀번호" id='passwd' class="mWt100p" name="passwd" value="" placeholder="비밀번호 입력" >
						</td>
					</tr>
					<tr>
						<th>비밀번호 확인</th>
						<td>
							<input type="password" valch="yes" msg="비밀번호 확인" id='repasswd' class="mWt100p" name="repasswd" value="" placeholder="비밀번호 재입력" >
						</td>
					</tr>
					</tbody>
				</table>
				<p style="margin-top:5px;margin-bottom:30px;">*비밀번호는 6~15자 이내로 영문(대문자, 소문자),숫자,특수문자 3가지 조합 중 2가지 이상을 조합하셔서 작성하시면 됩니다.<br>단, 3가지 모두를 조합하실 경우 더욱 강력한 패스워드 구현이 가능합니다.</p>

				<h3>회원정보</h3>
				<table class="list" style="margin-bottom:20px;">
					<colgroup>
						<col width="120px">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th>이름</th>
						<td>
							<input type="text" valch="yes" msg="이름" class="mWt100p" name="name" value="" placeholder="실명을 입력하세요" >
							(실명과 다를경우 예약에 제한이 있을 수 있습니다.)
						</td>
					</tr>
					<tr>
						<th>휴대폰번호</th>
						<td>
							<input type="text" valch="yes" msg="휴대폰" class="mWt50p left" name="cp" id='cp' value="" placeholder="&quot;-&quot; 구분없이 입력해 주세요" >
							<button type="button" class="btn-point" id="setSms">인증번호전송</button>
						</td>
					</tr>
					<tr>
						<th>인증번호</th>
						<td>
							<input type="text" valch="yes" msg="인증번호" class="mWt100p" name="returnnum" id='returnnum' value="" placeholder="인증번호 입력" >
							(인증번호는 카톡 or 문자로 전송됩니다.)
						</td>
					</tr>
					<tr>
						<th>생년월일</th>
						<td>
							<input type="text" valch="yes" msg="생년월일" class="mWt100p datePicker" data-min-view-mode="years" name="birth" id="birth" value="" readonly>
						</td>
					</tr>
					<tr>
						<th>성별</th>
						<td>
							<input type="radio" name="sex" value="F" id="woman"> <label for="woman">여성</label>
							<input type="radio" name="sex" value="M" id="man" > <label for="man">남성</label>
						</td>
					</tr>
					</tbody>
				</table>

				<div class="clause">
					<? include "{$_SERVER['DOCUMENT_ROOT']}/old/sites/partials/terms/use.html";?>
				</div>

				<div class="clause" style="margin-bottom:5px;">
					<? include "{$_SERVER['DOCUMENT_ROOT']}/old/sites/partials/terms/privacy.html";?>
				</div>

				<ul>
					<li>
						<input type="checkbox" id="chk1" valch="yes" msg="이용약관 동의"> <label for="chk1">이용약관 동의</label>
					</li>
					<li>
						<input type="checkbox" id="chk4" valch="yes" msg="개인정보 취급방침 동의"> <label for="chk4">개인정보 취급방침 동의</label>
					</li>
				</ul>
				<div class="button-box">
					<button type="submit" class="btn-point big" style="border:none;">회원가입</button>
				</div>
			</form>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

	<script type="text/javascript">
		$(function() {
			var $cp = $('#cp');
			var $cpCheck = $('#cpcheck');
			var $cpCheckCp = $('#cpcheckcp');
			var $setSms = $('#setSms');
			var $returnnum = $('#returnnum');

			$(document)
				.on('click', '#setSms', function(e) {
					e.stopPropagation();

					if ($cp.val() == '') {
						alert('휴대폰번호를 입력하세요');
						return;
					}

					if(!$setSms.data('process')) {
						$setSms.data('process', true)
							.text('전송중...');

						var param = "cp=" + $cp.val() + "&memg=" + $("#memgrade").val();

						$.getJSON('/mo/proajax.php?modtype=mem_common&han=cpcheck&' + param, function (result) {
							if (result['res'] == 'ok') {
								$cpCheck.val(result['data']);
								$cpCheckCp.val(result['cp']);
								$setSms.text('전송완료');
								$returnnum.focus();
							} else {
								alert('이미 가입된 휴대폰번호 입니다.');
								$setSms.data('process', false)
									.text('인증번호전송');
							}
						});
					}

				})
				.on('input', '#cp', function() {
					if($cp.val() != $cpCheckCp.val()) {
						$setSms.data('process', false)
							.text('인증번호전송');
					} else {
						$setSms.data('process', true)
							.text('전송완료');
						$returnnum.focus();
					}

				})
		});
	</script>

<!-- 타겟팅 게이츠 트래킹 스크립트: 마케팅팀 요청(180305) -->
<!-- WIDERPLANET SCRIPT START 2018.2.28 -->
<div id="wp_tg_cts" style="display:none;"></div>
<script type="text/java-script">
var wptg_tagscript_vars = wptg_tagscript_vars || [];
wptg_tagscript_vars.push(
(function() {
return {
wp_hcuid:"", /*고객넘버 등 Unique ID (ex. 로그인 ID, 고객넘버 등 )를 암호화하여 대입.
주의 : 로그인 하지 않은 사용자는 어떠한 값도 대입하지 않습니다./
ti:"39960",
ty:"Join", /*트래킹태그 타입 /
device:"web", /*디바이스 종류 (web 또는 mobile)/
items:[{
i:"회원 가입", /*전환 식별 코드 (한글 , 영어 , 번호 , 공백 허용 )*/
t:"회원 가입", /*전환명 (한글 , 영어 , 번호 , 공백 허용 )*/
p:"1", /*전환가격 (전환 가격이 없을 경우 1로 설정 )*/
q:"1" /*전환수량 (전환 수량이 고정적으로 1개 이하일 경우 1로 설정 )*/
}]
};
}));
</script>
	<script type="text/java-script" async src="//cdn-aitg.widerplanet.com/js/wp_astg_4.0.js"></script>
	<!-- // WIDERPLANET SCRIPT END 2018.2.28 -->
</body>
</html>