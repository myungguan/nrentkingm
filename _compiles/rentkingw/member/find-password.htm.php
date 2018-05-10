<?php /* Template_ 2.2.8 2017/12/28 16:17:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/member/find-password.htm 000002574 */ ?>
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
					<h2>비밀번호찾기</h2>
					<strong>회원님의 비밀번호를 찾아드립니다.</strong>
					<p>아이디/이름/휴대폰번호로 찾으실 수 있습니다.</p>
				</div><!--//txt-->
				<span>
					<img src="/imgs/rentking.w/loginTopicon.jpg" alt="로그인Icon">
				</span>
			</div><!--//conBanner-->
<?php if($TPL_VAR["find"]["result"]=='Y'){?>
			<table class="list">
				<colgroup>
					<col width="120px">
					<col>
				</colgroup>
				<tbody>
<?php if($TPL_VAR["find"]["resultcount"]> 0){?>
				<tr>
					<th>임시 비밀번호</th>
					<td><?php echo $TPL_VAR["findrs"]["passwd"]?></td>
				</tr>
<?php }else{?>
				<tr>
					<th>임시 비밀번호</th>
					<td>입력하신 정보와 일치하는 계정이 없습니다.</td>
				</tr>
<?php }?>
				</tbody>
			</table>
			<div class="button-box">
				<a href="/member/login.php" class="btn-point big">로그인</a>
				<a href="/member/find-password.php" class="btn-point big">비밀번호 찾기</a>
			</div>
<?php }else{?>
			<form name="findform" id="findform" action="/member/find-password.php" method="post" onsubmit="return findid('findform');">
				<input type="hidden" name="mode" value="w">
				<input type="hidden" name="find_chk" value="2" >
				<table class="list">
					<colgroup>
						<col width="120px">
						<col>
					</colgroup>
					<tr>
						<th>아이디</th>
						<td><input id="id" name="id" class="lostInput" value="" type="text" msg="아이디" valch="yes"/></td>
					</tr>
					<tr>
						<th>이름</th>
						<td><input id="name" name="name" class="lostInput" value="" type="text" msg="이름" valch="yes"></td>
					</tr>
					<tr>
						<th>휴대폰번호</th>
						<td><input id="cp" name="cp" maxlength="11" type="text" msg="휴대폰번호" valch="yes" placeholder="'-' 구분없이 입력해 주세요" /></td>
					</tr>
				</table>
				<div class="button-box">
					<button type="submit" class="btn-point big">확인</button>
				</div>
			</form>
<?php }?>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>