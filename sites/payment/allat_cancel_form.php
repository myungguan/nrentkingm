<?
/**
 * 차량 검색 페이지
 * www.rentking.co.kr/rent/search.php
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include_once $config['incPath']."/connect.php";
include_once  $config['incPath']."/session.php";
include_once  $config['incPath']."/config_site.php";

$order_idx = isset($_REQUEST['order_idx']) ? $_REQUEST['order_idx'] : $order_idx;
$query = "SELECT * FROM payment_accounts WHERE payment_idx = $order_idx AND tbtype='I' ORDER BY dt_create DESC LIMIT 0,1";
$account = mysql_fetch_assoc(mysql_query($query));
$accountReturn = $account['account'] - $account['isouts'];

$accountReturn = isset($_REQUEST['accountReturn']) && $_REQUEST['accountReturn'] != '' ? str_replace(',', '', $_REQUEST['accountReturn']) : $accountReturn;

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
		m.idx = $order_idx
";
$order = mysql_fetch_assoc(mysql_query($query));

$query = "SELECT * FROM payment_accounts WHERE payment_idx = $order_idx AND tbtype = 'I' ORDER BY dt_create DESC LIMIT 0, 1";
$account = mysql_fetch_assoc(mysql_query($query));

$rawdata = explode('|R|', $account['rawdata']);

if(!$config['production'] && strpos($account['order_no'], 'dev') === false)
	$config['allatTest'] = 'Y';

if($account['buymethod'] == 'C') {?>
	<form name="allatPayment" id="allatPayment" method="post">
		<!-- 필수 정보 -->
		<input type=hidden name="allat_enc_data" value="">
		<input type="hidden" placeholder="CROSSKEY"				name="test_cross_key" value="<?=$config['allatCrossKey'][2] ?>" size="19" maxlength="200">
		<input type="hidden" placeholder="상점 ID"				name="allat_shop_id" value="<?=$config['allatShopId'][2] ?>" size="19" maxlength="20">
		<input type="hidden" placeholder="주문번호"				name="allat_order_no" value="<?=$account['order_no'] ?>" size="19" maxlength="80">
		<input type="hidden" placeholder="승인금액"				name="allat_amt" value="<?=$accountReturn?>" size="19" maxlength="10">
		<input type="hidden" placeholder="원거래건의 결제방식"	name="allat_pay_type" value="<?=$rawdata[0]?>" size="19" maxlength="6">
		<input type="hidden" placeholder="인증정보수신URL"		name="shop_receive_url" value="<?=$config['scheme']?>://<?=$_SERVER['HTTP_HOST']?>/sites/payment/allat_receive.php" size="19">
		<input type="hidden" placeholder="올앳참조필드"			name="allat_opt_pin" value="NOUSE" size="19">
		<input type="hidden" placeholder="올앳참조필드"			name="allat_opt_mod" value="APP" size="19">
		<!-- 필수 정보 -->

		<!-- 옵션 정보 -->
		<input type="hidden" placeholder="거래일련번호"	name="allat_seq_no" value="" size="19" maxlength="10">
		<input type="hidden" placeholder="테스트 여부"	name="allat_test_yn"		value="<?=$config['production'] ? 'N' : $config['allatTest'] ?>" size="19" maxlength="1">
		<!-- 옵션 정보 -->

		<input type="hidden" name="buymethod" value="<?=$account['buymethod']?>" />
	</form>
	<script type="text/javascript">
		function result_submit(resultCd, resultMsg, encData) {
			$('#allatPayment').remove();
			var $cancelForm = $('#cancelForm');
			if(resultCd != '0000') {
				window.setTimeout(function(){alert(resultMsg);},1000);
			} else {
				$cancelForm.find('input[name="allat_enc_data"]').val(encData);
				var $accountReturn = $cancelForm.find('input[name="accountReturn"]');
				if($accountReturn.val() == '') {
					$accountReturn.val(<?=$accountReturn ?>);
				}

				$cancelForm.attr('action', '/sites/payment/allat_cancel.php').data('payment', true).submit();
			}
		}
		Allat_Plus_Api(allatPayment);
	</script>
<?} else if($account['buymethod'] == 'F') {?>
	<script type="text/javascript">
		var $cancelForm = $('#cancelForm');
		var $accountReturn = $cancelForm.find('input[name="accountReturn"]');
		if($accountReturn.val() == '') {
			$accountReturn.val(<?=$accountReturn ?>);
		}
		$cancelForm.attr('action', '/sites/payment/allat_cancel.php').data('payment', true).submit();
	</script>
<?}?>
