<?php /* Template_ 2.2.8 2017/11/27 09:41:14 D:\Documents\Desktop\www\old\sites\tpls\rentkingw\customer-center\qnaw.htm 000002718 */ ?>
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
					<h2>Q&amp;A</h2>
					<strong>무엇이든 물어보세요!</strong>
					<p>자주 묻는 질문에 없는 궁금증은 Q&amp;A를 통해 물어봐주세요!</p>
				</div><!--//txt-->
				<span>
                            <img alt="Icon" src="/imgs/rentking.w/clauseTopIcon.jpg">
                        </span>
			</div><!--//conBanner-->
			<form name='writeform' id='writeform' action='/customer-center/qnaw.php' method='post' ENCTYPE='multipart/form-data'>
				<input type='hidden' name='mode' value='w'>
				<table class="list">
					<colgroup>
						<col width="120px">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th>제목</th>
						<td><input type="text" size="45px;" valch="yes" msg="제목" name="subject"></td>
					</tr>
					<tr>
						<th>내용</th>
						<td>
							<textarea name='memo' valch="yes" msg="내용" style="width:100%" rows="10"></textarea>
						</td>
					</tr>
					</tbody>
				</table>

				<div class="button-box">
					<button type="button" class="btn-point big" onclick="qnawritech();">문의하기</button>
				</div>
			</form>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

	<script type="text/javascript">
		function qnawritech() {
			var isall = true;
			var form = $("#writeform");
			form.find('input[type=text],select,input[type=password],textarea').each(function (key) {
				var obj = $(this);
				if (obj.attr('valch') == 'yes') {
					if (obj.attr('tagName') == 'INPUT') {
						if (obj.val() == '') {
							alert(obj.attr('msg') + ' 입력하세요');
							obj.focus();
							isall = false;
							return false;
						}

					}
					if (obj.attr('tagName') == 'TEXTAREA') {
						if (obj.val() == '') {
							alert(obj.attr('msg') + ' 입력하세요');
							obj.focus();
							isall = false;
							return false;
						}

					}
					if (obj.attr('tagName') == 'SELECT') {
						if (obj.find(':selected').val() == '') {
							alert(obj.attr('msg') + ' 선택하세요');
							obj.focus();
							isall = false;
							return false;
						}
					}
				}
			});

			if (isall) {
				if (confirm('작성하시겠습니까?')) {
					form.submit();
				}
			}

		}
	</script>
</body>
</html>