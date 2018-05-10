<?php /* Template_ 2.2.8 2017/11/15 14:53:03 D:\Documents\projects\rentking\trunk\www\old\sites\tpls\rentkingm\customer-center\qnalist.htm 000001042 */ 
$TPL_qnalist_1=empty($TPL_VAR["qnalist"])||!is_array($TPL_VAR["qnalist"])?0:count($TPL_VAR["qnalist"]);?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
		<a class="btn btn-primary big" href="qnaw.php">질문작성</a>

		<div class="board">
			<ul class="list">
<?php if($TPL_qnalist_1){foreach($TPL_VAR["qnalist"] as $TPL_V1){?>
				<li>
					<a href="qnar.php?idx=<?php echo $TPL_V1["index"]?>">
						<span class="title"><?php if($TPL_V1["answer_yn"]=='Y'){?>[답변완료]<?php }else{?>[답변대기]<?php }?> <?php echo $TPL_V1["subject"]?></span>
						<span class="date"><?php echo $TPL_V1["dt_create"]?></span>
					</a>
				</li>
<?php }}?>
			</ul>
		</div>

	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>