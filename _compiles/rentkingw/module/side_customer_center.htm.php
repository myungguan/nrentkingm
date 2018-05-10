<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/module/side_customer_center.htm 000001512 */ ?>
<div id="lnb">
	<table>
		<tbody>
		<tr>
			<td <?php if($TPL_VAR["global"]["nowurlh"]=='/bbs/list.php'||$TPL_VAR["global"]["nowurlh"]=='/bbs/read.php'){?> class="on" <?php }?>>
			<a href="/bbs/list.php?boardid=notice">
				공지사항
			</a>
			</td>
		</tr>
		<tr>
			<td <?php if($TPL_VAR["global"]["nowurlh"]=='/customer-center/faq.php'){?>class="on" <?php }?>>
			<a href="/customer-center/faq.php">
				자주 묻는 질문
			</a>
			</td>
		</tr>
		<tr>
			<td <?php if($TPL_VAR["global"]["nowurlh"]=='/customer-center/qnalist.php'||$TPL_VAR["global"]["nowurlh"]=='/customer-center/qnaw.php'||$TPL_VAR["global"]["nowurlh"]=='/customer-center/qnar.php'){?>class="on" <?php }?>>
			<a href="/customer-center/qnalist.php">
				Q&amp;A
			</a>
			</td>
		</tr>
		<tr>
			<td <?php if($TPL_VAR["global"]["nowurlh"]=='/customer-center/est.php'){?>class="on" <?php }?>>
			<a href="/customer-center/est.php">
				견적문의
			</a>
			</td>
		</tr>
		<tr>
			<td <?php if($TPL_VAR["global"]["nowurlh"]=='/customer-center/reservation.php'){?>class="on" <?php }?>>
			<a href="/customer-center/reservation.php">
				RESERVATION
			</a>
			</td>
		</tr>
		</tbody>
	</table>
	<div class="subB">
		<a href="/rent/search.php" class="goReservation"><img src="/imgs/rentking.w/subBnner.jpg"></a>
	</div><!--//subB-->
</div>