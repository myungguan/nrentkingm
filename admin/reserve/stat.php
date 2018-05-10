<?
$q = "SELECT * FROM vehicles WHERE dt_delete IS NULL";
if ($_SESSION['member_grade'] == '10') {
	$q = $q . " AND rentshop_idx=" . $ar_rshop['idx'];
}
$r = mysql_query($q);
$isit = mysql_num_rows($r);
?>

<table class="baseTable" style="width:100%;margin-top:10px;">
	<tbody>
	<tr>
		<th class="whiteTh">총등록댓수</th>
		<td colspan="3" class="whiteTd"><?=$isit;?>대</td>
		<th colspan="4" class="whiteTh">* 차량별 대여현황</th>
	</tr>
	<tr>
		<th>날짜</th>
		<th colspan="3">결제</th>
		<th>대여</th>
		<th>대여중</th>
		<th colspan="2">반납</th>
	</tr>
	<?
	$ar_days = array("0" => "일", "1" => "월", "2" => "화", "3" => "수", "4" => "목", "5" => "금", "6" => "토");
	for ($i = 0; $i < 3; $i ++) {
		if ($i == 0) {
			$stddate = date("Y-m-d");
			$stddate1 = date("y/m/d");
			$yo = $ar_days[date("w")];
		} else {
			$stddate = date("Y-m-d", strtotime("-{$i}days"));
			$stddate1 = date("y/m/d", strtotime("-{$i}days"));
			$yo = $ar_days[date("w", strtotime("-{$i}days"))];
		}

		$q = "
						SELECT m.*, mt.retype, ma.account - ma.isouts account
						FROM payment_accounts ma
							LEFT JOIN payments m ON ma.payment_idx = m.idx
							LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
						WHERE
							ma.dt_create BETWEEN '$stddate 00:00:00' AND '$stddate 23:59:59'
							AND ma.tbtype = 'I'
							AND ma.account > ma.isouts
							";
		if ($_SESSION['member_grade'] == '10') {
			$q = $q . " AND mt.vehicle_idx IN (SELECT idx FROM vehicles WHERE rentshop_idx=" . $ar_rshop[idx] . ")";
		}
		$r = mysql_query($q);
		while ($row = mysql_fetch_array($r)) {

			if (!$ar_payment[$i]['total']) {
				$ar_payment[$i]['total'] = 0;
				$ar_payment[$i]['totalac'] = 0;

				$ar_payment[$i]['total1'] = 0;
				$ar_payment[$i]['totalac1'] = 0;

				$ar_payment[$i]['total2'] = 0;
				$ar_payment[$i]['totalac2'] = 0;
			}

			$ar_payment[$i]['total'] += 1;
			$ar_payment[$i]['totalac'] += $row['account'];

			if ($row['retype'] == '1') {
				$ar_payment[$i]['total1'] += 1;
				$ar_payment[$i]['totalac1'] += $row['account'];
			}
			if ($row['retype'] == '2') {
				$ar_payment[$i]['total2'] += 1;
				$ar_payment[$i]['totalac2'] += $row['account'];
			}
		}

		$q = "
						SELECT m.*, mt.retype
						FROM payments m
							LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
						WHERE mt.sdate BETWEEN '$stddate 00:00:00' AND '$stddate 23:59:59'";
		if ($_SESSION['member_grade'] == '10') {
			$q = $q . " AND mt.vehicle_idx IN (SELECT idx FROM vehicles WHERE rentshop_idx=" . $ar_rshop[idx] . ")";
		}
		$r = mysql_query($q);
		while ($row = mysql_fetch_array($r)) {

			if (!$ar_payment[$i]['totald1']) {
				$ar_payment[$i]['totald1'] = 0;
				$ar_payment[$i]['totald11'] = 0;
				$ar_payment[$i]['totald12'] = 0;
			}

			$ar_payment[$i]['totald1'] += 1;

			if ($row['retype'] == '1') {
				$ar_payment[$i]['totald11'] += 1;
			}
			if ($row['retype'] == '2') {
				$ar_payment[$i]['totald12'] += 1;
			}
		}

		$q = "
						SELECT m.*, mt.retype 
						FROM payments m
							LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
						WHERE LEFT(mt.sdate,10)<'$stddate' AND LEFT(mt.edate,10)>'$stddate'";
		if ($_SESSION['member_grade'] == '10') {
			$q = $q . " AND mt.vehicle_idx IN (SELECT idx FROM vehicles WHERE rentshop_idx=" . $ar_rshop[idx] . ")";
		}
		$r = mysql_query($q);
		while ($row = mysql_fetch_array($r)) {

			if (!$ar_payment[$i]['totald2']) {
				$ar_payment[$i]['totald2'] = 0;
				$ar_payment[$i]['totald21'] = 0;
				$ar_payment[$i]['totald22'] = 0;
			}

			$ar_payment[$i]['totald2'] += 1;

			if ($row['retype'] == '1') {
				$ar_payment[$i]['totald21'] += 1;
			}
			if ($row['retype'] == '2') {
				$ar_payment[$i]['totald22'] += 1;
			}
		}

		$q = "
						SELECT m.*, mt.retype, mt.account
						FROM payments m
							LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
						WHERE LEFT(mt.sdate,10)<'$stddate' AND LEFT(mt.edate,10)>'$stddate'";
		if ($_SESSION['member_grade'] == '10') {
			$q = $q . " AND mt.vehicle_idx IN (SELECT idx FROM vehicles WHERE rentshop_idx=" . $ar_rshop[idx] . ")";
		}
		$r = mysql_query($q);
		while ($row = mysql_fetch_array($r)) {

			if (!$ar_payment[$i]['totald3']) {
				$ar_payment[$i]['totald3'] = 0;
				$ar_payment[$i]['totalacd3'] = 0;
				$ar_payment[$i]['totald31'] = 0;
				$ar_payment[$i]['totalacd31'] = 0;
				$ar_payment[$i]['totald32'] = 0;
				$ar_payment[$i]['totalacd32'] = 0;
			}

			$ar_payment[$i]['totald3'] += 1;

			if ($row['retype'] == '1') {
				$ar_payment[$i]['totald31'] += 1;
				$ar_payment[$i]['totalacd31'] += $row['account'];
			}
			if ($row['retype'] == '2') {
				$ar_payment[$i]['totald32'] += 1;
				$ar_payment[$i]['totalacd32'] += $row['account'];
			}
		}
		?>
		<tr>
			<th rowspan="3" class="grayTh" style="padding:0 15px;"><? if ($i == 0) {
					echo "오늘";
				} else if ($i == 1) {
					echo "어제";
				} else {
					echo "그제";
				} ?> <?=$stddate1;?>(<?=$yo;?>)
			</th>
			<td>전체</td>
			<td><?=number_format($ar_payment[$i]['total']);?> 건</td>
			<td style='text-align:right;padding-right:5px;'><?=number_format($ar_payment[$i]['totalac']);?> 원</td>
			<td><?=number_format($ar_payment[$i]['totald1']);?> 건</td>
			<td><?=number_format($ar_payment[$i]['totald2']);?> 건</td>
			<td><?=number_format($ar_payment[$i]['totald3']);?> 건</td>
			<td style='text-align:right;padding-right:5px;'><?=number_format($ar_payment[$i]['totalacd3']);?> 원</td>
		</tr>
		<tr>
			<td>단기</td>
			<td><?=number_format($ar_payment[$i]['total1']);?> 건</td>
			<td style='text-align:right;padding-right:5px;'><?=number_format($ar_payment[$i]['totalac1']);?> 원</td>
			<td><?=number_format($ar_payment[$i]['totald11']);?> 건</td>
			<td><?=number_format($ar_payment[$i]['totald21']);?> 건</td>
			<td><?=number_format($ar_payment[$i]['totald31']);?> 건</td>
			<td style='text-align:right;padding-right:5px;'><?=number_format($ar_payment[$i]['totalacd31']);?> 원</td>
		</tr>
		<tr>
			<td>장기</td>
			<td><?=number_format($ar_payment[$i]['total2']);?> 건</td>
			<td style='text-align:right;padding-right:5px;'><?=number_format($ar_payment[$i]['totalac2']);?> 원</td>
			<td><?=number_format($ar_payment[$i]['totald12']);?> 건</td>
			<td><?=number_format($ar_payment[$i]['totald22']);?> 건</td>
			<td><?=number_format($ar_payment[$i]['totald32']);?> 건</td>
			<td style='text-align:right;padding-right:5px;'><?=number_format($ar_payment[$i]['totalacd32']);?> 원</td>
		</tr>
	<? } ?>
	<?
	$q = "
					SELECT
						count(a.idx) , sum( a.account - a.isouts )
					FROM payment_accounts a
						LEFT JOIN payments m ON a.payment_idx = m.idx
						LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
					WHERE
						LEFT( a.dt_create, 7 ) = '".date("Y-m")."'
						AND a.tbtype = 'I'
						AND a.account > a.isouts";
	if ($_SESSION['member_grade'] == '10') {
		$q = $q . " AND mt.vehicle_idx IN (SELECT idx FROM vehicles WHERE rentshop_idx=" . $ar_rshop[idx] . ")";
	}
	$r = mysql_query($q);
	$row = mysql_fetch_row($r);

	$q1 = "SELECT count( m.idx) FROM payments m LEFT JOIN reservation mt ON m.reservation_idx = mt.idx WHERE LEFT( mt.sdate, 7 ) = '" . date("Y-m") . "' AND m.dan IN ('1','2','3')";
	if ($_SESSION['member_grade'] == '10') {
		$q1 = $q1 . " AND mt.vehicle_idx IN (SELECT idx FROM vehicles WHERE rentshop_idx=" . $ar_rshop[idx] . ")";
	}
	$r1 = mysql_query($q1);
	$row1 = mysql_fetch_row($r1);

	$q2 = "SELECT count( m.idx ) , sum( m.payment_account ) FROM payments m LEFT JOIN reservation mt ON m.reservation_idx = mt.idx WHERE LEFT( mt.edate, 7 ) = '" . date("Y-m") . "' AND m.dan IN ('1','2','3')";
	if ($_SESSION['member_grade'] == '10') {
		$q2 = $q2 . " AND mt.vehicle_idx IN (SELECT idx FROM vehicles WHERE rentshop_idx=" . $ar_rshop[idx] . ")";
	}
	$r2 = mysql_query($q2);
	$row2 = mysql_fetch_row($r2);
	?>
	<tr>
		<th class="grayTh"><?=date("m");?>월 누적건수</th>
		<td>전체</td>
		<td><?=number_format($row[0]);?> 건</td>
		<td style='text-align:right;padding-right:5px;'><?=number_format($row[1]);?> 원</td>
		<td><?=number_format($row1[0]);?> 건</td>
		<td>-</td>
		<td><?=number_format($row2[0]);?> 건</td>
		<td style='text-align:right;padding-right:5px;'><?=number_format($row2[1]);?> 원</td>
	</tr>
	</tbody>
</table>