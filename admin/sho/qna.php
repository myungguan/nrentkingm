<?php
$isanswer = $_REQUEST['isanswer'];
$mode = $_REQUEST['mode'];
$keyword = trim($_REQUEST['keyword']);
$page = $_REQUEST['page'];


if ($mode == 'move') {
	$ar_index = $_REQUEST['ar_index'];
	$ar_index = explode("|R|", $ar_index);
	for ($i = 0; $i < sizeof($ar_index); $i ++) {
		if ($ar_index[$i] != '') {
			$value['result'] = "D";
			$value['resultwriter'] = $memname;
			update("qna", $value, " where idx='$ar_index[$i]'");
		}

	}
	echo "<script>location.replace('$PHP_SELF?code=$code&page=$page&key=$key&keyword=$keyword&sb=$sb'); </script>";
	exit;
}

$num_per_page = 20;
$page_per_block = 10;
$reply_limit = 6;

$page = $_GET['page'];
if (!$page) {
	$page = 1;
}

$countQuery = "SELECT count(q.idx) 
	FROM
		qna q
		LEFT JOIN member m ON q.member_idx = m.idx
		LEFT JOIN member ad ON q.admin_idx = ad.idx
	WHERE
		q.dt_delete IS NULL";
$listQuery = "SELECT q.*, m.id mem_id, m.name mem_name, ad.name ad_name
	FROM
		qna q
		LEFT JOIN member m ON q.member_idx = m.idx
		LEFT JOIN member ad ON q.admin_idx = ad.idx
	WHERE
		q.dt_delete IS NULL";

$where = '';

if ($isanswer) {
	if($isanswer == 'Y')
		$where .= " AND q.dt_answer IS NOT NULL";
	else
		$where .= " AND q.dt_answer IS NULL";
}
if ($keyword) {
	$where .= " AND (m.name LIKE '%".mysql_escape_string($keyword)."%' OR m.id LIKE '%".mysql_escape_string($keyword)."%' OR q.subject LIKE '%".mysql_escape_string($keyword)."%' OR q.memo LIKE '%".mysql_escape_string($keyword)."%')";
}
if ($_REQUEST['sdate']) {
	$where .= " AND q.dt_create>='$sdate 00:00:00'";
}
if ($_REQUEST['edate']) {
	$where .= " AND q.dt_create<='$edate 23:59:59'";
}

$r = mysql_query($countQuery.$where);
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

<div style='margin-top:10px;'>
	<form name='listform' action="/sho.php" method="get">
		<input type="hidden" name="code" value="<?=$code ?>" />
		<input type='hidden' name='page' value='<?=$page;?>'>
		<input type='hidden' name='ar_index' value=''>
		<input type='hidden' name='mode' value='move'>
		<input type='hidden' name='fid' value='<?=$fid;?>'>

		<!--
		<table class="detailTable2">
			<tr>
				<th>답변상태</th>
				<td><select class="uch" name='isanswer' style="z-index: 4;">
						<option value=''>전체</option>
						<option value='N' <? if ($isanswer == 'N') {
							echo "selected";
						} ?>>답변안됨
						</option>
						<option value='D' <? if ($isanswer == 'D') {
							echo "selected";
						} ?>>답변중
						</option>
						<option value='Y' <? if ($isanswer == 'Y') {
							echo "selected";
						} ?>>답변완료
						</option>
					</select></td>
			</tr>
			<tr>
				<th>검색어</th>
				<td>
					<input type='text' name='keyword' value='<?=$keyword;?>' size='40' class='basic_input' placeholder="작성자이름, ID, 제목, 내용">
				</td>
			<tr>
				<th>질문일시</th>
				<td>
					<input type='text' name='sdate' value='<?=$sdate;?>' id='sdates' class="datePicker" data-parent="#container" readonly> ~
					<input type='text' name='edate' value='<?=$edate;?>' id='edates' class="datePicker" data-parent="#container" readonly>

				</td>
			</tr>
			</tr>
		</table>
		<div class="btn_wrap btn_center btn_bottom">
			<span class="btn_green btn_submit" data-form="#form1"><a href="javascript:">검색하기</a></span>
