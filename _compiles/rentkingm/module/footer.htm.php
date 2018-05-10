<?php /* Template_ 2.2.8 2018/01/16 11:27:01 /home/rentking/dev/rentking/old/sites/tpls/rentkingm/module/footer.htm 000001349 */ ?>
<?php if($TPL_VAR["global"]["nowurlh"]!='/index.php'){?>
<!--<div id="debugger"></div>-->
<button class="go-top">위로</button>
<footer style="background:url(<?php echo $TPL_VAR["global"]["imgserver"]?><?php echo $TPL_VAR["global"]["config"]['image.main.m']?>) center bottom;background-size:100% auto;">
	<ul>
		<li><a href="/" class="home">홈</a></li>
<?php if($TPL_VAR["global"]["memislogin"]=='Y'){?>
		<li><a href="/member/mypage.php" class="myinfo">내정보</a></li>
		<li><a href="/member/payment.php" class="orderlist">결제내역</a></li>
<?php }else{?>
		<li><a href="/member/login.php" class="login">로그인</a></li>
		<li><a href="/member/join.php" class="join">회원가입</a></li>
<?php }?>
		<li><a href="/customer-center/faq.php" class="faq">자주묻는질문</a></li>
	</ul>
</footer>
<?php }?>

<?
	$pageType='mobile';
	if($TPL_VAR['global']['production']) {
		include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/log/analytics.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/log/naverkeyword.php");
	}

    //channel.io 모바일에서는 첫 메인화면에서만 실행
	if($TPL_VAR['global']['isMain'])
		include_once($_SERVER['DOCUMENT_ROOT']."/old/inc/plugins/channel.io.php");
?>