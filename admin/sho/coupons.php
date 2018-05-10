<?
$idx = $_REQUEST['idx'];
$ar_data = sel_query_all("coupons", " WHERE idx='$idx'");
$mode = $_REQUEST['mode'];


if ($mode == 'w') {
	$cous = $_REQUEST['cous'];

	for ($i = 0; $i < $cous; $i ++) {
		if ($ar_data['type'] == 'SE') {
			$value['serialnum'] = $ar_data['serialnum'];
		} else {
			$q = "SHOW TABLE STATUS WHERE name = 'coupon_serials'";
			$r = mysql_query($q);
			$row = mysql_fetch_array($r);
			$folder = $row["Auto_increment"];

			$nums = "RENTKING" . $folder . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
			$value['serialnum'] = createCouponSerial($idx);
		}

		$value['coupon_idx'] = $idx;
		$value['dt_create'] = date("Y-m-d H:i:s", time());
		insert("coupon_serials", $value);
		unset($value);
	}

	echo "<Script>alert('생성완료'); location.replace('$PHP_SELF?code=$code&idx=$idx');</script>";
	exit;
}
?>
<div class="content">

	<div class="form_wrap">
		<form name="makeform" id="makeform" action="<?=$PHP_SELF;?>?code=<?=$code;?>" method="post">
			<input type='hidden' name='idx' value='<?=$idx;?>'>
			<input type='hidden' name='mode' value='w'>
			<table class="detailTable2">
				<tr>
					<th>
						쿠폰이름
					</th>
					<td>
						<?=$ar_data[name];?>
					</td>
				</tr>
				<tr>
					<Th>쿠폰번호생성
					</td>
					<td>
						<? if ($ar_data['type'] == 'RS') { ?>시리얼 갯수 : <input type='text' name='cous' size='4'>개<? } ?>
						<? if ($ar_data['type'] == 'SE') { ?>시리얼 갯수 :
							<input type='text' name='cous' size='4'>개 | 쿠폰번호 :
							<input readonly type='text' name='coupons' size='20' value="<?=$ar_data['serialnum']?>"><? } ?>
					</td>
				</tr>
			</table>

	</div><!-- // .form_wrap -->
	<div class="btn_wrap btn_center btn_bottom">
		<span class="btn_green btn_submit" data-form="#makeform"><a href="javascript:">생성하기</a></span>
		<span class="btn_green"><a href="./excel/excel_serial.php?idx=<?=$idx;?>">EXCEL</a></span>
	</div>
	</form>
	<br/>

	<table class="listTableColor">
		<colgroup>
			<col width="45px"/>
			<col width=""/>
			<col width=""/>
			<col width=""/>
		</colgroup>
		<thead>
		<tr>
			<th>번호</th>
			<th>시리얼번호</th>
			<th>생성일</th>
			<th>등록일</th>
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

		$q = "select count(idx) from coupon_serials where coupon_idx='$idx'";
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

		$q = "select * from coupon_serials where coupon_idx='$idx'";
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

			?>

			<tr class='<?=$co;?>' onmouseover="this.style.backgroundColor='#F6F6F6'" onmouseout="this.style.backgroundColor=''">
				<td class="first"><?=$row[idx];?></td>
				<td><?=$row['serialnum'];?></td>
				<Td><?=$row['dt_create'];?></td>
				<Td><?=$row['dt_use'];?></td>
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