<?php /* Template_ 2.2.8 2018/01/15 18:19:21 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/module/footer.htm 000001650 */ ?>
<footer>
	<ul class="top">
		<li><a href="/about/company.php">회사소개</a></li>
		<li><a href="/about/recruit.php">인재채용</a></li>
		<li><a href="/about/alliance.php">입점/제휴문의</a></li>
		<li><a href="/about/terms/privacy.php">개인정보취급방침</a></li>
	</ul>
	<div class="content">
		<ul class="inner">
			<li class="logo"></li>
			<li class="info1">
				주식회사 렌트킹 ㅣ 대표이사 : 윤영진<br />
				주소: 서울특별시 서대문구 연세로 50<br />
				(신촌동, 연세대학교) 공학원
			</li>
			<li class="info2">
				사업자등록번호: 813-81-00123<br />
				통신판매업신고: 제 2016-서대문구-0597호<br />
				벤처기업인증: 제2016113295호
			</li>
			<li class="contact">
				<span class="b">고객센터: 1661-3313</span><br />
				영업시간 : 평일 오전 9시 - 오후 6시 30분<br />
				개인정보관리책임자 : 신윤호
			</li>
		</ul>
	</div>
	<div class="copyright">
		<div class="warning">렌트킹은 통신판매중개자이며 통신판매의 당사자가 아니며, 상품·거래정보 및 거래에 대하여 책임을 지지 않습니다.</div>
		Copyright ⓒ Rentking Co. All rights reserved.
	</div>
</footer>

<?
	$pageType='web';
	if($TPL_VAR['global']['production']) {
		include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/log/analytics.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/log/naverkeyword.php");
	}
	include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/plugins/channel.io.php");
?>