<!--			<span class="btn_green"><a data-title="질문과답변 EXCEL" class="excel_download" href="./excel/excel_down.php?act=qna">EXCEL</a></span>
		</div>
		-->

	<div class="h3_wrap">
		<h3>총 : <?=number_format($total_record);?> 게시물</h3>
	</div>


	<div>
		<table class="listTableColor">
			<thead>
			<tr>
				<th class="sd1">번호</th>
				<th class="sd1">제 목</th>
				<th class="sd1">작성자</th>
				<th class="sd1">작성자ID</th>
				<th class="sd1">질문일시</th>
				<th class="sd1">답변</th>
				<th class="sd1">답변자</th>
			</tr>
			</thead>
			<tbody>

			<?
			//엑셀쿼리
			$listQuery .= $where . " ORDER BY q.idx DESC";
			$_SESSION['sql_excel'] = $listQuery;

			$listQuery .= " LIMIT $first, $num_per_page";

			$r = mysql_query($listQuery);
			$article_num = $total_record - (($page - 1) * $num_per_page);
			while ($row = mysql_fetch_array($r)) {
				$pop = "<a href=\"javascript:MM_openBrWindow('/popup/memview.php?idx={$row['member_idx']}','member{$row['member_idx']}','scrollbars=yes,width=1150,height=700,top=0,left=0');;\">";
				?>

				<tr>
					<td class="sd2"><?=$row['idx'];?></td>
					<td style='text-align:left;padding-left:10px;'>
						<a href="/sho.php?code=<?=$code;?>r&idx=<?=$row['idx'];?>&page=<?=$page;?>&keyword=<?=$keyword;?>&isanswer=<?=$isanswer;?>"><?=$row['subject'];?><? if (trim($row['subject']) == '') {?>문의<?}?></a>

					</td>
					<td style="text-align:left;padding:0 5px;"><?=$pop;?><?=$row[mem_name];?></a></td>
					<td style="text-align:left;padding:0 5px;"><?=$row[mem_id];?></td>
					<td class="sd2"><?=$row['dt_create'];?></td>
					<td class="sd3">
						<? if ($row['dt_answer']) { ?>답변완료
						<? } else {?><font color='red'>미답변</font>
						<?}?>
					</td>
					<td><? if ($row['dt_answer']) { ?><?=$row['ad_name'];?><?}?></td>
				</tr>
				<?php
				$article_num --;
				$cou ++;
				$data[] = $row;
			}
			?>
			</tbody>

		</table>
</form>
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
		echo("<span class='btn_white_xs btn_prev'><a href=$PHP_SELF?page=$before_page&key=$key&keyword=$keyword&code=$code&sb=$sb&isanswer=$isanswer&sre=$sre&fid=$fid&sdate=$sdate&edate=$edate><i class='fa fa-angle-left'></i>이전</a></span> ");
	}

	for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page ++) {
		if ($page == $direct_page) {
			echo(" <strong class='current'>$direct_page</strong>");
		} else {
			echo(" <a href=$PHP_SELF?page=$direct_page&key=$key&keyword=$keyword&code=$code&sb=$sb&isanswer=$isanswer&sre=$sre&fid=$fid&sdate=$sdate&edate=$edate>$direct_page</a>");
		}
	}
	if ($block < $total_block) {
		$daum_page = $last_page + 1;
		echo(" <span class='btn_white_xs btn_next'><a href=$PHP_SELF?page=$daum_page&key=$key&keyword=$keyword&code=$code&sb=$sb&isanswer=$isanswer&sre=$sre&fid=$fid&sdate=$sdate&edate=$edate>다음<i class='fa fa-angle-right'></i></a></span>");
	}
	?>
</div><!-- // .paging -->

<script language="javascript" type="text/javascript">
	$(document).ready(function () {
		$(document).on("click", "a.excel_download", function () {

			$.fileDownload($(this).prop('href'), {
				successCallback: function (url) {
				},
				failCallback: function (responseHtml, url) {
					alert('다운로드에 실패하였습니다.');
				}
			});

			return false;
		});
	});
	function allch() {
		for (var i = 0; i < document.listform.index.length; i++) {
			if (document.listform.index[i].checked == true) {
				document.listform.index[i].checked = false;
			}
			else {
				document.listform.index[i].checked = true;
			}
		}
	}
	function set_move() {
		var str = '';
		var k = 0;
		if (document.listform.index.length) {
			for (var i = 0; i < document.listform.index.length; i++) {
				if (document.listform.index[i].checked == true) {
					k = k + 1;
					str = str + document.listform.index[i].value + '|R|';
				}

			}
			if (k == 0) {
				alert('이동 게시물선택' + k);
				return;
			}
		}
		else {
			if (document.listform.index.checked == true) {
				k = k + 1;
				str = str + document.listform.index.value + '|R|';
			}
			if (k == 0) {
				alert('이동 게시물선택');
				return;
			}
		}
		document.listform.ar_index.value = str;
		document.listform.submit();
	}

	function set_qnacate() {
		var fid = $('#id_fid option:selected').val();

		var param = "mode=qnacate&fid=" + fid;
		$.ajax({
			type: "GET",
			url: "./ajaxmo/get_pid.php",
			dataType: "html",
			data: param,
			success: function (msg) {
				$('#id_sb').html(msg);
			}
		});
	}
</script>


