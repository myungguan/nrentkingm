<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingm/member/login.htm 000002076 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap" class="login">
		<form name="g_loginform" id="g_loginform" action="/member/loginpro.php" method="post" class="validationForm">
			<input type='hidden' name='url' value='<?php echo $_GET["url"]?>'>
			<div class="topInput">
				<input type="email" class="id validationFormRequired" name="id" id="g_id" value="" placeholder="이메일" data-error="아이디를 입력하세요.">
				<input type="password" class="pw validationFormRequired" name="passwd" id="g_passwd" value="" placeholder="비밀번호" data-error="비밀번호를 입력하세요.">

				<div class="bottom">
					<div class="left">
						<!--
						<input type="checkbox" class="mCheckbox" name="loginSave" id="loginSave" value="Y"> <label for="loginSave">로그인 기억</label>
						-->
					</div>
					<div class="right">
						<a href="/member/find-id.php">아이디찾기</a>&nbsp;|&nbsp;
						<a href="/member/find-password.php">비밀번호찾기</a>
					</div>
				</div>
			</div>
			<div class="button-area">
				<button type="submit" class="btn btn-primary big">로그인</button>
				<a href="/member/join.php" class="btn btn-secondary big">회원가입</a>
			</div>
		</form>

		<div class="social">
			<div class="title">
				<div class="inner">SNS 계정으로 로그인</div>
			</div>

			<ul>
				<li><a href="/social/kakao_login.php" class="kakao">카카오톡 로그인</a></li>
				<li><a href="/social/naver_login.php" class="naver">네이버 로그인</a></li>
				<li><a href="/social/fb_login.php" class="facebook">페이스북 로그인</a></li>
				<!--<li><a href="/social/twitter_login.php" class="twitter">트위터 로그인</a></li>-->
			</ul>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>