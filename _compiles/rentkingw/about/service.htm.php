<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/about/service.htm 000002080 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
<?php $this->print_("side_about",$TPL_SCP,1);?>

		<div id="content">
			<div class="conBanner">
				<div class="txt">
					<h2>서비스안내</h2>
					<strong>렌트킹?! 따라와~</strong>
					<p>당신도 이제 렌트킹 고수!</p>
				</div><!--//txt-->
				<span>
					<img src="/imgs/rentking.w/clauseTopIcon.jpg" alt="Icon">
				</span>
			</div><!--//conBanner-->

			<h3>서비스 컨셉</h3>
			<div class="guide-service-concept">
				<ul>
					<li class="service1">
						<div class="title">실시간 가격비교</div>
						<div class="description">전국의 엄선된 렌트카 회사<br/>차량들을 한 번에 가격비교</div>
					</li>
					<li class="service2">
						<div class="title">한방에 예약&amp;결제</div>
						<div class="description">최저가 검색 후 바로 예약하고<br/>신용카드로 간편결제</div>
					</li>
					<li class="service3">
						<div class="title">편리한 배달대여</div>
						<div class="description">사무실, 집, 지하철역 등<br/>내가 원하는 장소에서 픽업</div>
					</li>
					<li class="service4">
						<div class="title">맞춤형 월간대여</div>
						<div class="description">내 스타일에 맞는 차량을<br />실시간으로 월대여</div>
					</li>
				</ul>
			</div>

			<? include "{$_SERVER['DOCUMENT_ROOT']}/old/sites/partials/help/searchbar.w.html";?>

			<? include "{$_SERVER['DOCUMENT_ROOT']}/old/sites/partials/help/select_location.w.html";?>

			<? include "{$_SERVER['DOCUMENT_ROOT']}/old/sites/partials/help/search.w.html";?>

			<? include "{$_SERVER['DOCUMENT_ROOT']}/old/sites/partials/help/payment.w.html";?>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>