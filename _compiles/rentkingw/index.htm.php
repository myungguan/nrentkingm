<?php /* Template_ 2.2.8 2018/04/11 17:00:59 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/index.htm 000008516 */ ?>
<!DOCTYPE html>
<html lang="ko" class="main">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<?php echo $TPL_VAR["test"]?>

	<div class="visual-area">
		<div class="inner">
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
				<div class="title">신규·기존고객 모두에게 할인쿠폰 지급</div>

				<div class="description1">
					<span class="dot">렌</span><span class="dot">트</span><span class="dot">킹</span>과
					<span class="dot">KB</span><span class="dot">캐</span><span class="dot">피</span><span class="dot">탈</span>의
					업무제휴 기념
				</div>
				<div class="description2">10,000원 할인쿠폰 지급</div>
			</div>
<?php }?>

<?php if($TPL_VAR["page"]["eventSamsungcard001"]){?>
			<div class="eventSamsungcard001">
				<div class="title">삼성카드생활앱 &#x2715; 렌트킹</div>
				<div class="des">10월 이벤트 진행 중</div>
				<a href="/bbs/read.php?idx=23&page=1&key=&keyword=&boardid=event">자세히보기</a>
			</div>
<?php }?>

<?php if($TPL_VAR["page"]["event201711"]){?>
			<div class="event201711">
				<div class="title">렌트킹 11월 이벤트 진행 중</div>
				<div class="des1">차량을 렌트하시는 고객님들께</div>
				<div class="des2">신세계 상품권 or 빼빼로를<br><span class="small">선물로 보내드립니다!</span></div>
				<a href="/bbs/read.php?idx=9&page=1&boardid=event">자세히보기</a>
			</div>
<?php }?>

<?php if($TPL_VAR["page"]["event201712"]){?>
			<div class="event201712">
				<div class="title">12월 연말 선물 이벤트</div>
				<div class="des1">전국 렌트카 실시간 가격비교</div>
				<div class="des2">
					한 달간, 차량을 렌트하시는 분들 중<br>
					<span class="large">매주 추첨을 통하여<br />아래 선물을 보내드립니다 !</span>
				</div>
				<span class="icon1"></span>
				<span class="icon2"></span>
				<span class="icon3"></span>
			</div>
<?php }?>

<?php if($TPL_VAR["page"]["event201801"]){?>
			<div class="event201712">
				<div class="title">1월 렌트킹 회원가입 이벤트</div>
				<div class="des1">전국 렌트카 실시간 가격비교</div>
				<div class="des2" style="margin-top:-65px;">
					<img src="../imgs/rentking.w/main/event201801_icon.png" style="width:300px;height:300px;transform-origin: 150% 100%;transform: scale(1);"/>
					<p style="margin-top:-50px;">지금 회원가입 시<br>
					<span class="large">10,000원 할인쿠폰을 드립니다!</span></p>
				</div>
			</div>
<?php }?>

