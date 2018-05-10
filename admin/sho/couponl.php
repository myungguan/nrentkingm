<?
$idx = $_REQUEST['idx'];
$ar_data = sel_query_all("coupons", " where idx='$idx'");
$mode = $_REQUEST['mode'];
if ($mode == 'w') {
	$value['edate'] = $_POST['dt_use_end'];
	update("member_coupons", $value, " where coupon_idx='$idx' and dt_use IS NULL");

	echo "<script>alert('연장처리 완료'); location.replace('$PHP_SELF?code=$code&idx=$idx');</script>";
	exit;
}
?>

<script language="javascript" type="text/javascript">
	function gotoch() {
		if (confirm('연장하시겠습니까?')) {
			return true;
		}
		else {
			return false;
		}
	}
</script>
<div class="content">

	<div class="form_wrap">
		<table class="detailTable2">
			<tr>
				<th>
					쿠폰이름
				</th>
				<td>
					<?=$ar_data['name'];?>
				</td>
			</tr>

		</table>
	</div><!-- // .form_wrap -->
	<h3>* 사용기간설정</h3>

	<div class='site_select' style="height:53px">
		<div style="float:left;margin-top:4px">종료기간 :</div>
		<div style="float:left">
			<form id="ref" name="ref" action="<?=$PHP_SELF;?>?code=<?=$code;?>" method="post" onsubmit="return gotoch();">
				<input type='hidden' name='idx' value='<?=$idx;?>'>
				<input type='hidden' name='mode' value='w'>
				<input type='text' name='dt_use_end' id='dt_use_end' class="dateTimePicker" data-parent="#container" readonly>
				<span class="btn_white_s btn_submit" data-form="#ref"><a href="javascript:">연장하기</a></span>
			</form>
		</div>
	</div>
	<div style="clear:both;"></div>

	<h3>* 사용통계</h3>
	<div class="list_wrap order_list">
		<table class="detailTable2">
			<colgroup>
				<col width="45px"/>
				<col width=""/>
				<col width=""/>
				<col width=""/>
				<col width="100px"/>
			</colgroup>
			<thead>
			<tr>
				<th>번호</th>
				<th>회원명</th>
				<th>사용기간</th>
				<th>사용여부</th>
				<th>사용주문번호</th>
			</tr>
			</thead>
			<tbody>

			<?php
			$page = $_GET['page'];
			if (!$page) {
				$page = 1;
			}
			$num_per_page = 30;
			$page_per_block = 10;

			$len_cate = strlen($cate);

			$q = "select count(idx) from member_coupons where coupon_idx='$idx'";
			if ($keyword != '') {
				$q = $q . " and $key like '%$keyword%'";
			}

			$r = mysql_query($q);
			$total_record = mysql_result($r, 0, 0);
			mysql_free_result($r);

			$total_page = ceil($total_record / $num_per_page);

			if ($total_record == 0) {
				$first = 0;
				$last = 0;
			} else {
				$first = $num_per_page * ($page - 1);
				$last = $num_per_page * $page;
			}

			$q = "select * from member_coupons where coupon_idx='$idx'";
			if ($keyword != '') {
				$q = $q . " and $key like '%$keyword%'";
			}
			$q = $q . " order by idx desc limit $first, $num_per_page";
			$r = mysql_query($q);
			$article_num = $total_record - (($page - 1) * $num_per_page);
			$cou = 0;
			while ($row = mysql_fetch_array($r)) {
				$co = "";
				if (!($cou % 2)) {
					$co = "gray";
				}

				$ar_m = sel_query("member", "idx,name", " where idx='$row[member_idx]'");
				?>

				<tr class='<?=$co;?>' onmouseover="this.style.backgroundColor='#F6F6F6'" onmouseout="this.style.backgroundColor=''">
					<td class="first"><?=$row['idx'];?></td>
					<td>
						<a href="javascript:MM_openBrWindow('help.php?code=view&idx=<?=$ar_m['idx'];?>','member<?=$ar_m['idx'];?>','scrollbars=yes,width=1150,height=900,top=0,left=0');"><?=$ar_m['name'];?></a>
					</td>
					<td><?=$row['sdate'];?> ~ <?=$row['edate'];?></td>
					<td>
						<?=$row['dt_use'] ?>
					</tD>
					<td>
						<?
						if ($row['payment_idx'] != '') {
							?>
							<a href="javascript:MM_openBrWindow('order.php?code=newview&idx=<?=$row['payment_idx'];?>','order<?=$row['payment_idx'];?>','scrollbars=yes,width=1150,height=900,top=0,left=0');"><?=$row['payment_idx'];?></a>
						<?
						} ?>
					</td>
				</tr>
				<?php
				$article_num --;
				$cou ++;
			}
			?>
			</tbody>
		</table>
	</div><!-- // .list_wrap -->


	<div class="paging">

		<?php

		############### 게시물 페이지 이동
		$total_block = ceil($total_page / $page_per_block);
		$block = ceil($page / $page_per_block);

		$first_page = ($block - 1) * $page_per_block;
		$last_page = $block * $page_per_block;

		if ($total_block <= $block) {
			$last_page = $total_page;
		}

		###########################

		if ($block > 1) {
			$before_page = $first_page;
			echo("<span class='btn_white_xs btn_prev'><a href=$PHP_SELF?page=$before_page&code=$code&sgcate=$sgcate&key=$key&keyword=$keyword&idx=$idx><i class='fa fa-angle-left'></i>이전</a></span>");
		}

		for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page ++) {
			if ($page == $direct_page) {
				echo("<strong class='current'>$direct_page</strong>");
			} else {
				echo("<a href=$PHP_SELF?page=$direct_page&code=$code&sgcate=$sgcate&key=$key&keyword=$keyword&idx=$idx>$direct_page</a>");
			}
		}
		if ($block < $total_block) {
			$daum_page = $last_page + 1;
			echo("<span class='btn_white_xs btn_next'><a href=$PHP_SELF?page=$daum_page&code=$code&sgcate=$sgcate&key=$key&keyword=$keyword&idx=$idx>다음<i class='fa fa-angle-right'></i></a></span>");
		}
		?>

	</div><!-- // .paging -->
</div><!-- // .content -->