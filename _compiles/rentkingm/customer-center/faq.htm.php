<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingm/customer-center/faq.htm 000001484 */ 
$TPL_faq_1=empty($TPL_VAR["faq"])||!is_array($TPL_VAR["faq"])?0:count($TPL_VAR["faq"]);?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
		<ul class="faq">
<?php if($TPL_faq_1){foreach($TPL_VAR["faq"] as $TPL_V1){?>
			<li>
				<a href="#<?php echo $TPL_V1["idx"]?>" class="handle" data-idx="<?php echo $TPL_V1["idx"]?>"><?php echo $TPL_V1["title"]?></a>
				<div class="content">
					<?php echo $TPL_V1["content"]?>

				</div>
			</li>
<?php }}?>
		</ul>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>


	<script type="text/javascript">
		$(function() {
			var headerHeight = $('header').outerHeight();
			$(document)
				.on('click', 'a.handle', function(e) {
					e.preventDefault();
					var $handle = $(this);
					$handle.parent().toggleClass('on').siblings().removeClass('on');
					if ($handle.parent().hasClass('on')){
						plusReadCount($(this).data('idx'));
					}

					setTimeout(function() {
						$(window).scrollTop($handle.offset().top - headerHeight);
					}, 100);
				});

			function plusReadCount(idx) {
				var param = {
					mode : 'plusReadCount',
					idx : idx
				};
				$.post(location.href, param);
			}
		});
	</script>
</body>
</html>