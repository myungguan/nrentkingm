<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/about/alliance.htm 000002656 */ 
$TPL_locations_1=empty($TPL_VAR["locations"])||!is_array($TPL_VAR["locations"])?0:count($TPL_VAR["locations"]);?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
<?php $this->print_("side_about",$TPL_SCP,1);?>

		<div id="content">
			<div class="conBanner">
				<div class="txt">
					<h2>입점/제휴문의</h2>
					<strong>We go together!</strong>
					<p>렌트킹과 함께 성장하실 렌트카 업체 사장님들을 모십니다.</p>
				</div><!--//txt-->
				<span><img src="/imgs/rentking.w/faqTopicon.jpg" alt="Icon"></span>
			</div><!--//conBanner-->
			<form name="regiform" id="regiform" action="/about/alliance.php" method="post">
				<input type='hidden' name='mode' value='w'>
				<table class="list">
					<colgroup>
						<col width="120px">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th>법인명(단체명)</th>
						<td><input type="text" name="company" valch="yes" msg="법인명(단체명)" maxlength="50"></td>
					</tr>
					<tr>
						<th>지역</th>
						<td>
							<select name="location">
<?php if($TPL_locations_1){foreach($TPL_VAR["locations"] as $TPL_V1){?>
								<option value="<?php echo $TPL_V1["ac_name"]?>"><?php echo $TPL_V1["ac_name"]?></option>
<?php }}?>
							</select>
						</td>
					</tr>
					<tr>
						<th>담당자</th>
						<td><input type="text" name="manager" valch="yes" msg="담당자" maxlength="50"></td>
					</tr>
					<tr>
						<th>연락처</th>
						<td><input type="text" name="contact" valch="yes" msg="연락처" maxlength="50"></td>
					</tr>
					<tr>
						<th>이메일</th>
						<td><input type="text" name="email" valch="yes" msg="이메일" maxlength="50"></td>
					</tr>
					<tr>
						<th>제휴내용</th>
						<td><textarea name="content" valch="yes" msg="제휴내용" style="width:100%;" rows="10"></textarea></td>
					</tr>
					</tbody>
				</table>
				<div class="button-box">
					<button type="submit" class="btn-point big">문의하기</button>
				</div>
			</form>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>


	<script type="text/javascript">
		$(document)
			.on('submit', '#regiform', function(e) {
				var re = check_form('regiform');
				if (re) {
					return confirm('등록 하시겠습니까?');
				}
				else {
					return false;
				}
			});
	</script>
</body>
</html>