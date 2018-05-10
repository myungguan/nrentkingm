<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/member/login.htm 000002423 */ ?>
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
					<h2>로그인</h2>
					<strong>렌트킹에 방문해주셔서 감사합니다.</strong>
					<p>렌트킹에 접속하셔서 특별한 날 특별한 차량을 대여하세요!</p>
				</div><!--//txt-->
				<span>
					<img src="/imgs/rentking.w/loginTopicon.jpg" alt="로그인Icon">
				</span>
			</div><!--//conBanner-->
			<form name="g_loginform" id="g_loginform" action="/member/loginpro.php" method="post" class="validationForm">
				<input type='hidden' name='url' value='<?php echo $_GET["url"]?>'>
				<div class="loginform">
					<input class="email validationFormRequired" type="text" placeholder="이메일 아이디입력" name="id" id="g_id" value="<?php echo $TPL_VAR["global"]["c_id"]?>" data-error="아이디를 입력하세요.">
					<input class="password validationFormRequired" type="password" placeholder="비밀번호입력" name="passwd" id="g_passwd" value="" data-error="비밀번호를 입력하세요.">
					<button type="submit" class="btn-point">로그인</button>

					<!--
					<div class="save-id">
						<label><input class="logform4" type="checkbox" value="Y" name="saveid" <?php if($TPL_VAR["global"]["c_id"]!=''){?>checked<?php }?>> 아이디 저장</label>
					</div>
					-->

					<ul class="util">
						<li><a href="/member/find-id.php">아이디 찾기</a></li>
						<li><a href="/member/find-password.php">비밀번호 찾기</a></li>
						<li><a href="/member/join.php">회원가입</a></li>
					</ul>

					<ul class="social">
						<li><a href="/social/fb_login.php" class="facebook" title="페이스북 로그인">페이스북 로그인</a></li>
						<li><a href="/social/naver_login.php" class="naver" title="네이버 로그인">네이버 로그인</a></li>
						<li><a href="/social/kakao_login.php" class="kakao" title="카카오 로그인">카카오 로그인</a></li>
					</ul>
				</div>
			</form>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>