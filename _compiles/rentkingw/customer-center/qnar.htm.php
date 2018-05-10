<?php /* Template_ 2.2.8 2017/12/07 10:50:42 D:\Documents\Desktop\www\old\sites\tpls\rentkingw\customer-center\qnar.htm 000001752 */ ?>
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
			<table class="list">
				<colgroup>
					<col width="*">
					<col width="120px">
				</colgroup>
				<thead>
				<tr>
					<th><?php echo $TPL_VAR["qna"]["subject"]?></th>
					<th><?php echo substr($TPL_VAR["qna"]["dt_create"], 0, 10)?></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td colspan="2"><?php echo $TPL_VAR["qna"]["memo"]?></td>
				</tr>
				<tr>
					<td colspan="2" style="padding-top:40px;">
<?php if($TPL_VAR["qna"]["dt_answer"]){?>
						<p><strong>렌트킹님이 <?php echo $TPL_VAR["qna"]["dt_answer"]?> 에 답변하였습니다.</strong></p>
						<p><?php echo $TPL_VAR["qna"]["answer"]?></p>

<?php }else{?>
						<p>답변이 등록되지 않았습니다.</p>
<?php }?>
					</td>
				</tr>
				</tbody>
			</table><!--//boxTop-->

			<div class="btnArea">
				<a href="qnalist.php" class="btn-point">목록</a>
			</div><!--//btnWrap-->
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>