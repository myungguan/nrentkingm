<?
$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$usek = $_REQUEST['usek'];
$coupon_idxs = $_REQUEST['coupon_idxs'];
$datakey = $_REQUEST['datakey'];
?>
<div class="content">

	<div class='site_select' style="height:53px">
		<div style="float:left;margin-top:4px">검색조건 :</div>
		<div style="float:left">
			<form id="search" name="search" action="<?=$PHP_SELF;?>?code=<?=$code;?>" method="post">
				<select name='coupon_idxs'>
					<option value=''>쿠폰선택</option>
					<?php
					$q = "SELECT * FROM coupons WHERE dt_delete IS NULL ORDER BY idx DESC";
					$r = mysql_query($q);
					while ($row = mysql_fetch_array($r)) {
						if ($coupon_idxs == $row['idx']) {
							echo "<option value='$row[idx]' selected>$row[name]</option>";
							$cname = $row['name'];
						} else {
							echo "<option value='$row[idx]'>$row[name]</option>";
						}
					}
					?>
				</select>
				<Select name='usek'>
					<option value=''>전체</option>
					<option value='Y'>사용함</option>
					<option value='N'>사용안함</option>
				</select>

				<Select name='datakey'>
					<option value='dt_create' <? if ($datakey == 'dt_create') {
						echo "selected";
					} ?>>지급일
					</option>
					<option value='dt_use' <? if ($datakey == 'dt_use') {
						echo "selected";
					} ?>>사용일
					</option>
				</select>

				<input type='text' name='sdate' id='wdate_s1' size='10' class="datePicker" data-parent="#container" readonly value='<?=$sdate;?>'> ~
				<input type='text' name='edate' id='wdate_s2' size='10' class="datePicker" data-parent="#container" readonly value='<?=$edate;?>'>
				<span class="btn_white_s btn_submit" data-form="#search"><a href="javascript:">검색하기</a></span>
			</form>
		</div>
	</div>
	<div style="clear:both;"></div>

	<h3>* 검색조건 사용통계</h3>
	<div class="form_wrap">
		<table class="detailTable2">
			<tr>
				<th>기간</th>
				<td>
					<?=$sdate;?> ~ <?=$edate;?>
				</td>
				<th>쿠폰명</th>
				<td>
					<? if ($coupon_idxs) { ?><?=$cname;?><? } ?>
				</td>
				<?
				$q = "SELECT count(idx) FROM member_coupons WHERE 1";
				if ($coupon_idxs) {
					$q = $q . " and coupon_idx='$coupon_idxs'";
				}
				if ($sdate) {
					$q = $q . " and LEFT($datakey,10)>='$sdate'";
				}
				if ($edate) {
					$q = $q . " and LEFT($datakey,10)<='$edate'";
				}
				$q = $q . " and dt_use IS NOT NULL";
				$r = mysql_query($q);
				$row = mysql_fetch_array($r);

				$u1 = $row[0];

				$q = "SELECT count(idx) FROM member_coupons WHERE 1";
				if ($coupon_idxs) {
					$q = $q . " and coupon_idx='$coupon_idxs'";
				}
				if ($sdate) {
					$q = $q . " and LEFT($datakey,10)>='$sdate'";
				}
				if ($edate) {
					$q = $q . " and LEFT($datakey,10)<='$edate'";
				}
				$q = $q . " and dt_use IS NULL";
				$r = mysql_query($q);
				$row = mysql_fetch_array($r);

				$u2 = $row[0];
				?>
				<th>총건수</th>
				<td><?=number_format($u1 + $u2);?></td>
				<th>사용</th>
				<td><?=number_format($u1);?></td>
				<th>사용안함</th>
				<td><?=number_format($u2);?></td>
			</tr>
		</table>
	</div>

	<?
	$page = $_GET['page'];
	if (!$page) {
		$page = 1;
	}
	$num_per_page = 40;
	$page_per_block = 10;

	$len_cate = strlen($cate);

	$q = "SELECT count(idx) FROM member_coupons WHERE 1";
	if ($coupon_idxs) {
		$q = $q . " and coupon_idx='$coupon_idxs'";
	}
	if ($sdate) {
		$q = $q . " and LEFT($datakey,10)>='$sdate'";
	}
	if ($edate) {
		$q = $q . " and LEFT($datakey,10)<='$edate'";
	}
	if ($usek == 'Y') {
		$q = $q . " and dt_use IS NOT NULL";
	}
	if ($usek == 'N') {
		$q = $q . " and dt_use IS NULL";
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
	?>
	<h3>* 검색 총 <?=number_format($total_record);?>건 검색</h3>
	<table class="listTableColor">
		<table class="detailTable2">
			<colgroup>
				<col width="45px"/>
				<col width=""/>
				<col width=""/>
				<col width=""/>
				<col width=""/>
				<col width=""/>
				<col width=""/>
				<col width=""/>
				<col width=""/>
			</colgroup>
			<thead>
			<tr>
				<th>번호</th>
				<th>쿠폰명</th>
				<th>회원명</th>
				<th>지급일</th>
				<th>만료일</th>
				<th>사용여부</th>
				<th>사유</th>
			</tr>
			</thead>
			<tbody>

			<?php


			$q = "SELECT c.name coupon_name, cm.* FROM member_coupons cm LEFT JOIN coupons c ON cm.coupon_idx = c.idx WHERE 1";
			if ($coupon_idxs) {
				$q = $q . " and cm.coupon_idx='$coupon_idxs'";
			}
			if ($sdate) {
				$q = $q . " and LEFT($datakey,10)>='$sdate'";
			}
			if ($edate) {
				$q = $q . " and LEFT($datakey,10)<='$edate'";
			}
			if ($usek == 'Y') {
				$q = $q . " and cm.dt_use IS NOT NULL";
			}
			if ($usek == 'N') {
				$q = $q . " and cm.dt_use IS NULL";
			}
			$q = $q . " order by cm.idx desc limit $first, $num_per_page";
			$r = mysql_query($q);
			$article_num = $total_record - (($page - 1) * $num_per_page);
			$cou = 0;
			while ($row = mysql_fetch_array($r)) {
				$co = "";
				if (!($cou % 2)) {
					$co = "gray";
				}

				$ar_m = sel_query("member", "name", " where idx='$row[member_idx]'");
				?>

				<tr class='<?=$co;?>' onmouseover="this.style.backgroundColor='#F6F6F6'" onmouseout="this.style.backgroundColor=''">
					<td class="first"><?=$article_num;?></td>
					<td style='text-align:left;padding-left:5px;'><?=$row['coupon_name'];?></td>
					<td>
						<a href="javascript:MM_openBrWindow('/popup/memview.php?idx=<?=$row['member_idx']?>','member<?=$row['member_idx']?>','scrollbars=yes,width=1150,height=700,top=0,left=0');"><?=$ar_m['name'];?></a>
					</td>
					<td><?=$row['dt_create'];?></td>
					<td><?=$row['edate'];?></td>
					<td><?
						if ($row['dt_use']) {
							echo $row['dt_use'] . " ";
							?>
							주문번호 :
							<a href="javascript:MM_openBrWindow('/popup/orderview.php?idx=<?=$row['payment_idx'];?>','order<?=$row['payment_idx'];?>','scrollbars=yes,width=1150,height=900,top=0,left=0');"><?=$row['payment_idx'];?></a>
							<?
						} ?></td>
					<td style='text-align:left;'><?=$row['memo'];?></td>
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
		echo("<span class='btn_white_xs btn_prev'><a href=$PHP_SELF?page=$before_page&code=$code&sdate=$sdate&edate=$edate&coupon_idxs=$coupon_idxs&usek=$usek&datakey=$datakey><i class='fa fa-angle-left'></i>이전</a></span>");
	}

	for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page ++) {
		if ($page == $direct_page) {
			echo("<strong class='current'>$direct_page</strong>");
		} else {
			echo("<a href=$PHP_SELF?page=$direct_page&code=$code&sdate=$sdate&edate=$edate&coupon_idxs=$coupon_idxs&usek=$usek&datakey=$datakey>$direct_page</a>");
		}
	}
	if ($block < $total_block) {
		$daum_page = $last_page + 1;
		echo("<span class='btn_white_xs btn_next'><a href=$PHP_SELF?page=$daum_page&code=$code&sdate=$sdate&edate=$edate&coupon_idxs=$coupon_idxs&usek=$usek&datakey=$datakey>다음<i class='fa fa-angle-right'></i></a></span>");
	}
	?>

</div><!-- // .paging -->
</div><!-- // .content -->