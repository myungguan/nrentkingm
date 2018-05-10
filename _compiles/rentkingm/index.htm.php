<?php /* Template_ 2.2.8 2018/04/11 17:00:43 /home/rentking/dev/rentking/old/sites/tpls/rentkingm/index.htm 000004702 */ ?>
<!DOCTYPE html>
<html lang="ko" class="main">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body class="main" style="background:url(<?php echo $TPL_VAR["global"]["imgserver"]?><?php echo $TPL_VAR["global"]["config"]['image.main.m']?>) no-repeat center 0 fixed;background-size:100% 100%;">
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
<?php if($TPL_VAR["page"]["description"]){?>
		<div class="description">
			<ul class="title">
				<li><span class="point">최</span><span class="point">저</span><span class="point">가</span> 검색</li>
				<li><span class="point">실</span><span class="point">시</span><span class="point">간</span> 결제</li>
				<li><span class="point">내</span><span class="point">게</span><span class="point">로</span> 배달</li>
			</ul>
		</div>
<?php }?>

<?php if($TPL_VAR["page"]["eventKbcard001"]){?>
		<div class="eventKbcard001">
			<div class="description1">
				<span class="dot">렌</span><span class="dot">트</span><span class="dot">킹</span>과
				<span class="dot">KB</span><span class="dot">캐</span><span class="dot">피</span><span class="dot">탈</span>의<br />
				업무제휴 기념
			</div>
			<div class="description2">고객 모두에게<br />10,000원 할인쿠폰 !</div>
		</div>
<?php }?>

<?php if($TPL_VAR["page"]["eventSamsungcard001"]){?>
		<div class="eventSamsungcard001">
			<div class="title">삼성카드생활앱 &#x2715; 렌트킹</div>
			<div class="des">10월 이벤트 진행 중<br />지금 바로 참여하세요</div>
			<a href="/bbs/read.php?idx=23&page=1&key=&keyword=&boardid=event">자세히보기</a>
		</div>
<?php }?>

<?php if($TPL_VAR["page"]["event201711"]){?>
		<div class="event201711">
			<div class="title">11월 1달간, 렌트하시면</div>
			<div class="des">신세계 상품권 or 빼빼로를<br />선물로 보내드립니다!</div>
			<a href="/bbs/read.php?idx=9&page=1&boardid=event">자세히보기</a>
		</div>
<?php }?>

<?php if($TPL_VAR["page"]["event201712"]){?>
		<div class="event201712">
			<div class="title">전국 렌트카<br />실시간 가격비교</div>
			<div class="des">12월 연말 선물 이벤트 !</div>
			<a href="/bbs/read.php?idx=17&page=1&boardid=event">자세히보기</a>
		</div>
<?php }?>

<?php if($TPL_VAR["page"]["event201802"]){?>
		<div class="event201802">
			<div class="title">전국 렌트카<br />실시간 가격비교</div>
			<div class="des">2월 회원가입 이벤트!</div>
			<a href="/bbs/read.php?idx=23&page=1&boardid=event">자세히보기</a>
		</div>
<?php }?>

<?php if($TPL_VAR["page"]["event201803"]){?>
		<div class="event201802">
			<div class="title">전국 렌트카<br />실시간 가격비교</div>
			<div class="des">월렌트 시 3만원 상품권 증정!</div>
			<a href="/bbs/read.php?idx=24&page=1&boardid=event">자세히보기</a>
		</div>
<?php }?>

<?php if($TPL_VAR["page"]["event201804"]){?>
		<div class="event201804">
			<div class="title">전국 렌트카<br />실시간 가격비교</div>
			<div class="des">4월 신규! 월장기! 단기! 이벤트!</div>
			<a href="/bbs/read.php?idx=25&page=1&boardid=event">자세히보기</a>
		</div>
<?php }?>

<?php $this->print_("searchbar",$TPL_SCP,1);?>


		<ul class="data">
			<li>
				<span class="number numberAnimation numberAnimationAutoStart" data-delay="800" data-target="<?php echo $TPL_VAR["page"]["rentshop"]?>">0</span>
				<span class="title">전국지점</span>
			</li>
			<li>
				<span class="number numberAnimation numberAnimationAutoStart" data-delay="800" data-target="<?php echo $TPL_VAR["page"]["renttcar"]?>">0</span>
				<span class="title">보유차량</span>
			</li>
			<!--<li>
				<span class="number numberAnimation numberAnimationAutoStart" data-delay="800" data-target="<?php echo $TPL_VAR["page"]["rentcar"]?>">0</span>
				<span class="title">예약가능차량</span>
			</li>-->
			<li>
				<span class="number numberAnimation numberAnimationAutoStart" data-delay="800" data-target="<?php echo $TPL_VAR["page"]["model"]?>">0</span>
				<span class="title">보유 차종</span>
			</li>
		</ul>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

	<script type="text/javascript" src="/js/rentking.m.main.min.js?201802<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>"></script>

<?php if($TPL_VAR["global"]["production"]){?>
	<script type="text/javascript" src="//tenping.kr/scripts/cpa/tenping.cpa.V2.min.js"></script>
	<script type="text/javascript">
        ex2cts.push('track', 'pageview_mainpage');
	</script>
<?php }?>
</body>
</html>