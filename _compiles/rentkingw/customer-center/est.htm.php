<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/customer-center/est.htm 000004547 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
<?php $this->print_("side_customer_center",$TPL_SCP,1);?>

		<div id="content">
			<div class="conBanner">
				<div class="txt">
					<h2>견적문의</h2>
					<strong>렌트킹은 다양한 서비스가 가능합니다.</strong>
					<p>특별한 목적 외의 일반차량 문의는 렌트킹에서 차량 검색을 해보시면 더욱 빠르게 검색, 예약이 가능합니다.</p>
				</div><!--//txt-->
				<span><img src="/imgs/rentking.w/faqTopicon.jpg" alt="Icon"></span>
			</div>
			<form name="regiform" id="regiform" action="<?=$PHP_SELF;?>?code=<?=$code;?>" method="post" onsubmit="return foch("regiform");">
				<input type="hidden" name="mode" value="w">
				<table class="list">
					<colgroup>
						<col width="120px">
						<col>
						<col width="120px">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th>견적메뉴</th>
						<td>
							<select class="ctmMu" name="kinds" valch="yes" msg="견적문의종류">
								<option value="">견적종류선택</option>
								<option value="VIP의전">VIP의전</option>
								<option value="연장기">연장기</option>
								<option value="행사차량">행사차량</option>
							</select>
						</td>
						<th>방문선택</th>
						<td>
							<select class="ctmMu" name="isdeli" valch="yes" msg="방문여부선택">
								<option value="">방문여부선택</option>
								<option value="지점방문">지점방문</option>
								<option value="배달대여">배달대여</option>
							</select>
						</td>
					</tr>

					<tr>
						<th rowspan="2">서비스 일시</th>
						<td rowspan="2">
							<input type="text" name="date1" id="date1" class="dateTimePicker" placeholder="대여시작일" value="<?php echo $TPL_VAR["global"]["sdate"]?>" readonly valch="yes" msg="대여시작" style="margin-bottom:4px;"
								data-end="#date2"
								data-min-date="<?php echo $TPL_VAR["global"]["sdate"]?>"
								data-max-date-origin="today"
								data-max-date="40d"><br />
							<input type="text" name="date2" id="date2" class="dateTimePicker" placeholder="대여종료일" value="<?php echo $TPL_VAR["global"]["edate"]?>" readonly valch="yes" msg="대여종료"
								data-template="1w,1M,2M,3M,6M,1y"
								data-start="#date1"
								data-min-date-origin="#date1"
								data-min-date="1d"
								data-max-date-origin="#date1"
								data-max-date="1y">
						</td>
						<th>픽업위치</th>
						<td><input type="text" size="25" name="paddr"></td>
					</tr>


					<tr>
						<th>반납위치</th>
						<td><input type="text" size="25" name="raddr"></td>
					</tr>
					<tr>
						<th>차량모델</th>
						<td><input type="text" name="models" valch="yes" msg="희망차량모델"></td>
						<th rowspan="5">기타요청사항</th>
						<td rowspan="5">
							<textarea name="memo" style="width:100%;" rows="8"></textarea>
						</td>
					</tr>
					<tr>
						<th>고객명</th>
						<td><input type="text" valch="yes" msg="고객명" name="name"></td>

					</tr>
					<tr>
						<th>휴대폰번호</th>
						<td><input type="text" valch="yes" msg="휴대폰번호" name="cp"></td>

					</tr>
					<tr>
						<th>이메일</th>
						<td><input type="text" msg="이메일" name="email"></td>
					</tr>
					<tr>
						<th>전화번호</th>
						<td><input type="text" msg="전화번호" name="phone"></td>
					</tr>
					</tbody>
				</table>
				<div class="clause2" style="margin-bottom:5px;margin-top:20px;">
					<? include "{$_SERVER["DOCUMENT_ROOT"]}/old/sites/partials/terms/use.html";?>
				</div><!--//clause-->
				<input type="checkbox" id="개인" valch="yes" msg="이용약관"> <label for="개인">렌트킹 이용약관 동의</label>

				<div class="button-box">
					<button type="submit" class="btn-point big">견적문의</button>
				</div>
			</form>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

	<script type="text/javascript">
		$(function() {
			$(document)
				.on('submit', '#regiform', function(e) {
					return check_form('regiform') && confirm("견적문의 하시겠습니까?");
				})
		});
	</script>
</body>
</html>