<?
/**
 * admin.rentking.co.kr/settlement.php?code=linkprice
 * 관리자페이지 > 정산관리 > 링크프라이스
 */

$query = "SELECT
		m.idx payment_idx,
		l.lpinfo,
		l.order_code,
		l.product_code,
		mem.name mem_name,
		CASE
			WHEN mt.retype = 1 THEN '단기'
			WHEN mt.retype = 2 THEN '장기'
		END retype,
		m.dt_create,
		mt.sdate,
		mt.edate,
		CONCAT(r..name, '(', r.affiliate, ')') rentshop,
		CASE
			WHEN m.dan = 1 THEN '예약확정'
			WHEN m.dan = 2 THEN '대여중'
			WHEN m.dan = 3 THEN '반납'
			WHEN m.dan = 4 THEN '취소요청'
		END status,
		l.price
	FROM
		linkprice l
		LEFT JOIN reservation mt ON l.order_code = mt.idx
		LEFT JOIN payments m ON mt.idx = m.reservation_idx
		LEFT JOIN member mem ON m.member_idx = mem.idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
	WHERE
		m.idx IS NOT NULL
		AND m.dan < 5
	ORDER BY dt_create DESC";
$_SESSION['sql_excel'] = $query;
$r = mysql_query($query);

?>
<div style="text-align:right;margin-top:10px;margin-bottom:10px;">
	<a href="/excel/excel_down.php?act=settlement_linkprice" class="greenBtn_small">EXCEL</a>
</div>
<table class="listTableColor">
	<thead>
	<tr>
		<th>예약번호</th>
		<th>주문코드</th>
		<th>상품코드</th>
		<th>고객명</th>
		<th>예약구분</th>
		<th>예약일시</th>
		<th>픽업일시</th>
		<th>반납일시</th>
		<th>대여지점</th>
		<th>상태</th>
		<th>결제금액</th>
	</tr>
	</thead>
	<tbody>
	<?while($row = mysql_fetch_assoc($r)) {?>
		<tr>
			<td><a href="#<?=$row['payment_idx'];?>" onclick="MM_openBrWindow('/popup/orderview.php?idx=<?=$row['payment_idx'];?>','order<?=$row['payment_idx'];?>','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;"><?=$row['payment_idx'] ?></a></td>
			<td><?=$row['order_code']?></td>
			<td><?=$row['product_code']?></td>
			<td><?=$row['mem_name'] ?></td>
			<td><?=$row['retype'] ?></td>
			<td><?=$row['dt_create'] ?></td>
			<td><?=$row['sdate'] ?></td>
			<td><?=$row['edate'] ?></td>
			<td style="text-align:left;padding:0 5px;"><?=$row['rentshop'] ?></td>
			<td><?=$row['status'] ?></td>
			<td style="text-align:right;padding:0 5px;"><?=number_format($row['price']) ?></td>
		</tr>
	<?}?>
	</tbody>
</table>