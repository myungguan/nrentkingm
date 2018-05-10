<?
/**
 * admin.rentking.co.kr/reverse.php?code=list
 * 관리자페이지 > 예약관리 > 예약관리
 * 예약관리 페이지
 */
$searchkeyword = $_REQUEST['searchkeyword'];
$ser_sdate = $_REQUEST['ser_sdate'];
$ser_edate = $_REQUEST['ser_edate'];
$datekey = $_REQUEST['datekey'];
?>
<form id="search" name="search" action="reserve.php" method="get">
	<input type="hidden" name="code" value="<?=$code;?>" />
	<div style="margin-top:10px;">
		<input type="text" name="searchkeyword" value='<?=$searchkeyword;?>' class="" placeholder="예약번호,예약자,휴대폰,회사명,차량번호" style="width:240px;"> &nbsp;&nbsp;
		<select name="dan">
			<option value=''>진행단계선택</option>
			<option value="1" <?= $dan == '1' ? 'selected' : '' ?>>예약확정</option>
			<option value="2" <?= $dan == '2' ? 'selected' : '' ?>>대여중</option>
			<option value="3" <?= $dan == '3' ? 'selected' : '' ?>>반납완료</option>
			<option value="4" <?= $dan == '4' ? 'selected' : '' ?>>취소요청</option>
			<option value="5" <?= $dan == '5' ? 'selected' : '' ?>>취소완료</option>
		</select>
		<select name="datekey">
			<option value="sdate" <?= $datekey == 'sdate' ? 'selected' : '' ?>>배차일</option>
            <option value="edate" <?= $datekey == 'edate' ? 'selected' : '' ?>>반납일</option>
            <option value="dt_create" <?= $datekey == 'dt_create' ? 'selected' : '' ?>>예약일</option>
		</select>
		<input type="text" name="ser_sdate" id="ser_sdate" class="datePicker" data-parent="#container" value="<?=$ser_sdate?>" readonly> ~
		<input type="text" name="ser_edate" id="ser_edate" class="datePicker" data-parent="#container" value="<?=$ser_edate?>" readonly>
		<button type="submit" class="greenBtn">검색하기</button>

		<span class="greenBtn" style="float:right;" onclick="location.href='./excel/excel_down.php?act=reserve';">EXCEL</span>
<!--		<span class="greenBtn" style="float:right;margin-right:4px;" onclick="sms_reg();">SMS발송</span>-->
	</div>
