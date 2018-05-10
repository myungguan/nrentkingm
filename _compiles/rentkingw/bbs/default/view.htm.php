<?php /* Template_ 2.2.8 2017/12/28 16:47:47 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/bbs/default/view.htm 000002573 */ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
<?php if($TPL_VAR["bbs"]["boardid"]=='notice'||$TPL_VAR["bbs"]["boardid"]=='pnotice'){?>
<?php $this->print_("side_customer_center",$TPL_SCP,1);?>

		<div id="content">
<?php }?>
			<div class="conBanner">
				<div class="txt">
					<h2><?php if($TPL_VAR["bbs"]["boardid"]=='notice'){?>공지사항
<?php }elseif($TPL_VAR["bbs"]["boardid"]=='pnotice'){?>멤버사공지사항
<?php }elseif($TPL_VAR["bbs"]["boardid"]=='event'){?>이벤트
<?php }?></h2>
					<strong><?php if($TPL_VAR["bbs"]["boardid"]=='notice'||$TPL_VAR["bbs"]["boardid"]=='pnotice'){?>렌트킹에서 알려드립니다!
<?php }elseif($TPL_VAR["bbs"]["boardid"]=='event'){?>다양한 지점들의 끊임없는 이벤트!
<?php }?></strong>
					<p><?php if($TPL_VAR["bbs"]["boardid"]=='notice'||$TPL_VAR["bbs"]["boardid"]=='pnotice'){?>렌트킹의 새로운 소식! 기분 좋은 뉴스를 확인해보세요!
<?php }elseif($TPL_VAR["bbs"]["boardid"]=='event'){?>여러 지역, 많은 지점들의 다양한 이벤트를 경험해보세요!
<?php }?></p>
				</div><!--//txt-->
				<span>
<?php if($TPL_VAR["bbs"]["boardid"]=='notice'||$TPL_VAR["bbs"]["boardid"]=='pnotice'){?><img src="/imgs/rentking.w/notice.jpg" alt="Icon">
<?php }elseif($TPL_VAR["bbs"]["boardid"]=='event'){?><img src="/imgs/rentking.w/eventTopicon.jpg" alt="Icon">
<?php }?>
				</span>
			</div><!--//conBanner-->
			<div class="boardView ">
				<table class="list">
					<colgroup>
						<col width="*">
						<col width="120px;">
					</colgroup>
					<thead>
					<tr>
						<th><?php echo $TPL_VAR["bbs"]["title"]?></th>
						<th style="width:120px;"><?php echo substr($TPL_VAR["bbs"]["dt_create"], 0, 10)?></th>
					</tr>
					</thead>
					<tbody>

					<tr class="view">
						<td colspan="2">
							<div class="detail">
								<?php echo $TPL_VAR["bbs"]["content"]?>

							</div>
						</td>
					</tr>
					</tbody>
				</table>
				<div class="btnArea">
					<a href="<?php echo $TPL_VAR["bbs"]["listlink"]?>" class="btn-point">목록</a>
				</div>
			</div>
<?php if($TPL_VAR["bbs"]["boardid"]=='notice'||$TPL_VAR["bbs"]["boardid"]=='pnotice'){?>
		</div>
<?php }?>
	</div>


<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>