<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config.php";
include "../adminhead.php";

$idx = $_REQUEST['idx'];
$allat_enc_data = $_REQUEST['allat_enc_data'];

$query = "
	SELECT
		m.*,
		mt.vehicle_idx,
		mt.retype,
		mt.pricetype,
		mt.ddata1,
		mt.ddata2,
		mt.rtype,
		mt.sdate,
		mt.edate,
		mt.account,
		mt.account1,
		mt.account2,
		mt.preaccount,
		mt.insu,
		mt.insuac,
		mt.addr,
		mt.raddr
	FROM payments m
		LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
	WHERE
		m.idx = $idx
";
$order = mysql_fetch_assoc(mysql_query($query));

$mode = $_REQUEST['mode'];
$query = "SELECT * FROM payment_accounts WHERE payment_idx = $idx AND tbtype='I' ORDER BY dt_create DESC LIMIT 0,1";
$account = mysql_fetch_assoc(mysql_query($query));
$lastRawdata = $account['rawdata'];
$accountReturnable = $account['account'] - $account['isouts'];
if($account['account_transfer_idx']) {
	$accountReturnable = 0;
}

if (!$idx) {
	echo "<script>alert('잘못된 접근입니다.'); window.close(); </script>";
	exit;
}

?>
<div id="pop_contents">
	<span class="subTitle">* 취소처리</span>
	<form name="cancelForm" id="cancelForm" action="/sites/payment/allat_cancel_form.php" method="post">
		<input type="hidden" name="accountReturnFee" value="0" />
		<input type="hidden" name="order_idx" value="<?=$order['idx'] ?>" />
		<input type="hidden" name="allat_enc_data" value="" />
		<table class="detailTable2">
			<?
			if ($order['pdan'] != '') {
				?>
				<tr>
					<th>취소요청</th>
					<td><? if ($order['pdan'] == '1') {
							echo "고객";
						} else {
							echo "지점";
						} ?></td>
					<th>취소요청일</th>
					<td><?=$order['dt_cancelr'];?></td>
				</tr>
				<?
			}
			?>
			<tr>
				<th>픽업예정</th>
				<td><?=$order['sdate'];?></td>
				<th>현재시간</th>
				<td><?=date("Y-m-d H:i:s");?></td>
			</tr>
			<tr>
				<th>환불가능금액</th>
				<td><?=number_format($accountReturnable) ?>원 <?=$account['account_transfer_idx'] ? '(정산완료)' : '' ?></td>
				<th>수수료</th>
				<td><input type="text" name="accountFee" data-max="100" class="inputNumber" value="0" />%</td>
			</tr>
			<tr>
				<th>환불금액</th>
				<td colspan="3">
					<input type="text" name="accountReturn" data-max="<?=$accountReturnable?>" class="inputNumber" value="<?=$accountReturnable?>">원<br />
					(수수료 + 환불금액)이 환불 가능금액 이상이면 예약이 취소됩니다.
				</td>
			</tr>
		</table>
		<div class="topBtn" style='text-align:center;'>
			<?if(!$account['account_transfer_idx']) {?>
				<button type="submit" class="greenBtn" id="btnCancel">예약취소</button>
			<?}?>
		</div>
	</form>
</div><!-- // .content -->

<div id="paymentForm"></div>
<script type="text/javascript" charset="EUC-KR" src="https://tx.allatpay.com/common/NonAllatPayREPlus.js"></script>
<script type="text/javascript">
	$(function() {
		$(document)
			.on('submit', '#cancelForm', function(e) {
				var $cancelForm = $(this);

				if(!$cancelForm.data('payment')) {
					var $accountReturn = $('input[name="accountReturn"]');
					var accountReturnable = parseInt($accountReturn.data('max'));
					var accountReturn = parseInt($accountReturn.val().replace(/,/g, ''));
					var $paymentForm = $('#paymentForm');
					if(accountReturn < 1000) {
						alert('1000원 이하는 환불할 수 없습니다.');
						return false;
					}

					if(accountReturn > accountReturnable) {
						alert('최대 환불 가능한 금액은 ' + accountReturnable.numberFormat(0) + '원 입니다.');
						return false;
					}

					var running = $cancelForm.data('running');
					if(!running && confirm('취소처리 하시겠습니까?')) {
						$.post($cancelForm.attr('action'), $cancelForm.serializeArray())
							.done(function(html) {
								$paymentForm.html(html);
							})
					}


					return false;
				}
			})
			.on('input', 'input[name="accountFee"]', function(e) {
				var $accountFee = $(this);
				var $accountReturn = $('input[name="accountReturn"]');
				var $accountReturnFee = $('input[name="accountReturnFee"]');

				var fee = parseInt($accountFee.val());
				if(isNaN(fee))	fee = 0;
				var accountReturnable = parseInt($accountReturn.data('max'));
				var accountReturnFee = Math.floor(accountReturnable * fee / 100);
				var accountReturn = accountReturnable - accountReturnFee;

				$accountReturn.val(accountReturn.numberFormat(0));
				$accountReturnFee.val(accountReturnFee);

				$('#btnCancel').text('예약취소');
			})
			.on('input', 'input[name="accountReturn"]', function(e) {
				var $accountReturn = $('input[name="accountReturn"]');
				var accountReturnable = parseInt($accountReturn.data('max'));

				$('input[name="accountReturnFee"]').val(0);
				$('input[name="accountFee"]').val(0);

				var accountReturn = parseInt($accountReturn.val().replace(',', ''));
				if(isNaN(accountReturn)) {
					accountReturn = 0;
				}

				if(accountReturn >= accountReturnable) {
					$('#btnCancel').text('예약취소');
				} else {
					$('#btnCancel').text('환불');
				}

			})
	});
</script>
