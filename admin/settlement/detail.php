<?
/**
 * 어드민 > 정산관리 > 정산내역 > 업체별조회
 * admin.rentking.co.kr/settlement/rentshop.php?dt=1981-10-29
 * 회원 정보 및 예약 관련 팝업
 */
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";

$dt = $_REQUEST['dt'];
$rentshop = $_REQUEST['rentshop'];
if($_SESSION['member_grade']=='10') {
	$rentshop = $ar_rshop['idx'];
}

if($mode == 'ajax') {
	$data = array('result' => false);

	$idx = $_REQUEST['idx'];
	$query = "SELECT
				FLOOR(CASE
					WHEN pa.turn = 1 THEN (pa.account - pa.isouts) + p.discount
					ELSE pa.account - pa.isouts
				END * (100 - pa.settlement_per) / 100) account,
				pa.payment_idx,
				pa.account_transfer_idx,
				r2.bank,
				r2.bankaccount,
				r2.bankname,
				r2.bankholder,
				at.dt_create
			FROM payment_accounts pa
				LEFT JOIN payments p ON pa.payment_idx = p.idx
				LEFT JOIN reservation r ON p.reservation_idx = r.idx
				LEFT JOIN vehicles v ON r.vehicle_idx = v.idx
				LEFT JOIN rentshop r2 ON v.rentshop_idx = r2.idx
				LEFT JOIN account_transfer at ON pa.account_transfer_idx = at.idx
			WHERE pa.idx = $idx";

	$r = mysql_query($query);
	if(mysql_num_rows($r) > 0) {
		$account = mysql_fetch_assoc($r);

		if($account['account_transfer_idx']) {
			$data['msg'] = '이미 정산 되었습니다.';
		} else {

			$transferInfo = array(
				'infoWithdraw' => $rentshop.'_'.date('Ymd').'_'.$idx, 	//출금계좌 인자내역
				'bank' => $account['bank'], 						//입금계좌 은행코드
				'bankAccount' => $account['bankaccount'],		//입금계좌 번호
				'bankHolder' => $account['bankholder'],				//입금계좌 예금주 사업자등록번호 or 생년월일(6) + 식별자(1)
				'bankName' => $account['bankname'],					//입금계좌 예금주명
				'infoDeposit' => 'RK_'.date('Ymd'),				//입금계좌 인자내역 ( 10글자 이하만 가능하여 payment.idx 미존재시에만 정산날짜 첨부)
				'cms' => '',							//필수값 아님. 이용기관 입장에서 추후 거래를 확인하기 위한 고유번호 개념으로 이용가능. 20바이트까지 가능
				'datetime' => date('YmdHis'),	//요청일시
				'account' => $account['account']						//금액
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
			}else if($command == 'settlement_direct') {
				$query = "INSERT INTO account_transfer(bank, bankaccount, bankname, bankholder, account, info_withdraw, info_deposit, rawdata, dt_create) 
				VALUES('{$transferInfo['bank']}', '{$transferInfo['bankAccount']}', '{$transferInfo['bankName']}', '{$transferInfo['bankHolder']}', '{$transferInfo['account']}', null, null, null, now())";
				$r = mysql_query($query);

				$query = "UPDATE payment_accounts SET account_transfer_idx = (SELECT MAX(idx) FROM account_transfer) WHERE idx IN ($idx)";
				$r = mysql_query($query);
				$data['result'] = true;
			}
			$account = mysql_fetch_assoc(mysql_query("SELECT * FROM payment_accounts WHERE idx = $idx"));
			$data['account'] = $account;
		}
	}

	echo json_encode($data);
	exit;
}

if (!$dt || !$rentshop) {
	echo "<script>alert('잘못된 접근입니다.'); window.close(); </script>";
	exit;
}

$query = "SELECT CONCAT(r.name, '(', r.affiliate, ')') name FROM rentshop r
	WHERE r.idx = $rentshop";
$r = mysql_query($query);
$rentshopName = mysql_fetch_assoc($r)['name'];

$query = "SELECT
		t.*,
		t.total - t.settlement commission
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
			ma.settlement_per,
			r.idx rentshop_idx,
			r.bank,
			r.bankaccount,
			r.bankname,
			r.bank_transfer_status,
			ma.dt_settlement,
			ma.account_transfer_idx,
			m.idx payment_idx,
			mt.sdate,
			mt.edate,
			vs.name modelname,
			v.carnum,
			ma.idx payment_account_idx,
			at.dt_create
		FROM payment_accounts ma
			LEFT JOIN payments m ON ma.payment_idx = m.idx
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
			LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
			LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
			LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
			LEFT JOIN account_transfer at ON ma.account_transfer_idx = at.idx
		WHERE
			ma.tbtype = 'I'
			AND ma.account > ma.isouts
			AND ma.dt_settlement='$dt'
			AND r.idx = $rentshop
		ORDER BY payment_account_idx DESC
	) t";
