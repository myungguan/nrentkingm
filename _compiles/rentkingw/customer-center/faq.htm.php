<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/customer-center/faq.htm 000002888 */ 
$TPL_faq_1=empty($TPL_VAR["faq"])||!is_array($TPL_VAR["faq"])?0:count($TPL_VAR["faq"]);?>
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
					<h2>자주 묻는 질문</h2>
					<strong>무엇이든 물어보세요!</strong>
					<p>궁금하신 사항은 아래 검색을 통해! 없는 궁금증은 Q&amp;A를 통해 물어봐주세요!</p>
				</div><!--//txt-->
				<span><img src="/imgs/rentking.w/faqTopicon.jpg" alt="Icon"></span>
			</div><!--//conBanner-->
			<div class="faqSch">
				<div class="searchBox">
					<div class="input-wrap">
						<input type="text" value="<?php echo $TPL_VAR["keyword"]?>">
						<button class="btn-search">검색하기</button>
					</div>
					<a href="/customer-center/qnalist.php" class="btn-point">문의하기</a>
				</div> <!--//searchBox-->
				<div class="schFaqs">
					<div id="list">
<?php if($TPL_faq_1){foreach($TPL_VAR["faq"] as $TPL_V1){?>
						<a class="question" data-idx="<?php echo $TPL_V1["idx"]?>"><?php echo $TPL_V1["title"]?></a>
						<div class="answer" style="display: none;">
							<span><?php echo $TPL_V1["content"]?></span>
						</div> <!--//answer6-->
<?php }}?>

					</div> <!--//list-->
				</div><!--//schFaqs-->
			</div><!--//faqSch-->
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

	<script type="text/javascript">
		$(function () {
			var $search = $('.searchBox input');

			function search() {
				if ($search.val() == '') {
					alert('검색어를 입력해주세요');
					return;
				}
				location.href = '/customer-center/faq.php?keyword=' + encodeURI($search.val());
			}

			$(document)
				.on('click', '#list a.question', function(e) {
					e.preventDefault();
					var $question = $(this);
					$question.toggleClass('active');
					if($question.hasClass('active')) {
						plusReadCount($(this).data('idx'));
						$question.next('.answer').slideDown('fast').siblings('.answer').slideUp('fast');
						$question.siblings(".question").removeClass("active");
					} else {
						$question.next('.answer').slideUp('fast');
					}

				})
				.on('click', '.searchBox .btn-search', function(e) {
					search();
				})
				.on('keyup', '.searchBox input', function(e) {
					if(e.keyCode == 13) {
						search();
					}
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