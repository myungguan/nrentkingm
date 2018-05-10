<?php /* Template_ 2.2.8 2017/12/28 16:47:16 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/module/side_member.htm 000001883 */ ?>
<div id="lnb">
    <table>
        <tbody>
<?php if($TPL_VAR["global"]["memislogin"]=='Y'){?>
        <tr>
            <td <?php if($TPL_VAR["global"]["nowurlh"]=='/member/mypage.php'){?>class="on" <?php }?>>
				<a href="/member/mypage.php">
					내정보
				</a>
            </td>
        </tr>
        <tr>
            <td <?php if($TPL_VAR["global"]["nowurlh"]=='/member/payment.php'){?>class="on" <?php }?>>
				<a href="/member/payment.php">
					결제내역
				</a>
            </td>
        </tr>
		<tr>
			<td <?php if($TPL_VAR["global"]["nowurlh"]=='/member/coupon.php'){?>class="on" <?php }?>>
				<a href="/member/coupon.php">
					쿠폰북
				</a>
			</td>
		</tr>
<?php }else{?>
		<tr>
			<td <?php if($TPL_VAR["global"]["nowurlh"]=='/member/login.php'){?> class="on" <?php }?>>
				<a href="/member/login.php?url=<?php echo $TPL_VAR["global"]["nowurl"]?>">
					로그인
				</a>
			</td>
		</tr>
		<tr>
			<td <?php if($TPL_VAR["global"]["nowurlh"]=='/member/join.php'){?> class="on" <?php }?>>
				<a href="/member/join.php">
					회원가입
				</a>
			</td>
		</tr>
		<tr>
			<td <?php if($TPL_VAR["global"]["nowurlh"]=='/member/find-id.php'){?> class="on" <?php }?>>
				<a href="/member/find-id.php">
					아이디찾기
				</a>
			</td>
		</tr>
		<tr>
			<td <?php if($TPL_VAR["global"]["nowurlh"]=='/member/find-password.php'){?> class="on" <?php }?>>
				<a href="/member/find-password.php">
					비밀번호찾기
				</a>
			</td>
		</tr>
<?php }?>
        </tbody>
    </table>
    <div class="subB">
        <a href="/rent/search.php" class="goReservation"><img src="/imgs/rentking.w/subBnner.jpg"></a>
    </div><!--//subB-->
</div><!--//lnb-->