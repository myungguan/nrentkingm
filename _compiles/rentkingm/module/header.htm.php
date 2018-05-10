<?php /* Template_ 2.2.8 2018/04/04 16:11:28 /home/rentking/dev/rentking/old/sites/tpls/rentkingm/module/header.htm 000003565 */ ?>
<div class="rentkingImagePreLoader">
	<img src="/imgs/rentking.m/loading.gif?20170502034352" />
	<img src="/imgs/rentking.w/loading_black.gif?20170801025958" />
	<img src="/imgs/dvImg.gif?20170731022102" />
</div>
<header style="background:url(<?php echo $TPL_VAR["global"]["imgserver"]?><?php echo $TPL_VAR["global"]["config"]['image.main.m']?>) center 0;background-size:100% auto;">
	<div class="header">
<?php if($TPL_VAR["page"]["type"]=='1'){?>
		<button class="btn-back" onclick="history.back();">뒤로가기</button>
<?php }?>
<?php if($TPL_VAR["page"]["type"]=='3'){?>
		<button class="btn-back" onclick="window.close();">닫기</button>
<?php }?>
<?php if($TPL_VAR["page"]["type"]=='2'){?>
		<a href="#slideMenuOpen" class="slideMenuBtn open">메뉴보기</a>
<?php }?>
		<h1 class="<?php if($TPL_VAR["global"]["isMain"]){?>logo<?php }?>"><?php echo $TPL_VAR["page"]["title"]?><?php if($TPL_VAR["global"]["isMain"]){?><span class="logo-ci"></span><span class="logo-text1"></span><span class="logo-text2"></span><?php }?></h1>
	</div>
<?php if($TPL_VAR["global"]["isMain"]==false){?>
	<div class="searchbar-wrap">
		<div class="info">
			<div class="duration"><span class="sdate"></span> ~ <span class="edate"></span></div>
			<div class="length"></div>
			<button class="open"></button>
		</div>
<?php $this->print_("searchbar",$TPL_SCP,1);?>

	</div>
<?php }?>
</header>

<div id="sidemenuDimm"></div>
<div id="sidemenu">
	<div class="info">
		<h1><a href="/">렌트킹</a></h1>
		<ul>
<?php if($TPL_VAR["global"]["memislogin"]=='Y'){?>
			<li><a href="/member/logout.php">로그아웃</a></li><li><a href="/member/mypage.php">마이페이지</a></li>
<?php }else{?>
			<li><a href="/member/login.php?url=<?php echo $TPL_VAR["global"]["nowurl"]?>">로그인</a></li><li><a href="/member/join.php">회원가입</a></li>
<?php }?>
		</ul>
	</div>

	<ul class="menu">
		<!--
		<li><a href="/rent/search.php" class="goReservation">실시간예약</a>
			<ul>
				<li><a href="#" class="goReservation">단기대여</a></li>
				<li><a href="#" class="goLongtimeReservation">월장기대여</a></li>
			</ul>
		</li>
		-->
		<li><a href="/member/mypage.php">마이페이지</a>
			<ul>
				<li><a href="/member/mypage.php">내정보</a></li>
				<li><a href="/member/payment.php">결제내역</a></li>
				<li><a href="/member/coupon.php">쿠폰북</a></li>
			</ul>
		</li>
		<li><a href="/bbs/list.php?boardid=event">이벤트</a></li>
		<li><a href="/bbs/list.php?boardid=notice">고객센터</a>
			<ul>
				<!--<li><a href="/bbs/list.php?boardid=rentking-news">렌트킹뉴스</a></li>-->
				<li><a href="/bbs/list.php?boardid=notice">공지사항</a></li>
				<li><a href="/customer-center/faq.php">자주묻는질문</a></li>
				<li><a href="/customer-center/qnalist.php">Q&amp;A</a></li>
			</ul>
		</li>
	</ul>

	<ul class="contact">
		<li class="call">
			<a href="tel:1661-3313">
				<div class="title">고객센터 1661-3313</div>
				<div class="des">평일 09:00~18:30</div>
			</a>
		</li>
		<li class="kakao">
			<!--<a href="kakaoplus://plusfriend/home/@rentking">rentking</a>-->
			<div class="title">카카오톡 1:1 상담</div>
			<div class="des">@렌트킹</div>
		</li>
	</ul>

	<div class="copyright">
		&copy; 2018 Rentking Co.</br>
		All Rights Reserved
	</div>
	<div><a href="http://www.kbchachacha.com" target="_blank"><img src="public/files/kbchachacha.jpg" style="width:100%;"/></a></div>
</div>