<?
/**
 * admin.rentking.co.kr/settlement.php?code=list
 * 관리자페이지 > 정산관리 > 정산내역
 */

$where = '';
if($_SESSION['member_grade']=='10') {
	$where = " AND r.idx = {$ar_rshop['idx']}";
}
$query = "SELECT
		t2.dt,
		COUNT(1) rentshop,
		SUM(t2.count) count,
		SUM(t2.account) account,
		SUM(t2.discount) discount,
		SUM(total) total,
		SUM(total - settlement) commission,
		SUM(settlement) settlement,
		SUM(settlement_finish) settlement_finish
	FROM (
		 SELECT
			 t.dt_settlement dt,
			 t.rentshop,
			 COUNT(1) count,
			 SUM(t.account) account,
			 SUM(t.discount) discount,
			 SUM(t.total) total,
			 SUM(t.settlement) settlement,
			 SUM(CASE
				 WHEN t.account_transfer_idx IS NOT NULL THEN t.settlement
				 ELSE 0
				 END) settlement_finish
		 FROM (
			  SELECT
			  		ma.account - ma.isouts account,
			  		CASE 
			  			WHEN m.dan < 4 AND  ma.turn = 1 THEN m.discount
			  			ELSE 0
					END discount,
				  CASE
					  WHEN m.dan < 4 AND  ma.turn = 1 THEN (ma.account - ma.isouts) + m.discount
					  ELSE ma.account - ma.isouts
				  END total,
				  FLOOR(CASE
						WHEN m.dan < 4 AND  ma.turn = 1 THEN (ma.account - ma.isouts) + m.discount
						ELSE ma.account - ma.isouts
						END * (100 - ma.settlement_per) / 100) settlement,
				  r.idx rentshop,
				  ma.dt_settlement,
				  ma.account_transfer_idx,
				  at.dt_create
			  FROM payment_accounts ma
				  LEFT JOIN payments m ON ma.payment_idx = m.idx
				  LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				  LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
				  LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
				  LEFT JOIN account_transfer at ON ma.account_transfer_idx = at.idx
			  WHERE
				  ma.tbtype = 'I'
				  AND ma.account > ma.isouts
				  AND r.idx IS NOT NULL
				  $where
			  ) t
		 GROUP BY dt, rentshop
		 ) t2
	GROUP BY dt
	ORDER BY dt DESC";
$_SESSION['sql_excel'] = $query;
$r = mysql_query($query);
?>
<?if($_SESSION['member_grade'] != '10') {?>
<div style="text-align:right;margin-top:10px;margin-bottom:10px;">
	<a href="/excel/excel_down.php?act=settlement_list" class="greenBtn_small">EXCEL</a>
</div>
<?} else {?>
	<div style="border:solid 1px #cdd0d5;background:#fff;margin:10px 0;padding:10px;font-size:14px;">
		정산일: 기준일 + 15일 이후 15일 혹은 말일 (기준일 - 단기: 반납일, 장기: 픽업일)<br />
		(정산일이 주말, 공휴일이면 변경이 있을 수 있습니다.)<br />
		멤버사 → 렌트킹 세금계산서 발행시: <strong>미정산금액으로 세금계산서 발행</strong> → 렌트킹 확인 후 입금<br />
		렌트킹 → 멤버사 세금계산서 발행시: 렌트킹에서 (수수료-할인액)으로 세금계산서 발행 → 미정산금액 입금 → <strong>정산예정일별 결제금액을 기타매출로 신고</strong><br />
		렌트킹의 정산금액은 모두 부가세포함금액 입니다.<br />
		<a href="/sites/partials/info/business_license.html?20170731022208" class="blackBtn_small openPopup" data-title="RentKing 사업자등록증" data-width="602" data-padding="0">RentKing 사업자등록증</a>
	</div>
<?}?>
<table class="listTableColor">
	<thead>
	<tr>
		<th>정산예정일</th>
		<?if($_SESSION['member_grade']!='10') {?>
		<th>지점수</th>
		<?}?>
		<th>예약건수</th>
		<th>결제금액</th>
		<th>할인액</th>
		<th>총금액<br />(결제금액 + 할인액)</th>
		<th>수수료</th>
		<th>정산예정금액<br />(총금액 - 수수료)</th>
		<th>정산완료금액</th>
		<th>미정산금액</th>
		<th>상태</th>
	</tr>
	</thead>
	<tbody>
	<?while($row = mysql_fetch_assoc($r)) {
		$link = "/settlement/rentshop.php?dt=".$row['dt'];
		$id = "rentshop_".$row['dt'];
		if($_SESSION['member_grade'] == '10') {
			$link = "/settlement/detail.php?dt=".$row['dt'];
			$id = "detail_".$row['dt'];
		}
		$onclick = "MM_openBrWindow('$link','$id','scrollbars=yes,width=1250,height=700,top=0,left=0');return false;"
		?>
		<tr <?=$row['settlement'] == $row['settlement_finish'] ? '' : ($row['dt'] <= date('Y-m-d') ? 'class="error"' : 'class="primary"') ?>>
			<td><a href="#<?=$row['dt']?>" onclick="<?=$onclick ?>"><?=$row['dt']?></a></td>
			<?if($_SESSION['member_grade']!='10') {?>
			<td><a href="#<?=$row['dt']?>" onclick="<?=$onclick ?>"><?=$row['rentshop']?></a></td>
			<?}?>
			<td><a href="#<?=$row['dt']?>" onclick="<?=$onclick ?>"><?=$row['count']?></a></td>
			<td style="text-align:right;padding:0 5px;"><a href="#<?=$row['dt']?>" onclick="<?=$onclick ?>"><?=number_format($row['account'])?></a></td>
			<td style="text-align:right;padding:0 5px;"><a href="#<?=$row['dt']?>" onclick="<?=$onclick ?>"><?=number_format($row['discount'])?></a></td>
			<td style="text-align:right;padding:0 5px;"><a href="#<?=$row['dt']?>" onclick="<?=$onclick ?>"><?=number_format($row['total'])?></a></td>
			<td style="text-align:right;padding:0 5px;"><a href="#<?=$row['dt']?>" onclick="<?=$onclick ?>"><?=number_format($row['commission'])?></a></td>
			<td style="text-align:right;padding:0 5px;"><a href="#<?=$row['dt']?>" onclick="<?=$onclick ?>"><?=number_format($row['settlement'])?></a></td>
			<td style="text-align:right;padding:0 5px;"><?=number_format($row['settlement_finish'])?></td>
			<td style="font-weight:bold;text-align:right;padding:0 5px;"><?=number_format($row['settlement']-$row['settlement_finish'])?></td>
			<td style="font-weight:bold;"><?=$row['settlement'] == $row['settlement_finish'] ? '정산완료' : '' ?></td>
		</tr>
	<?}?>
	</tbody>
</table>