</form>
<div id="printdiv">
	<!--
	<div>
		<div class="mpc_2">
			<? //include 'reserve_stat.php'; ?>
		</div>
	</div>
	-->
	<?
	$numper = $_REQUEST['numper'] ? $_REQUEST['numper'] : 50;

	$page_per_block = 10;
	$page = $_GET['page'] ? $_GET['page'] : 1;

	/* 정렬 기본 */
	if (!$sortcol) {
		$sortcol = "dt_create";
	}

	if (!$sortby) {
		$sortby = "desc";
	}

	/* //정렬 기본 */


	//HTTP QUERY STRING
	$keyword = trim($searchkeyword);
	$key = $_REQUEST['searchkey'];
	$qArr['numper'] = $numper;
	$qArr['page'] = $page;
	$qArr['code'] = $code;
	$qArr['sortcol'] = $sortcol;
	$qArr['sortby'] = $sortby;
	$qArr['dan'] = $dan;
	$qArr['searchkeyword'] = $keyword;
	$qArr['ser_sdate'] = $ser_sdate;
	$qArr['ser_edate'] = $ser_edate;
	$qArr['datekey'] = $datekey;

	if ($_SESSION['member_grade'] == '10') {
		$q = "SELECT 
			'[FIELD]'
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN member mem ON m.member_idx = mem.idx
			LEFT JOIN vehicles v ON v.idx = mt.vehicle_idx
			LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
			LEFT JOIN rentshop r ON v.rentshop_idx= r.idx
			LEFT JOIN log_visit lv ON m.session_id = lv.session_id
		WHERE v.rentshop_idx=" . $ar_rshop['idx'];
	} else {
		$q = "SELECT
			'[FIELD]' 
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN member mem ON m.member_idx = mem.idx
			LEFT JOIN vehicles v ON v.idx = mt.vehicle_idx
			LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx 
			LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
			LEFT JOIN log_visit lv ON m.session_id = lv.session_id
		WHERE 1";
	}
	if ($dan) {
		$q .= " AND m.dan='$dan'";
	}
	if ($keyword) {
		$q .= " AND (mem.name LIKE '%$keyword%' OR r.name LIKE '%$keyword%' OR r.affiliate LIKE '%$keyword%'";
		if(preg_match('/^[0-9]{4}$/', $keyword))
			$q .= " OR mem.cp LIKE '%$keyword%' OR v.carnum LIKE '%$keyword%'";
		if(preg_match('/^[0-9]{2}.[0-9]{4}$/u', $keyword))
			$q .= " OR v.carnum LIKE '%$keyword%'";
		if(preg_match('/^[0-9]+$/', $keyword))
			$q .= " OR m.idx = '$keyword'";
		$q .= ")";
	}
	if ($ser_sdate) {
		$q .= " AND LEFT(mt.$datekey,10)>='$ser_sdate'";
	}
	if ($ser_edate) {
		$q .= " AND LEFT(mt.$datekey,10)<='$ser_edate'";
	}

	//카운터쿼리
	$sql = str_replace("'[FIELD]'", "COUNT(m.idx)", $q);
	$r = mysql_query($sql);
	$total_record = mysql_result($r, 0, 0);
	mysql_free_result($r);

	if ($total_record == 0) {
		$first = 0;
		$last = 0;
	} else {
		$first = $numper * ($page - 1);
		$last = $numper * $page;
	}

	//데이터쿼리
	$_sql = str_replace("'[FIELD]'", "m.*, 
		mem.name mem_name, 
		mem.cp cp, 
		mt.vehicle_idx, 
		mt.addr, 
		mt.raddr, 
		mt.rtype, 
		mt.sdate, 
		mt.edate, 
		mt.retype, 
		vs.name modelname,
		lv.user_agent,
		(SELECT SUM(CASE WHEN turn = 1 THEN account-isouts+m.discount ELSE account-isouts END) FROM payment_accounts WHERE payment_idx = m.idx AND tbtype='I' AND account > isouts) total_account, 
		v.carnum, r.name, r.affiliate, mt.extend extendyn", $q);

	$_tArr = explode(",", $sortcol);
	if (is_array($_tArr) && count($_tArr)) {
		foreach ($_tArr as $v) {
			$orderbyArr[] = "{$v} {$sortby}";
		}
		$orderby = implode(", ", $orderbyArr);
	}

	$sql_order = " ORDER BY " . $orderby;
	$sql_limit = " LIMIT $first, $numper";
	$sql = $_sql . $sql_order . $sql_limit;

	$r = mysql_query($sql);

	//엑셀쿼리
	$sql_excel = $_sql . $sql_order;
	$_SESSION['sql_excel'] = $sql_excel;
	?>
	<div class="table_2">
		<table class="listTableColor">
			<colgroup>
				<col width="50px;">
			</colgroup>
			<thead>
			<tr>
				<th>예약번호</th>
				<th>구분</th>
				<th>예약일시</th>
				<th>예약자<br>휴대폰</th>
				<th>차종</th>
				<th>대여일시<br>반납일시</th>
				<th>회사명<br>지점</th>
				<th>배달장소<br>반납장소</th>
				<th>총금액</th>
				<th>진행상태</th>
			</tr>
			</thead>
			<tbody>
			<?while ($row = mysql_fetch_array($r)) {
				$onclick = "MM_openBrWindow('/popup/orderview.php?idx={$row['idx']}','order{$row['idx']}','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;";
				?>
				<tr>
					<td><a href="#<?=$row['idx'];?>" onclick="<?=$onclick?>"><?=$row['idx'];?></a></td>
					<td><?=$ar_retype[$row['retype']];?>(<?
						if(strpos($row['user_agent'], 'rentking/') !== FALSE) {
							echo 'A';
						} else {
							echo $row['pid'];
						}
						?>)</td>
					<td><?=str_replace(' ', '<br />', $row['dt_create']);?></td>
					<td style="text-align:left;padding:0 5px;"><?=$row['mem_name'];?><br/><?=phone_number_format($row['cp'])?></td>
					<td style="text-align:left;padding:0 5px;"><?=$row['modelname'];?><br/>[<?=$row['carnum'];?>]</td>
					<td><?=date("y.m.d H:i", strtotime($row['sdate']));?><br/><?=date("y.m.d H:i", strtotime($row['edate']));?></td>
					<td style="text-align:left;padding:0 5px;"><?=$row['name'] ?><br /><?=$row['affiliate'];?></td>
					<td style="text-align:left;padding:0 5px;">
						<?
						if ($row['ptype'] == '2') {
							echo "지점대여";
						} else {
							if($row['rtype'] == 1) {
								echo $row['addr'];
							} else {
								echo $row['addr'] . "<br />" . $row['raddr'];
							}
						}
						?>
					</td>
					<td style='text-align:right;padding-right:5px;'><?=number_format($row['total_account']);?>원</td>
					<td style='width:90px;'><? get_marketdan($row['dan'], $row['extend_payment_idx'], $row['extendyn']); ?></td>
				</tr>
				<?
			}
			?>
			</tbody>
		</table>
		<!-- paging     16.11.07 추가  -->
		<div class="paging">
			<?=paging_admin($page, $total_record, $numper, $page_per_block, $qArr);?>
		</div>
		<!-- //paging -->
	</div>
</div>