<?php if($TPL_VAR["page"]["event201802"]){?>
			<!--<div class="event201802">
				<div class="title">2월 렌트킹 회원가입 이벤트</div>
				<div class="des1">전국 렌트카 실시간 가격비교</div>
				<div class="des2">
					<img src="../imgs/rentking.w/main/event201801_icon.png"/>
					<p>지금 회원가입 시<br>
						<span class="large">10,000원 할인쿠폰을 드립니다!</span></p>
				</div>
			</div>-->
			<div class="event201802">
				<div class="event-content">
					<p>회원가입 시 10,000원 쿠폰 증정!</p>

					<a href="/bbs/read.php?idx=23&page=1&boardid=event"><button class="event-btn">2월 이벤트 자세히보기</button></a>
				</div>
			</div>
<?php }?>
<?php if($TPL_VAR["page"]["event201803"]){?>
			<!--<div class="event201802">
				<div class="title">2월 렌트킹 회원가입 이벤트</div>
				<div class="des1">전국 렌트카 실시간 가격비교</div>
				<div class="des2">
					<img src="../imgs/rentking.w/main/event201801_icon.png"/>
					<p>지금 회원가입 시<br>
						<span class="large">10,000원 할인쿠폰을 드립니다!</span></p>
				</div>
			</div>-->
			<div class="event201802">
				<div class="event-content">
					<p>월렌트 시 30,000원 상품권 증정!</p>
					<a href="/bbs/read.php?idx=24&page=1&boardid=event"><button class="event-btn">3월 이벤트 자세히보기</button></a>
				</div>
			</div>
<?php }?>
<?php if($TPL_VAR["page"]["event201804"]){?>
			<div class="event201804">
				<div class="event-content">
					<p>4월 신규! 월장기! 단기! 3종 이벤트!</p>
					<a href="/bbs/read.php?idx=25&page=1&boardid=event"><button class="event-btn">4월 이벤트 자세히보기</button></a>
				</div>
			</div>
<?php }?>
<?php if($TPL_VAR["page"]["applink"]){?>
			<div class="appLink">
				<a href="https://play.google.com/store/apps/details?id=kr.co.rentking" target="_blank">
					<img class="google-play" src="../imgs/rentking.w/main/playstore.png"/>
				</a>
				<a href="https://itunes.apple.com/kr/app/%EB%A0%8C%ED%8A%B8%ED%82%B9/id1131927380?mt=8" target="_blank">
					<img src="../imgs/rentking.w/main/appstore.png"/>
				</a>
			</div>
<?php }?>
			<ul class="data">
				<h2 class="data-title">전국 렌트카 실시간 가격비교</h2>
				<hr style="margin-top:10px;">
				<li>
					<span class="number numberAnimation" data-target="<?php echo $TPL_VAR["page"]["rentshop"]?>">0</span>
					<span class="title">전국지점</span>
				</li>
				<li>
					<span class="number numberAnimation" data-target="<?php echo $TPL_VAR["page"]["renttcar"]?>">0</span>
					<span class="title">보유차량</span>
				</li>
				<!--<li>
					<span class="number numberAnimation" data-target="<?php echo $TPL_VAR["page"]["rentcar"]?>">0</span>
					<span class="title">예약가능차량</span>
				</li>-->
				<li>
					<span class="number numberAnimation" data-target="<?php echo $TPL_VAR["page"]["model"]?>">0</span>
					<span class="title">보유 차종</span>
				</li>
			</ul>
<?php $this->print_("searchbar",$TPL_SCP,1);?>

		</div>
		<img class="visual-image" data-src="<?php echo $TPL_VAR["global"]["imgserver"]?><?php echo $TPL_VAR["global"]["config"]['image.main.w']?>" src="" />
	</div>
	<div class="service-area">
		<div class="inner">
			<h2>서비스 안내</h2>
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
	</div>
	<div class="event-area" style="display:none;">
		<div class="event-join" data-background="">
			<div class="inner">
				<div class="title"><span class="new"></span>회원가입 이벤트</div>
				<ul class="icon">
					<li class="icon1">
						<div>① 렌트킹 회원가입</div>
					</li>
					<li class="icon2">
						<div>② 5,000원 할인쿠폰 증정</div>
					</li>
					<li class="icon3">
						<div>③ 쿠폰으로 더욱 저렴하게 대여</div>
					</li>
				</ul>
				<div class="description">
					* 전지점, 전차종 가격제한 없이 지금 바로 사용가능<br />
					<span class="big">지금 회원가입 하시면 5,000원 쿠폰을 드립니다.</span>
				</div>
			</div>
		</div>
	</div>


	<div class="rentkingLoading rentkingLoadingShow rentkingLoadingReveal black"></div>
<?php $this->print_("footer",$TPL_SCP,1);?>

	<script type="text/javascript" src="/js/rentking.w.main.min.js?20180323<?php if(!$TPL_VAR["global"]["production"]){?>_<?php echo time()?><?php }?>"></script>

<?php if($TPL_VAR["global"]["production"]){?>
	<script type="text/javascript" src="//tenping.kr/scripts/cpa/tenping.cpa.V2.min.js"></script>
<?php }?>
</body>
</html>