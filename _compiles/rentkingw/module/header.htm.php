<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/module/header.htm 000002489 */ ?>
<div class="rentkingImagePreLoader">
	<img src="/imgs/rentking.w/loading.gif?20170801025913" />
	<img src="/imgs/rentking.w/loading_black.gif?20170801025958" />
	<img src="/imgs/dvImg.gif?20170731022102" />
</div>
<div class="header-wrap" style="background:url(<?php echo $TPL_VAR["global"]["imgserver"]?><?php echo $TPL_VAR["global"]["config"]['image.main.w']?>) no-repeat center top;background-size:100% auto;">
	<header>
		<div class="inner">
			<a href="/" class="logo"><h1>렌트킹 <span class="logo-ci"></span><span class="logo-text1"></span><span class="logo-text2"></span></h1></a>
			<ul class="menu">
				<!--
				<li><a href="/rent/search.php" class="goReservation">실시간예약</a>
					<ul>
						<li><a href="#" class="goReservation">단기대여</a></li>
						<li><a href="#" class="goLongtimeReservation">월장기대여</a></li>
					</ul>
				</li>
				-->
				<li><a href="/about/company.php">회사소개</a>
					<ul>
						<li><a href="/about/company.php">회사소개</a></li>
						<li><a href="/about/service.php">서비스안내</a></li>
						<li><a href="/about/alliance.php">입점/제휴문의</a></li>
						<li><a href="/about/recruit.php">인재채용</a></li>
					</ul>
				</li>
				<li><a href="/bbs/list.php?boardid=event">이벤트</a>
				</li>
				<li><a href="/bbs/list.php?boardid=notice">고객센터</a>
					<ul>
						<li><a href="/bbs/list.php?boardid=notice">공지사항</a></li>
						<li><a href="/customer-center/faq.php">자주묻는질문</a></li>
						<li><a href="/customer-center/qnalist.php">Q&amp;A</a></li>
						<li><a href="/customer-center/est.php">견적문의</a></li>
						<li><a href="/customer-center/reservation.php">RESERVATION</a></li>
					</ul>
				</li>
			</ul>
			<ul class="side">
<?php if($TPL_VAR["global"]["memislogin"]=='Y'){?>
				<li><a href="/member/logout.php">로그아웃</a></li>
				<li><a href="/member/mypage.php">마이페이지</a></li>
<?php }else{?>
				<li><a href="/member/login.php?url=<?php echo $TPL_VAR["global"]["nowurl"]?>">로그인</a></li>
				<li><a href="/member/join.php">회원가입</a></li>
<?php }?>
			</ul>
		</div>
	</header>
<?php if($TPL_VAR["global"]["isMain"]==false){?>
	<div class="searchbar-wrap">
<?php $this->print_("searchbar",$TPL_SCP,1);?>

	</div>
<?php }?>
</div>