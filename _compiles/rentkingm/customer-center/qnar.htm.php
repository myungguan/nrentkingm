<?php /* Template_ 2.2.8 2017/11/15 14:54:30 D:\Documents\projects\rentking\trunk\www\old\sites\tpls\rentkingm\customer-center\qnar.htm 000001041 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
		<div class="board">
			<div class="view">
				<div class="title"><?php echo $TPL_VAR["qna"]["subject"]?></div>
				<div class="qna-question"><?php echo $TPL_VAR["qna"]["memo"]?></div>
				<div class="qna-answer">
<?php if($TPL_VAR["qna"]["answer_yn"]=='Y'){?>
					<p><strong>렌트킹</strong>님이 <?php echo $TPL_VAR["qna"]["dt_answer"]?> 에 답변하였습니다.</p>
					<p><?php echo $TPL_VAR["qna"]["answer"]?></p>
<?php }else{?>
					<p>답변이 등록되지 않았습니다.</p>
<?php }?>
				</div>
				<a href="/customer-center/qnalist.php" class="btn btn-primary big">목록으로</a>
			</div>
		</div>

	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>