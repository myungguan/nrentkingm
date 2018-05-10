<?
/**
 * 차량 검색 페이지
 * www.rentking.co.kr/rent/search.php
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$order_idx = $_REQUEST['order_idx'];
$buymethod = $_REQUEST['buymethod'];
$ptype = $_REQUEST['ptype'];
$payType = $_REQUEST['payType'];
$coupon = $_REQUEST['coupon'];
$mobile = isset($_REQUEST['mobile']) && $_REQUEST['mobile'] == 'Y' ? true : false;

$query = "SELECT * FROM reservation WHERE idx = $order_idx";
$result = mysql_query($query);
if(mysql_num_rows($result) < 1) {
	$msg = '예약정보가 없습니다.';
	logError($msg);
	echo '<script type="text/javascript">alert("' . $msg. '");location.replace("/rent/search.php");</script>';
	exit;
}
$order = mysql_fetch_assoc($result);

//연장일 경우
$extend_order = NULL;
if($order['extend'] == 'Y') {
	$query = "SELECT
			m.*
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		WHERE
			mt.edate = '{$order['sdate']}'
			AND mt.vehicle_idx = {$order['vehicle_idx']}
			AND m.member_idx = $g_memidx
		ORDER BY m.idx DESC
		limit 1
			";
	$r = mysql_query($query);
	if(mysql_num_rows($r) != 1) {
		$msg = '잘못된 예약 정보 입니다.';
		logError($msg);
		echo '<script type="text/javascript">alert("' . $msg. '");location.replace("/rent/search.php");</script>';
		exit;
	}

	$extend_order = mysql_fetch_assoc($r);
	$data['extend_order'] = $extend_order;
}

$query = "SELECT * FROM payments WHERE reservation_idx = $order_idx";
if(mysql_num_rows(mysql_query($query)) > 0) {
	$msg = '결제 완료된 예약입니다.';
	logError($msg);
	echo '<script type="text/javascript">alert("' . $msg. '");location.replace("/rent/search.php");</script>';
	exit;
}

$memberType = 1;
if($ar_init_member['memgrade']=='10' || $ar_init_member['memgrade']=='12')	{
	$memberType = 2;
}
$search = getSearchInfo($memberType, $order['sdate'], $order['edate'], $order['retype'], $order['addr'], $order['raddr'], null, $order['vehicle_idx'], $extend_order);

$q = str_replace('[FIELD]', 't.*', $search['query']);
$result = mysql_query($q);
if(mysql_num_rows($result) < 1) {
	$msg = '예약 가능한 차량이 없습니다.';
	logError($msg);
	echo '<script type="text/javascript">alert("' . $msg. '");location.replace("/rent/search.php");</script>';
	exit;
}
$car = mysql_fetch_assoc($result);

if($coupon) {
	$query = "
		SELECT
			cm.*,
			c.account,
			c.actype
		FROM member_coupons cm
			LEFT JOIN coupons c ON cm.coupon_idx = c.idx
		WHERE
			cm.member_idx = $g_memidx
			AND cm.dt_use IS NULL
			AND cm.idx = $coupon";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		$coupon = mysql_fetch_assoc($result);
	} else {
		$coupon = null;
	}
}

$query = "SELECT * from member WHERE idx = $g_memidx";
$result = mysql_query($query);
$member = mysql_fetch_assoc($result);

$liscense = null;
if($member['driver_license_idx']) {
	$query = "SELECT * FROM driver_license WHERE idx = {$member['driver_license_idx']}";
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result)) {
		$liscense = $row;
	}
}


$amount = $order['totalaccount'];
if($payType == 2) {
	$amount = $order['account1'];
}

$delcharge = $car['delcharge'];
if($ptype == 2 || $extend_order)
	$delcharge = 0;

$amount += $delcharge;

$discountAccount = 0;

if($coupon) {
	$discount = $coupon['account'];
	$percent = false;

	if($coupon['actype'] == 2) {
		$percent = true;
	}

	$discountAccount = $discount = floatval($discount);

	if($percent) {
		$discountAccount = floor($amount * $discount / 10000) * 100;
	}

	$amount -= $discountAccount;
}

if($_SESSION['promotion']) {
	$discount = explode('||', $_SESSION['promotion']);

	$discount = $discount[1];
	$percent = false;

	if(strpos($discount, '%') !== FALSE) {
		$percent = true;
	}

	$discountAccount = $discount = floatval($discount);

	if($percent) {
		$discountAccount = floor($amount * $discount / 10000) * 100;
	}

	$amount -= $discountAccount;
}

$_SESSION['discountAccount'] = $discountAccount;
$_SESSION['paymentAmount'] = $amount;
?>

<?if($buymethod == 'C') {
	$isApp = false;
	$versionStatus = true;
	$appVersion = array(1, 0, 0);
	$platform = '';
	$allat_app_scheme = '';

	require_once($config['incPath']."/Mobile_Detect.php");
	$detect = new Mobile_Detect();
	if ($detect->isMobile()) {
		$ua = $_SERVER['HTTP_USER_AGENT'];

		$pos = strpos($ua, 'rentking');
		if ($pos !== false) {
			$isApp = true;
			$rentking = explode('/', substr($ua, $pos, strlen($ua) - $pos));
			if (count($rentking) > 1) {
				$appVersion = explode('.', $rentking[1]);
			}
		}

		if (stripos($ua, 'android') !== false) {
			$platform = 'android';
			$allat_app_scheme = 'ANDROID';
		}

		if (stripos($ua, 'iPhone') !== false ||
			stripos($ua, 'iPad') !== false ||
			stripos($ua, 'iPod') !== false) {
			$platform = 'ios';
			$allat_app_scheme = 'rentking://';
		}
	}
	?>
	<form name="allatPayment" id="allatPayment" method="post">
		<!-- 필수 정보 -->
		<input type="hidden" name="allat_enc_data" value="">
		<input type="hidden" placeholder="상점 ID"			name="allat_shop_id"	value="<?=$config['allatShopId'][2] ?>" maxlength="20">
		<input type="hidden" placeholder="주문번호"			name="allat_order_no"	value="<?=$order['idx'] ?><?=$config['production'] ? '' : '.dev' ?>" maxlength="70">
		<input type="hidden" placeholder="승인금액"			name="allat_amt"		value="<?=$amount?>" maxlength="10">
		<input type="hidden" placeholder="회원ID"			name="allat_pmember_id"	value="<?=substr($member['id'], 0, 20)?>" maxlength="20">
		<input type="hidden" placeholder="상품코드"			name="allat_product_cd"	value="<?=$car['idx']?>" maxlength="1000">
		<input type="hidden" placeholder="상품명"			name="allat_product_nm"	value="rentking" maxlength="1000">
		<input type="hidden" placeholder="결제자성명"		name="allat_buyer_nm"	value="-" maxlength="20">
		<input type="hidden" placeholder="수취인성명"		name="allat_recp_nm"	value="-" maxlength="20">
		<input type="hidden" placeholder="수취인주소"		name="allat_recp_addr"	value="<?=strlen($order['addr1']) > 0 ? $order['addr1'] : $car['rentshop_addr1']?>" maxlength="120">
		<input type="hidden" placeholder="인증정보수신URL"	name="shop_receive_url"	value="<?=$config['scheme']?>://<?=$_SERVER['HTTP_HOST']?>/payment/allat_receive.php">
		<!-- 필수 정보 -->

		<!-- 옵션 정보 -->
		<input type="hidden" placeholder="신용카드 결제 사용 여부" 				name="allat_card_yn"		value="Y" maxlength="1">
		<input type="hidden" placeholder="계좌이체 결제 사용 여부" 				name="allat_bank_yn"		value="N" maxlength="1">
		<input type="hidden" placeholder="무통장(가상계좌) 결제 사용 여부" 		name="allat_vbank_yn"		value="N" maxlength="1">
		<input type="hidden" placeholder="휴대폰 결제 사용 여부" 				name="allat_hp_yn"			value="N" maxlength="1">
		<input type="hidden" placeholder="상품권 결제 사용 여부" 				name="allat_ticket_yn"		value="" maxlength="1">
		<input type="hidden" placeholder="무통장(가상계좌) 인증 Key" 			name="allat_account_key"	value="" maxlength="20">
		<input type="hidden" placeholder="과세여부" 							name="allat_tax_yn"			value="Y" maxlength="1">
		<input type="hidden" placeholder="할부 사용여부" 						name="allat_sell_yn"		value="Y" maxlength="1">
		<input type="hidden" placeholder="일반/무이자 할부 사용여부" 			name="allat_zerofee_yn"		value="N" maxlength="1">
		<input type="hidden" placeholder="포인트 사용 여부" 					name="allat_bonus_yn"		value="N" maxlength="1">
		<input type="hidden" placeholder="현금 영수증 발급 여부" 				name="allat_cash_yn"		value="Y" maxlength="1">
		<input type="hidden" placeholder="상품이미지 URL" 						name="allat_product_img"	value="http://img.rentking.co.kr<?=$car['car_image_path'] ? $car['car_image_path'].'?'.$car['car_image_timestamp'] : $car['default_image_path'].'?'.$car['default_image_timestamp'] ?>" maxlength="256">
		<input type="hidden" placeholder="결제 정보 수신 E-mail" 				name="allat_email_addr"		value="<?=substr($member['id'], 0, 50)?>" maxlength="50">
		<input type="hidden" placeholder="테스트 여부" 							name="allat_test_yn"		value="<?=$config['production'] ? 'N' : $config['allatTest'] ?>" maxlength="1">
		<input type="hidden" placeholder="상품 실물 여부" 						name="allat_real_yn"		value="Y" maxlength="1">
		<input type="hidden" placeholder="카드 에스크로 적용여부" 				name="allat_cardes_yn"		value="" maxlength="1">
		<input type="hidden" placeholder="계좌이체 에스크로 적용여부" 			name="allat_bankes_yn"		value="" maxlength="1">
		<input type="hidden" placeholder="무통장(가상계좌) 에스 크로 적용여부"	name="allat_vbankes_yn"		value="" maxlength="1">
		<input type="hidden" placeholder="휴대폰 에스크로 적용여부"				name="allat_hpes_yn"		value="" maxlength="1">
		<input type="hidden" placeholder="상품권 에스크로 적용여부" 			name="allat_ticketes_yn"	value="" maxlength="1">
		<input type="hidden" placeholder="주민번호"								name="allat_registry_no"	value="" maxlength="13">
		<input type="hidden" placeholder="KB복합결제 적용여부"					name="allat_kbcon_point_yn"	value="" maxlength="1">
		<input type="hidden" placeholder="제공기간"								name="allat_provide_date"	value="" maxlength="25">
		<input type="hidden" placeholder="성별"									name="allat_gender"			value="" maxlength="1">
		<input type="hidden" placeholder="생년월일"								name="allat_birth_ymd"		value="" maxlength="8">
		<?if($isApp) {?>
			<input type="hidden" placeholder="App Sheme"						name="allat_app_scheme"		value="<?=$allat_app_scheme?>" maxlength="10">
		<?}?>
		<!-- 옵션 정보 -->
	</form>

	<script type="text/javascript">
		function result_submit(resultCd, resultMsg, encData) {
			<?if($mobile) {?>
			Allat_Mobile_Close();
			<?}?>
			$('#allatPayment').remove();
			var $reservationForm = $('#reservationForm');
			if(resultCd != '0000') {
				window.setTimeout(function(){alert(resultMsg);},1000);
			} else {
				$reservationForm.find('input[name="allat_enc_data"]').val(encData);
				$reservationForm.attr('action', '/payment/allat_payment.php').data('payment', true).submit();
			}

			<?if(!$mobile) {?>
			AllatPay_Closechk_End();
			<?}?>
		}
		<?if(!$mobile) {?>
		AllatPay_Approval(allatPayment);
		AllatPay_Closechk_Start();
		<?} else {?>
		Allat_Mobile_Approval(allatPayment, '0', '0');
		<?}?>

	</script>
<?} else if($buymethod == 'F') { ?>
		<form name="allatPayment" id="allatPayment" method="post">
			<!-- 필수 정보 -->
			<input type="hidden" name="allat_enc_data" value="">
			<input type="hidden" placeholder="상점 ID"		name="allat_shop_id"	value="<?=$config['allatShopId'][0] ?>" maxlength="20">
			<input type="hidden" placeholder="주문번호"		name="allat_order_no"	value="<?=$order['idx'] ?>.1<?=$config['production'] ? '' : '.dev' ?>" maxlength="70">
			<input type="hidden" placeholder="Receive URL"	name="shop_receive_url"	value="<?=$config['scheme']?>://<?=$_SERVER['HTTP_HOST']?>/payment/allat_receive.php">
			<!-- 필수 정보 -->

			<!-- 옵션 정보 -->
			<input type="hidden" placeholder="회원ID"					name="allat_pmember_id" value="<?=substr($member['id'], 0, 20)?>" maxlength="20">
			<input type="hidden" placeholder="승인금액"					name="allat_amt" value="" maxlength="20">
			<input type="hidden" placeholder="상품명"					name="allat_product_nm" value="rentking" maxlength="100">
			<input type="hidden" placeholder="결제 정보 수신 E-mail"	name="allat_email_addr" value="<?=substr($member['id'], 0, 50)?>" maxlength="50">
			<input type="hidden" placeholder="주민번호"					name="allat_registry_no" value="" maxlength="13">
			<input type="hidden" placeholder="정기과금 타입"			name="allat_fix_type" value="" maxlength="3">
			<input type="hidden" placeholder="테스트 여부"				name="allat_test_yn" value="<?=$config['production'] ? 'N' : $config['allatTest'] ?>" maxlength="1">
			<!-- 옵션 정보 -->
		</form>

		<script type="text/javascript">
			function result_submit(resultCd, resultMsg, encData) {
				<?if($mobile) {?>
				Allat_Mobile_Close();
				<?}?>
				$('#allatPayment').remove();
				var $reservationForm = $('#reservationForm');
				if(resultCd != '0000') {
					alert(resultMsg);
				} else {
					$reservationForm.find('input[name="allat_enc_data"]').val(encData);
					$reservationForm.attr('action', '/payment/allat_payment.php').data('payment', true).submit();
				}
			}
			<?if(!$mobile) {?>
			Allat_Plus_Fix(allatPayment, '0', '0');
			<?} else {?>
			Allat_Mobile_Fix(allatPayment, '0', '0');
			<?}?>

		</script>

<?}
