<?
/**
 * 어드민 > 정산관리 > 정산내역 > 업체별조회
 * admin.rentking.co.kr/settlement/rentshop.php?dt=1981-10-29
 * admin.rentking.co.kr/settlement/rentshop.php?rentshop=10
 * 회원 정보 및 예약 관련 팝업
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

$dt = $_REQUEST['dt'];
$rentshop = $_REQUEST['rentshop'];
$keyword = $_REQUEST['keyword'];

if($mode == 'ajax') {
	$data = array('result' => false);

	$query = "SELECT
				ma.idx,
				FLOOR(CASE
					WHEN ma.turn = 1 THEN (ma.account - ma.isouts) + m.discount
					ELSE ma.account - ma.isouts
				END * (100 - ma.settlement_per) / 100) account,
				ma.payment_idx,
				r.bank,
				r.bankaccount,
				r.bankname,
				r.bankholder
			FROM payment_accounts ma
				LEFT JOIN payments m ON ma.payment_idx = m.idx
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
				LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
			WHERE 
				ma.tbtype = 'I'
				AND ma.account > ma.isouts
				AND ma.dt_settlement='$dt'
				AND ma.account_transfer_idx IS NULL
				AND r.idx=$rentshop";
	$r = mysql_query($query);
	$data['num'] = mysql_num_rows($r);

	$idx = '';

	$amount = 0;
	while($row = mysql_fetch_assoc($r)) {
		if($idx == ''){
			$settlementData = $row;
		}
		if(strlen($idx) > 0)
			$idx .= ',';
		$idx .= $row['idx'];
		$amount += $row['account'];
	}

	$transferInfo = array(
		'infoWithdraw' => $rentshop.'_'.date('Ymd').($data['num'] == 1 ? '_'.$settlementData['idx'] : ''), 	//출금계좌 인자내역
		'bank' => $settlementData['bank'], 						//입금계좌 은행코드
		'bankAccount' => $settlementData['bankaccount'],		//입금계좌 번호
		'bankHolder' => $settlementData['bankholder'],				//입금계좌 예금주 사업자등록번호 or 생년월일(6) + 식별자(1)
		'bankName' => $settlementData['bankname'],					//입금계좌 예금주명
		'infoDeposit' => 'RK_'.($data['num'] == 1 ? $settlementData['payment_idx'] : date('Ymd')),				//입금계좌 인자내역 ( 10글자 이하만 가능하여 payment.idx 미존재시에만 정산날짜 첨부)
		'cms' => '',							//필수값 아님. 이용기관 입장에서 추후 거래를 확인하기 위한 고유번호 개념으로 이용가능. 20바이트까지 가능
		'datetime' => date('YmdHis'),	//요청일시
		'account' => $amount						//금액
	);

	if($command == 'settlement') {

		$result = transferAccount($transferInfo);

		if($result['result'] == 'SUCCESS'){

			$value['bank'] = $transferInfo['bank'];
			$value['bankaccount'] = $transferInfo['bankAccount'];
			$value['bankname'] = $transferInfo['bankName'];
			$value['bankholder'] = $transferInfo['bankHolder'];
			$value['account'] = $transferInfo['account'];
			$value['info_withdraw'] = $transferInfo['infoWithdraw'];
			$value['info_deposit'] = $transferInfo['infoDeposit'];
			$value['rawdata'] = json_encode($result['data']);
			$value['dt_create'] = date("Y-m-d H:i:s");
			insert("account_transfer", $value);

			$query = "UPDATE payment_accounts SET account_transfer_idx = (SELECT MAX(idx) FROM account_transfer) WHERE idx IN ($idx)";
			$r = mysql_query($query);
			$data['result'] = true;
		}else{
			$data = $result;
			$data['result'] = false;
		}

	}else if($command == 'settlement_direct')
	{
		$query = "INSERT INTO account_transfer(bank, bankaccount, bankname, bankholder, account, info_withdraw, info_deposit, rawdata, dt_create) 
				VALUES('{$transferInfo['bank']}', '{$transferInfo['bankAccount']}', '{$transferInfo['bankName']}', '{$transferInfo['bankHolder']}', '{$transferInfo['account']}', null, null, null, now())";
		$r = mysql_query($query);

		$query = "UPDATE payment_accounts SET account_transfer_idx = (SELECT MAX(idx) FROM account_transfer) WHERE idx IN ($idx)";
		$r = mysql_query($query);
		$data['result'] = true;
	}

	echo json_encode($data);
	exit;
}

if (!$dt && !$rentshop) {
	echo "<script>alert('잘못된 접근입니다.'); window.close(); </script>";
	exit;
}

if($dt) {
	$where = '';
	if($keyword) {
		$k = mysql_escape_string($keyword);
		$where .= " AND (r.name LIKE '%$k%' OR r.affiliate LIKE '%$k%')";
	}
	$query = "SELECT
			t.dt_settlement dt,
			t.rentshop_idx,
			t.rentshop,
			t.bank,
			t.bankaccount,
			t.bankname,
			t.bankholder,
			t.businessnum,
			t.dname2,
			t.dcp2,
			t.dname3,
			t.dcp3,
			t.bank_transfer_status,
			COUNT(1) count,
			SUM(t.account) account,
			SUM(t.discount) discount,
			SUM(t.total) total,
			SUM(t.total - t.settlement) commission,
			SUM(t.settlement) settlement,
			SUM(CASE
				WHEN t.account_transfer_idx IS NOT NULL THEN t.settlement
				ELSE 0
				END) settlement_finish,
			SUM(t.settlement - CASE
				WHEN t.account_transfer_idx IS NOT NULL THEN t.settlement
				ELSE 0
				END) settlement_no
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
				r.idx rentshop_idx,
				CONCAT(r.name, '(', r.affiliate, ')') rentshop,
				r.bank,
				r.bankaccount,
				r.bankname,
				r.bankholder,
				r.businessnum,
				r.dname2,
				r.dcp2,
				r.dname3,
				r.dcp3,
				r.bank_transfer_status,
				ma.dt_settlement,
				ma.account_transfer_idx
			FROM payment_accounts ma
				LEFT JOIN payments m ON ma.payment_idx = m.idx
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
				LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
			WHERE
				ma.tbtype = 'I'
				AND ma.account > ma.isouts
				AND ma.dt_settlement='$dt'
				AND r.idx IS NOT NULL
				$where
			 ) t
		GROUP BY rentshop_idx
		ORDER BY settlement_no DESC, settlement DESC";
} else if($rentshop) {
	$query = "SELECT r.idx, CONCAT(r.name, '(', r.affiliate, ')') name, r.bank, r.bankaccount, r.bankname, r.bankholder, r.bank_transfer_status FROM rentshop r
			WHERE r.idx = $rentshop";
	$rentshopInfo = mysql_fetch_assoc(mysql_query($query));

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
			t.rentshop_idx,
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
				  r.idx rentshop_idx,
				  ma.dt_settlement,
				  ma.account_transfer_idx
			  FROM payment_accounts ma
				  LEFT JOIN payments m ON ma.payment_idx = m.idx
				  LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				  LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
				  LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
			  WHERE
				  ma.tbtype = 'I'
				  AND ma.account > ma.isouts
				  AND r.idx = $rentshop
			  ) t
		 GROUP BY dt, rentshop_idx
		 ) t2
	GROUP BY dt
	ORDER BY dt DESC";
}
$_SESSION['sql_excel'] = $query;
$result = mysql_query($query);


include "../adminhead.php";
?>
</head>
<body>
<div id="pop_contents">
	<form id="ajaxForm" action="/settlement/rentshop.php">
		<input type="hidden" name="rentshop" value="" />
		<input type="hidden" name="dt" value="" />
		<input type="hidden" name="mode" value="ajax" />
		<input type="hidden" name="command" value="" />
	</form>
	<div style="margin-bottom:20px;">
		<?if($dt) {?>
			<form id="listForm" action="/settlement/rentshop.php">
				<h3 style="display:inline-block;margin:0;"><?=$dt ?> 정산내역</h3>
				<input type="hidden" name="dt" value="<?=$dt?>" />
				<input type="text" name="keyword" value="<?=$keyword?>" placeholder="회사명, 지점" style="padding:0 5px;"/>
				<button type="submit" class="greenBtn_small">검색</button>
				<?if($_SESSION['member_grade'] != '10') {?>
					<a href="/excel/excel_down.php?act=settlement_rentshop&dt=<?=$dt?>" class="greenBtn_small" style="float:right;">EXCEL</a>
				<?}?>

			</form>
		<?}?>

		<?if($rentshop) {?>
			<h3 style="display:inline-block;margin:0;"><?=$rentshopInfo['name'] ?> - <?=getBankName($rentshopInfo['bank'])?> <?=$rentshopInfo['bankaccount']?>(<?=$rentshopInfo['bankname']?>)</h3>
			<span class="greenBtn" style="float:right;"><a href="/excel/excel_down.php?act=settlement_rentshop&rentshop=<?=$rentshop?>">EXCEL</a></span>
		<?}?>
	</div>


	<table class="listTableColor">
		<thead>
		<tr>
			<?if($dt) {?>
				<th>회사명(지점)</th>
				<th>(은행명) 계좌번호<br />예금주</th>
			<?}?>
			<?if($rentshop){?>
				<th>정산예정일</th>
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
		<?while($row = mysql_fetch_assoc($result)) {
			$detailDt = '';
			$detailRentshop = '';
			$detailBankTransferStatus = '';
			if($dt) {
				$detailDt = $dt;
				$detailRentshop = $row['rentshop_idx'];
				$detailBankTransferStatus = $row['bank_transfer_status'];
			}
			if($rentshop) {
				$detailDt = $row['dt'];
				$detailRentshop = $rentshop;
				$detailBankTransferStatus = $rentshopInfo['bank_transfer_status'];
			}
			$onclick = "MM_openBrWindow('/settlement/detail.php?dt=$detailDt&rentshop=$detailRentshop','detail_{$detailDt}_{$detailRentshop}','scrollbars=yes,width=1250,height=700,top=0,left=0');return false;";
			?>
			<tr <?=$row['settlement'] == $row['settlement_finish'] ? '' : ($row['dt'] <= date('Y-m-d') ? 'class="error"' : 'class="primary"') ?>>
				<?if($dt) {?>
					<td style="text-align:left;padding:0 5px;"><a href="#<?=$detailRentshop?>" onclick="MM_openBrWindow('/settlement/rentshop.php?rentshop=<?=$detailRentshop?>','rentshop_<?=$detailRentshop ?>','scrollbars=yes,width=1250,height=700,top=0,left=0');return false;"><?=$row['rentshop'] ?></a></td>
					<td style="text-align:left;padding:0 5px;">(<?=getBankName($row['bank']) ?>) <?=$row['bankaccount'] ?><br /><?=$row['bankname'] ?></td>
				<?}?>
				<?if($rentshop){?>
					<td><a href="#<?=$detailDt?>" onclick="<?=$onclick?>"><?=$detailDt ?></a></td>
				<?}?>
				<td><a href="#<?=$detailDt?>" onclick="<?=$onclick?>"><?=$row['count'] ?></a></td>
				<td style="text-align:right;padding:0 5px;"><a href="#<?=$detailDt?>" onclick="<?=$onclick?>"><?=number_format($row['account']) ?></a></td>
				<td style="text-align:right;padding:0 5px;"><a href="#<?=$detailDt?>" onclick="<?=$onclick?>"><?=number_format($row['discount']) ?></a></td>
				<td style="text-align:right;padding:0 5px;"><a href="#<?=$detailDt?>" onclick="<?=$onclick?>"><?=number_format($row['total']) ?></a></td>
				<td style="text-align:right;padding:0 5px;"><a href="#<?=$detailDt?>" onclick="<?=$onclick?>"><?=number_format($row['commission']) ?></a></td>
				<td style="text-align:right;padding:0 5px;"><a class="settlement_todo" href="#<?=$detailDt?>" onclick="<?=$onclick?>"><?=number_format($row['settlement']) ?></a></td>
				<td class="settlement_finish" style="text-align:right;padding:0 5px;"><?=number_format($row['settlement_finish']) ?></td>
				<td class="settlement_none" style="font-weight:bold;text-align:right;padding:0 5px;"><?=number_format($row['settlement']-$row['settlement_finish'])?></td>
				<td style="font-weight:bold;white-space:nowrap">
					<span class="status"><?=$row['settlement'] == $row['settlement_finish'] ? '정산완료' : '' ?></span>
					<?if($_SESSION['member_grade']!='10') {?>
						<?
						$settlementDate = strtotime('-5 days', strtotime($detailDt));
						$canSettlement = $settlementDate <= time();
						if($row['settlement'] != $row['settlement_finish'] && $canSettlement) {?>
							<button type="button" class="blackBtn_small btnSettlement" data-rentshop="<?=$detailRentshop ?>" data-dt="<?=$detailDt?>" data-bank-transfer-status="<?=$detailBankTransferStatus?>">정산</button>
						<?}?>
					<?}?>
				</td>
			</tr>
		<?}?>
		</tbody>
	</table>
</div><!-- // .content -->

<script type="text/javascript">
	$(document)
		.on('click', '.btnSettlement', function(e) {
			var $btn = $(this);
			if($btn.data('bankTransferStatus') == 'Y'){
				if(confirm('자동이체 정산을 진행 하시겠습니까?')) {
					var rentshop = $btn.data('rentshop');
					var dt = $btn.data('dt');
					var $ajaxForm = $('#ajaxForm');
					var $tr = $btn.parents('tr');

					$ajaxForm.find('input[name="rentshop"]').val(rentshop);
					$ajaxForm.find('input[name="dt"]').val(dt);
					$ajaxForm.find('input[name="command"]').val('settlement');

					$.getJSON($ajaxForm.attr('action'), $ajaxForm.serializeArray(), function(data) {
						if(data.result) {
							alert(data['num']+'건이 정산처리 되었습니다.');
							$tr.find('.settlement_finish').text($tr.find('.settlement_todo').text());
							$tr.find('.settlement_none').text(0);
							$tr.find('.status').text('정산완료');
							$tr.removeClass('primary error');
							$btn.hide();
						} else {
							if(data.msg) {
								if(confirm('정산이체를 실행하는 중에 오류가 발생하였습니다.\n\n오류내용:\n'+data.msg+'\n\n직접 이체를 진행하여 정산 완료를 하고싶은 경우 확인을 눌러주세요.')){
									directTransfer($btn);
								}
							}
						}
					})
				}
			}else{
				if(confirm('자동이체 정산을 미지원하는 은행계좌이거나 계좌정보가 정확하지 않은 가맹점입니다.\n수동 이체 후 정산 처리를 진행하고 싶으시면 확인을 눌러주세요.')) {
					directTransfer($btn);
				}
			}

		});


	function directTransfer($btn){
		var rentshop = $btn.data('rentshop');
		var dt = $btn.data('dt');
		var $ajaxForm = $('#ajaxForm');
		var $tr = $btn.parents('tr');

		$ajaxForm.find('input[name="rentshop"]').val(rentshop);
		$ajaxForm.find('input[name="dt"]').val(dt);
		$ajaxForm.find('input[name="command"]').val('settlement_direct');

		$.getJSON($ajaxForm.attr('action'), $ajaxForm.serializeArray(), function(data) {
			if (data.result) {
				alert(data['num'] + '건이 정산처리 되었습니다.');
				$tr.find('.settlement_finish').text($tr.find('.settlement_todo').text());
				$tr.find('.settlement_none').text(0);
				$tr.find('.status').text('정산완료');
				$tr.removeClass('primary error');
				$btn.hide();
			}
		});
	}
</script>
</body>