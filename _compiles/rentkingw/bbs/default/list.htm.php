<?php /* Template_ 2.2.8 2017/12/28 16:47:47 /home/rentking/dev/rentking/old/sites/tpls/rentkingw/bbs/default/list.htm 000003480 */ 
$TPL_bbs2_1=empty($TPL_VAR["bbs2"])||!is_array($TPL_VAR["bbs2"])?0:count($TPL_VAR["bbs2"]);
$TPL_loop_paging_1=empty($TPL_VAR["loop_paging"])||!is_array($TPL_VAR["loop_paging"])?0:count($TPL_VAR["loop_paging"]);?>
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
			<table class="list">
				<colgroup>
					<col width="80px;">
					<col width="*">
					<col width="120px;">
				</colgroup>
				<thead>
				<tr>
					<th>번호</th>
					<th class="etit">제목</th>
					<th>날짜</th>
				</tr>
				</thead>
				<tbody>
<?php if($TPL_bbs2_1){foreach($TPL_VAR["bbs2"] as $TPL_V1){?>
				<tr>
					<td>
						<?php echo $TPL_V1["num"]?>

					</td>
					<td class="etit" style='text-align:left;'>
						<a href="<?php echo $TPL_V1["readlinks"]?>"><?php echo $TPL_V1["title"]?></a>
					</td>
					<td><?php echo substr($TPL_V1["dt_create"], 0, 10)?>

					</td>
				</tr>
<?php }}?>
				</tbody>
			</table><!--//boxTop-->
			<!-- paging -->
			<div class="paging">
<?php if($TPL_VAR["paging"]["total_record"]!= 0){?>
				<a href="<?php echo $TPL_VAR["paging"]["first"]?>" class="prev"></a>
<?php }?>
<?php if($TPL_loop_paging_1){foreach($TPL_VAR["loop_paging"] as $TPL_V1){?>
<?php if($TPL_V1["page"]==$TPL_VAR["paging"]["nowpage"]){?>
				<a href="#" class="on" onclick="return false;"><?php echo $TPL_V1["page"]?></a>
<?php }else{?>
				<a href="<?php echo $TPL_V1["links"]?>"><?php echo $TPL_V1["page"]?></a>
<?php }?>
<?php }}?>
<?php if($TPL_VAR["paging"]["total_record"]!= 0){?>
				<a href="<?php echo $TPL_VAR["paging"]["last"]?>" class="next"></a>
<?php }?>


			</div>
			<!-- //paging -->
<?php if($TPL_VAR["bbs"]["boardid"]=='notice'||$TPL_VAR["bbs"]["boardid"]=='pnotice'){?>
		</div>
<?php }?>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>