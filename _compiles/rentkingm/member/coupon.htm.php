<?php /* Template_ 2.2.8 2017/11/23 18:30:01 D:\Documents\projects\rentking\trunk\www\old\sites\tpls\rentkingm\member\coupon.htm 000001302 */ 
$TPL_clist_1=empty($TPL_VAR["clist"])||!is_array($TPL_VAR["clist"])?0:count($TPL_VAR["clist"]);?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
		<div class="coupon-book">
			<form method="post">
				<input type="hidden" name="mode" value="r" />
				<div class="input-wrap">
					<input type="text" class="" name="nums" id="coupon" value="" placeholder="코드를 입력해주세요.">
				</div>
				<button type="submit" class="btn btn-primary register">등록</button>
			</form>

			<ul class="list">
<?php if($TPL_clist_1){foreach($TPL_VAR["clist"] as $TPL_V1){?>
				<li>
					<div class="title"><?php echo $TPL_V1["name"]?></div>
					<img src="<?php echo $TPL_VAR["global"]["imgserver"]?><?php echo $TPL_V1["path"]?>" alt="<?php echo $TPL_V1["name"]?>">
					<div class="description">
						<p>사용기간: <?php echo $TPL_V1["sdate"]?> ~ <?php echo $TPL_V1["edate"]?></p>
					</div>
				</li>
<?php }}?>
			</ul>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>