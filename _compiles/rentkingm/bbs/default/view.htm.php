<?php /* Template_ 2.2.8 2017/11/09 17:48:35 D:\Documents\projects\rentking\trunk\www\old\sites\tpls\rentkingm\bbs\default\view.htm 000000708 */ ?>
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
				<div class="title"><?php echo $TPL_VAR["bbs"]["title"]?></div>
				<div class="content"><?php echo $TPL_VAR["bbs"]["content"]?></div>
				<a href="<?php echo $TPL_VAR["bbs"]["listlink"]?>" class="btn btn-primary big">목록으로</a>
			</div>
		</div>

	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>