$_SESSION['sql_excel'] = $query;
$result = mysql_query($query);

include "../adminhead.php";
?>
</head>
<body>
<div id="pop_contents">
	<form id="ajaxForm" action="/settlement/detail.php">
		<input type="hidden" name="mode" value="ajax" />
		<input type="hidden" name="command" value="" />
	</form>
	<div style="margin-bottom:20px;">
		<h3 style="display:inline-block;margin:0;"><?=$dt ?> <?=$rentshopName ?></h3>
		<a href="/excel/excel_down.php?act=settlement_detail&dt=<?=$dt?>&rentshop=<?=$rentshopName ?>" class="greenBtn_small" style="float:right;">EXCEL</a>
	</div>

	<?if($_SESSION['member_grade'] == '10') {?>
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
			<th>예약번호</th>
			<th>대여일시<br />반납일시</th>
			<th>차종</th>
			<th>결제금액</th>
			<th>할인액</th>
			<th>총금액<br />(결제금액 + 할인액)</th>
			<th>수수료</th>
			<th>정산예정금액<br />(총금액 - 수수료)</th>
			<th>정산일시</th>
			<th>상태</th>
		</tr>
		</thead>
		<tbody>
		<?while($row = mysql_fetch_assoc($result)) {?>
			<tr <?=$row['account_transfer_idx'] ? '' : ($row['dt_settlement'] <= date('Y-m-d') ? 'class="error"' : 'class="primary"') ?>>
				<td><a href="#<?=$row['payment_idx'];?>" onclick="MM_openBrWindow('/popup/orderview.php?idx=<?=$row['payment_idx'];?>','order<?=$row['payment_idx'];?>','scrollbars=yes,width=1150,height=700,top=0,left=0');return false;"><?=$row['payment_idx'] ?></a></td>
				<td><?=$row['sdate'] ?><br /><?=$row['edate'] ?></td>
				<td style="text-align:left;padding:0 5px;"><?=$row['modelname'] ?><br />[<?=$row['carnum'] ?>]</td>
				<td style="text-align:right;padding:0 5px;"><?=number_format($row['account']) ?></td>
				<td style="text-align:right;padding:0 5px;"><?=number_format($row['discount']) ?></td>
				<td style="text-align:right;padding:0 5px;"><?=number_format($row['total']) ?></td>
				<td style="text-align:right;padding:0 5px;"><?=number_format($row['commission']) ?>(<?=number_format($row['settlement_per'])?>%)</td>
				<td style="text-align:right;padding:0 5px;"><?=number_format($row['settlement']) ?></td>
				<td class="dt_settlement_finish"><?=$row['dt_create'] ?></td>
				<td style="font-weight:bold;white-space:nowrap">
					<span class="status"><?=$row['account_transfer_idx'] ? '정산완료' : '' ?></span>
					<?if($_SESSION['member_grade']!='10') {?>
						<?
						$settlementDate = strtotime('-5 days', strtotime($row['dt_settlement']));
						$canSettlement = $settlementDate <= time();
						if(!$row['account_transfer_idx'] && $canSettlement) {?>
						<button type="button" class="blackBtn_small btnSettlement" data-idx="<?=$row['payment_account_idx'] ?>" data-bank-transfer-status="<?=$row['bank_transfer_status']?>">정산</button>
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
					var idx = $btn.data('idx');
					var $ajaxForm = $('#ajaxForm');
					var $tr = $btn.parents('tr');

					$ajaxForm.find('input[name="command"]').val('settlement');
					var $idx = $ajaxForm.find('input[name="idx"]');
					if($idx.length < 1) {
						$idx = $('<input type="hidden" name="idx" value="" />');
						$idx.appendTo($ajaxForm);
					}
					$idx.val(idx);

					$.getJSON($ajaxForm.attr('action'), $ajaxForm.serializeArray(), function(data) {
						if(data.result) {
							$tr.find('.dt_settlement_finish').text(data['account']['dt_create']);
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
		var $ajaxForm = $('#ajaxForm');
		var $tr = $btn.parents('tr');
		var idx = $btn.data('idx');

		$ajaxForm.find('input[name="command"]').val('settlement_direct');
		var $idx = $ajaxForm.find('input[name="idx"]');
		if($idx.length < 1) {
			$idx = $('<input type="hidden" name="idx" value="" />');
			$idx.appendTo($ajaxForm);
		}
		$idx.val(idx);

		$.getJSON($ajaxForm.attr('action'), $ajaxForm.serializeArray(), function(data) {
			if (data.result) {
				$tr.find('.dt_settlement_finish').text(data['account']['dt_create']);
				$tr.find('.status').text('정산완료');
				$tr.removeClass('primary error');
				$btn.hide();
				alert('정산처리가 완료되었습니다.');
			}
		});
	}
</script>
</body>