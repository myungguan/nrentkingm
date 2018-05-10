<?php /* Template_ 2.2.8 2017/12/07 10:50:31 D:\Documents\Desktop\www\old\sites\tpls\rentkingw\customer-center\qnalist.htm 000002720 */ 
$TPL_qnalist_1=empty($TPL_VAR["qnalist"])||!is_array($TPL_VAR["qnalist"])?0:count($TPL_VAR["qnalist"]);
$TPL_loop_paging_1=empty($TPL_VAR["loop_paging"])||!is_array($TPL_VAR["loop_paging"])?0:count($TPL_VAR["loop_paging"]);?>
<!DOCTYPE html>
<html lang="ko">
<head>
<?php $this->print_("head",$TPL_SCP,1);?>

</head>
<body>
<?php $this->print_("header",$TPL_SCP,1);?>

	<div id="contentWrap">
<?php $this->print_("side_customer_center",$TPL_SCP,1);?>

		<div id="content">
			<div class="conBanner">
				<div class="txt">
					<h2>Q&amp;A</h2>
					<strong>무엇이든 물어보세요!</strong>
					<p>자주 묻는 질문에 없는 궁금증은 Q&amp;A를 통해 물어봐주세요!</p>
				</div><!--//txt-->
				<span>
					<img alt="Icon" src="/imgs/rentking.w/clauseTopIcon.jpg">
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
					<th>제목</th>
					<th>날짜</th>
				</tr>
				</thead>
				<tbody>
<?php if($TPL_qnalist_1){foreach($TPL_VAR["qnalist"] as $TPL_V1){?>
				<tr>
					<td><?php echo $TPL_V1["no"]?></td>
					<td class="etit" style='text-align:left;'>
						<a href="qnar.php?idx=<?php echo $TPL_V1["index"]?>"><?php if($TPL_V1["dt_answer"]){?>[답변완료]<?php }else{?>[답변대기]<?php }?> <?php echo $TPL_V1["subject"]?></a>
					</td>
					<td><?php echo substr($TPL_V1["dt_create"], 0, 10)?></td>
				</tr>
<?php }}?>
				</tbody>
			</table><!--//boxTop-->
			<div class="btnArea">
				<a href="qnaw.php" class="btn-point">질문작성</a>
			</div><!--//btnWrap-->
			<!-- paging -->
			<div class="paging">
<?php if($TPL_VAR["paging"]["total_record"]!= 0){?>
				<a href="<?php echo $TPL_VAR["paging"]["first"]?>" class="prev"><img src="/imgs/rentking.w/btn_prev01.gif"></a>
<?php }?>
<?php if($TPL_loop_paging_1){foreach($TPL_VAR["loop_paging"] as $TPL_V1){?>
<?php if($TPL_V1["page"]==$TPL_VAR["paging"]["nowpage"]){?>
				<a href="javascript:return false;" class="on"><?php echo $TPL_V1["page"]?></a>
<?php }else{?>
				<a href="<?php echo $TPL_V1["links"]?>"><?php echo $TPL_V1["page"]?></a>
<?php }?>
<?php }}?>
<?php if($TPL_VAR["paging"]["total_record"]!= 0){?>
				<a href="<?php echo $TPL_VAR["paging"]["last"]?>" class="next"><img src="/imgs/rentking.w/btn_next01.gif"></a>
<?php }?>


			</div>
			<!-- //paging -->
		</div>
	</div>
<?php $this->print_("footer",$TPL_SCP,1);?>

</body>
</html>