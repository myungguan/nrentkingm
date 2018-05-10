<?php /* Template_ 2.2.8 2017/11/23 18:31:14 D:\Documents\Desktop\www\old\sites\tpls\rentkingw\member\coupon.htm 000002073 */ 
$TPL_clist_1=empty($TPL_VAR["clist"])||!is_array($TPL_VAR["clist"])?0:count($TPL_VAR["clist"]);?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
<?php $this->print_("side_member",$TPL_SCP,1);?>

		<div id="content">
			<div class="conBanner">
				<div class="txt">
					<h2>쿠폰북</h2>
					<strong>바로바로 할인!</strong>
					<p>실시간으로 올라오는 특별한 할인쿠폰을 만나보세요!</p>
				</div><!--//txt-->
				<span>
					<img src="/imgs/rentking.w/coupnBkTopIcon.jpg" alt="Icon">
				</span>
			</div><!--//conBanner-->
			<form method="post" style="position:relative;background:#f8f8f8;border:solid 1px #ccc;padding:10px 208px 10px 110px;">
				<input type="hidden" name="mode" value="r" />
				<input type="text" class="" name="nums" id="coupon" value="" placeholder="코드를 입력해주세요." style="display:block;width:100%;line-height:10px;padding:10px;">
				<button type="submit" class="btn-point" style="position:absolute;top:10px;right:122px;">등록하기</button>
			</form>
			<div class="couponCon">
				<ul>
<?php if($TPL_clist_1){foreach($TPL_VAR["clist"] as $TPL_V1){?>
					<li>
						<img src="<?php echo $TPL_VAR["global"]["imgserver"]?><?php echo $TPL_V1["path"]?>" alt="쿠폰이미지">
						<table class="list" style="border-top:none;">
							<colgroup>
								<col width="100px">
								<col>
							</colgroup>
							<tr>
								<th>쿠폰명</th>
								<td><?php echo $TPL_V1["name"]?></td>
							</tr>
							<tr>
								<th>사용기간</th>
								<td>
									<?php echo $TPL_V1["sdate"]?> ~ <?php echo $TPL_V1["edate"]?>

								</td>
							</tr>
						</table>
					</li>
<?php }}?>
				</ul>
			</div>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>