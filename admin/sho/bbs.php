<?php
/**
 * admin.rentking.co.kr/sho.php?code=bbs
 * 관리자페이지 > 사이트관리 > 게시물관리
 * 게시물관리 목록
 */
?>

<div class="btn_wrap btn_right" style='margin:13px 0px;'>
	<select class="uch" name='boardid' id="id_boardid">
		<option value="">게시판을선택하세요</option>
        <option value="렌트킹뉴스">렌트킹뉴스</option>
		<option value="공지사항">공지사항</option>
		<option value="이벤트">이벤트</option>
		<option value="멤버사 공지사항">멤버사 공지사항</option>
	</select>
	<span class="btn_white_xs btn_navy"><a href="javascript:write_bbs()">글쓰기</a></span>
</div>

<div>
	<table class="listTableColor">
		<colgroup>
			<col width="45px"/>
			<col width="100px;"/>
			<col width=""/>
			<col width="65px"/>
			<col width="45px"/>
			<col width="120px"/>
		</colgroup>
		<thead>
		<tr>
			<th>번호</th>
			<th>게시판명</th>
			<th>제목</th>
			<th>작성자</th>
			<th>조회수</th>
			<th>등록일</th>
		</tr>
		</thead>
		<tbody>
		<?
		$num_per_page = 30;
		$page_per_block = 10;
		$reply_limit = 6;

		$page = $_GET['page'];
		if (!$page) {
			$page = 1;
		}

		$q = "SELECT count(a.idx) from (SELECT idx FROM notices WHERE dt_delete IS NULL UNION ALL SELECT idx FROM events WHERE dt_delete IS NULL) a";

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

		$q = "SELECT a.*, m.name FROM 
				(SELECT
				 	idx, 
					IF(front_yn = 'Y', '공지사항', '멤버사 공지사항') type, 
					title, 
					member_idx, 
					read_count, 
					dt_create 
				FROM notices
				WHERE dt_delete IS NULL 
			UNION 
				SELECT 
					idx,
					'이벤트' type, 
					title, 
					member_idx, 
					read_count, 
					dt_create 
				FROM events
				WHERE dt_delete IS NULL
			UNION 
				SELECT 
					idx,
					'렌트킹뉴스' type, 
					title, 
					member_idx, 
					read_count, 
					dt_create 
				FROM rentking_news
				WHERE dt_delete IS NULL
			) a
			INNER JOIN member m ON m.idx = a.member_idx ";
		$q = $q . " order by dt_create desc limit $first, $num_per_page";
		$r = mysql_query($q);

		$i = 0;
		while ($row = mysql_fetch_array($r)) {

			$row['title'] = kstrcut($row['title'], 150, "..");

			?>
			<tr onmouseover="this.style.backgroundColor='#F6F6F6'" onmouseout="this.style.backgroundColor=''">
				<td class="first"><?=$total_record - ($page == 1 ? 0 : ($page - 1) * $num_per_page) - $i?></td>
				<td><?=$row['type'];?></td>
				</td>
				<td style="text-align:left;padding-left:10px">
					<a href="sho.php?code=bbsr&idx=<?=$row['idx'];?>&boardid=<?=$row['type'];?>&page=<?=$page;?>"><?=$row['title'];?></a>
				</td>
				<td>
					<?=$pop;?><?=$row['name']?></a>
				</td>

				<td><?=$row['read_count']?></td>
				<td><?=$row['dt_create'];?></td>
			</tr>
			<?php
			$i ++;
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
		echo("<span class='btn_white_xs btn_prev'><a href=sho.php?page=$before_page&code=$code&sboardid=$sboardid&sdate=$sdate&edate=$edate&key=$key&keyword=$keyword&sisdel=$sisdel><i class='fa fa-angle-left'></i>이전</a></span>");
	}

	for ($direct_page = $first_page + 1; $direct_page <= $last_page; $direct_page ++) {
		if ($page == $direct_page) {
			echo("<strong class='current'>$direct_page</strong>");
		} else {
			echo("<a href=sho.php?page=$direct_page&code=$code&sboardid=$sboardid&sdate=$sdate&edate=$edate&key=$key&keyword=$keyword&sisdel=$sisdel>$direct_page</a>");
		}
	}
	if ($block < $total_block) {
		$daum_page = $last_page + 1;
		echo("<span class='btn_white_xs btn_next'><a href=sho.php?page=$daum_page&code=$code&sboardid=$sboardid&sdate=$sdate&edate=$edate&key=$key&keyword=$keyword&sisdel=$sisdel>다음<i class='fa fa-angle-right'></i></a></span>");
	}
	?>
</div><!-- // .paging -->

<script type="text/javascript">
	function write_bbs() {
		if ($("#id_boardid option:selected").val() == '') {
			alert('작성하고자하는 게시판을 선택하세요');
			return;
		}
		location.href = 'sho.php?code=<?=$code;?>w&boardid=' + $("#id_boardid option:selected").val();
	}
</script>