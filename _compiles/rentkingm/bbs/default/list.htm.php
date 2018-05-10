<?php /* Template_ 2.2.8 2017/11/09 17:48:35 D:\Documents\Desktop\www\old\sites\tpls\rentkingm\bbs\default\list.htm 000001619 */ 
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

		<div class="board">
			<ul class="list">
<?php if($TPL_bbs2_1){foreach($TPL_VAR["bbs2"] as $TPL_V1){?>
				<li>
					<a href="<?php echo $TPL_V1["readlinks"]?>">
						<span class="title"><?php echo $TPL_V1["title"]?></span>
						<span class="date"><?php echo $TPL_V1["dt_create"]?></span>
					</a>
				</li>
<?php }}?>
			</ul>

			<div class="paging">
<?php if($TPL_VAR["paging"]["total_record"]!= 0){?>
				<a href="<?php echo $TPL_VAR["paging"]["first"]?>" class="prev">이전</a>
<?php }?>
<?php if($TPL_loop_paging_1){foreach($TPL_VAR["loop_paging"] as $TPL_V1){?>
<?php if($TPL_V1["page"]==$TPL_VAR["paging"]["nowpage"]){?>
					<a href="javascript:return false;" class="now"><?php echo $TPL_V1["page"]?></a>
<?php }else{?>
					<a href="<?php echo $TPL_V1["links"]?>"><?php echo $TPL_V1["page"]?></a>
<?php }?>
<?php }}?>
<?php if($TPL_VAR["paging"]["total_record"]!= 0){?>
				<a href="<?php echo $TPL_VAR["paging"]["last"]?>" class="next">다음</a>
<?php }?>
			</div>
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>