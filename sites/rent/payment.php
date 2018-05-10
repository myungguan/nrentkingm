<?
/**
 * 차량 예약 결제 페이지
 * www.rentking.co.kr/rent/payment.php
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";
include $config['incPath']."/access_site.php";

//과거 결제를 한번 거쳤을 경우 관련 정보 초기화
$_SESSION['paymentAmount'] = null;
$_SESSION['discountAccount'] = null;


$order_idx = $_REQUEST['idx'];
$data = array();
$data['idx'] = $order_idx;

$query = "SELECT * FROM reservation WHERE idx = $order_idx";
$result = mysql_query($query);
if(mysql_num_rows($result) < 1) {
	echo '<script type="text/javascript">alert("예약정보가 없습니다.");location.replace("/rent/search.php");</script>';
	exit;
}
$order = mysql_fetch_assoc($result);
$data['order'] = $order;

//연장일 경우
$extend_order = NULL;
if($order['extend'] == 'Y') {
	$query = "SELECT
			m.*,
			mt.retype,
			mt.addr,
			mt.raddr
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
		echo '<script type="text/javascript">alert("잘못된 예약 정보 입니다.");location.replace("/rent/search.php");</script>';
		exit;
	}

	$extend_order = mysql_fetch_assoc($r);
	$data['extend_order'] = $extend_order;
}

$query = "SELECT * FROM payments WHERE reservation_idx = $order_idx";
if(mysql_num_rows(mysql_query($query)) > 0) {
	echo '<script type="text/javascript">alert("결제 완료된 예약입니다.");location.replace("/rent/search.php");</script>';
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
	echo '<script type="text/javascript">alert("예약 가능한 차량이 없습니다.");location.replace("/rent/search.php");</script>';
	exit;
}
$car = mysql_fetch_assoc($result);
$data['car'] = $car;

$coupon = array();
$query = "SELECT
		cm.*,
		c.name,
		c.actype,
		c.account
	FROM 
		member_coupons cm 
		LEFT JOIN coupons c ON cm.coupon_idx=c.idx 
	WHERE
		cm.member_idx='$g_memidx'
		AND cm.dt_use IS NULL
		AND cm.sdate<='".date("Y-m-d H:i:s")."'
		AND cm.edate>='".date("Y-m-d H:i:s")."'
		";
$result = mysql_query($query);
while($row = mysql_fetch_assoc($result))	{
	if($row['canuseac'] > 0) {
		if($row['canuseac'] > $order['totalaccount']) {
			continue;
		}
	}

	$coupon[] = $row;
}
$data['coupon'] = $coupon;

$query = "SELECT * from member WHERE idx = $g_memidx";
$result = mysql_query($query);
$data['member'] = mysql_fetch_assoc($result);

$data['liscense'] = array();
if($data['member']['driver_license_idx']) {
	$query = "SELECT * FROM driver_license WHERE idx = {$data['member']['driver_license_idx']}";
	$result = mysql_query($query);
	while($row = mysql_fetch_assoc($result)) {
		$data['liscense'] = $row;
	}
}


$addr = explode(' ', $data['car']['rentshop_addr1']);
$addr = array_slice($addr, 0, count($addr) > 3 && ($addr[1] == '고양시' || $addr[1] == '성남시') ? 4 : 3);
$addr = implode(' ', $addr);
$data['car']['rentshop_addr'] = $addr;

$data['promotion'] = $_SESSION['promotion'] ? explode('||', $_SESSION['promotion']) : null;

$page['title'] = '결제';
$page['type'] = 1;
$tpls->assign('page', $page);
$tpls->assign('data', $data);
$tpls->print_('mains');