<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";
include $config['incPath']."/access.php";

$mode = $_REQUEST['mode'];

if($mode=='c')	{
	require_once($config['incPath']."/Mobile_Detect.php");
	$detect = new Mobile_Detect();
	$order_idx = $_REQUEST['order_idx'];

	$query = "
		SELECT
			mt.sdate,
			mt.retype,
			m.ptype,
			m.dt_create
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		WHERE
			m.idx = $order_idx
		";
	$order = mysql_fetch_assoc(mysql_query($query));
	$sdate = $order['sdate'];
	$dt_create = $order['dt_create'];
	$retype = $order['retype'];
	$ptype = $order['ptype'];

	$query = "SELECT * FROM payment_accounts WHERE payment_idx = $order_idx AND tbtype='I' ORDER BY dt_create DESC LIMIT 0,1";
	$account = mysql_fetch_assoc(mysql_query($query));
	$accountReturnable = $account['account'] - $account['isouts'];

	$timeToPickup = (strtotime($sdate)-time())/60/60;
	$timeFromPayment = (time()-strtotime($dt_create))/60/60;

	if(
		$timeFromPayment <= 1		//결제후 한시간 이내
		|| $timeToPickup >= 24		//픽업 24시간 이전
	) {
		include '../payment/allat_cancel_form.php';
		exit;
	} else {
		$value['dan'] = "4";
		$value['dt_cancelr'] = date("Y-m-d H:i:s");
		$value['pdan'] = "1";
		update("payments",$value," WHERE idx='$order_idx'");

		$query = "
			SELECT
				m.*,
				mem.name mem_name,
				mem.cp,
				mt.account,
				mt.sdate,
				mt.edate,
				r.name rentshop_name,
				r.affiliate,
				r.addr1 rentshop_addr1,
				r.addr2 rentshop_addr2,
				mt.addr,
				mt.raddr,
				r.dphone2 rentshop_dphone2,
				vs.name modelname,
				v.carnum
			FROM payments m 
				LEFT JOIN member mem ON m.member_idx = mem.idx
				LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
				LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
				LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
				LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
			WHERE
				m.idx = $order_idx
		";
		$order = mysql_fetch_assoc(mysql_query($query));

		$query = "INSERT INTO comments(type, type_idx, public, comment, member_idx, dt_create, dt_update) VALUES
			('payment', $order_idx, 'Y', '취소요청', {$_SESSION['member_idx']}, NOW(), NOW());";
		mysql_query($query);

		//문자 발송
		$kakao = array(
			'order' => $order_idx,
			'wdate' => $order['dt_create'],
			'cancel_date' => date("Y-m-d H:i:s"),
			'sdate' => $order['sdate'],
			'edate' => $order['edate'],
			'model' => $order['modelname'],
			'carnum' => $order['carnum'],
			'ac' => number_format($account['account']).'원',
			'ac_cancel' => number_format($account['account']).'원'
		);

        if(!$config['production']){
            $order['cp'] = '01034460336';
        }
		sendKakao($config['kakaoAuth'], $order['cp'], 'CAN0302', $kakao);
		if($config['production']) {
			sendKakao($config['kakaoAuth'], '01034711155', 'CAN0402', $kakao);	//신윤호
			sendKakao($config['kakaoAuth'], '01035010075', 'CAN0402', $kakao);	//김지원
		}

		echo "<Script>alert('취소요청이 완료되었습니다. 관리자 확인후 취소완료처리 됩니다'); location.replace('/member/payment.php'); </script>";
		exit;
	}
}

$page['type'] = "2";
$page['title'] = "마이페이지";

$q = "SELECT * FROM payments WHERE member_idx='$g_memidx' AND sdate>='".date("Y-m-d H:i:s")."' AND dan='1' ORDER BY dt_create desc limit 0,1";
$r = mysql_query($q);
$ar_data = mysql_fetch_array($r);

if($ar_data[idx])	{

	$ar_car = sel_query_all("vehicles"," WHERE idx='$ar_data[vehicle_idx]'");
	$ar_car_std = sel_query_all("vehicle_models"," WHERE idx='$ar_car[model_idx]'");
	$ar_rentshop = sel_query_all("rentshop"," WHERE idx='$ar_car[rentshop_idx]'");

	$page['isok'] = "Y";
}
$tpls->assign('data', $ar_data);
$tpls->assign('car', $ar_car);
$tpls->assign('carstd', $ar_car_std);
$tpls->assign('rentshop', $ar_rentshop);

$tpls->assign('page', $page);
$tpls->print_('mains');
?>
