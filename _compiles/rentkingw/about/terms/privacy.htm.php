<?php /* Template_ 2.2.8 2017/12/28 16:47:47 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/about/terms/privacy.htm 000000888 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
		<div class="conBanner">
			<div class="txt">
				<h2>개인정보취급방침</h2>
				<strong>We go together!</strong>
				<p>렌트킹은 회원님들의 개인정보를 철저하게 보호하고 있습니다!</p>
			</div><!--//txt-->
			<span>
						<img src="/imgs/rentking.w/clauseTopIcon.jpg" alt="Icon">
					</span>
		</div><!--//conBanner-->
		<div class="personal">
			<? include "{$_SERVER['DOCUMENT_ROOT']}/old/sites/partials/terms/privacy.html";?>
		</div><!--//agree-->
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>