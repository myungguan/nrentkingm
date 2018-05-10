<?
/**
 * 차량 검색 페이지
 * www.rentking.co.kr/rent/search.php
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$vehicle_idx = $_REQUEST['vehicle_idx'];
$insu = $_REQUEST['insu'];
$addr = $_REQUEST['addr'];
$raddr = $_REQUEST['raddr'];
$extend = $_REQUEST['extend'];
$data = [];

$retype = 1;
$hours = calcHours($sdate, $edate);
$ddata1 = floor($hours['rent_hour'] / 24);
$ddata2 = fmod($hours['rent_hour'], 24);

$days = calcDays($sdate, $edate);
if($days['months'] > 0) {
	$retype = 2;
	$ddata1 = $days['months'];
	$ddata2 = $days['days'];
}

$rtype = 1;
if(strcmp($addr, $raddr) != 0) {
	$rtype = 2;
}

$memberType = 1;
if($ar_init_member['memgrade']=='10' || $ar_init_member['memgrade']=='12')	{
	$memberType = 2;
}

if($extend != 'Y')
	$extend = 'N';

$extend_order = NULL;
if($extend == 'Y') {
	$query = "SELECT
			m.*
		FROM payments m
			LEFT JOIN reservation mt ON m.reservation_idx = mt.idx
		WHERE
			mt.edate = '$sdate'
			AND mt.vehicle_idx = $vehicle_idx
			AND m.member_idx = $g_memidx
		ORDER BY m.idx DESC
		limit 1
			";
	$r = mysql_query($query);
	if(mysql_num_rows($r) != 1) {
		$data['num'] = 0;
		$data['err'] = '잘못된 예약 정보 입니다.';
		logError($data['err']);
		echo json_encode($data);
		exit;
	}

	$extend_order = mysql_fetch_assoc($r);
	$data['extend_order'] = $extend_order;
}

$search = getSearchInfo($memberType, $sdate, $edate, $retype, $addr, $raddr, null, $vehicle_idx, $extend_order);

$q = str_replace('[FIELD]', 't.*', $search['query']);
$result = mysql_query($q);

$data['num'] = $num = mysql_num_rows($result);
if($num > 0) {
	$car = mysql_fetch_array($result);
	$data['account'] = $car['charge'];
	$data['account1'] = $car['moncharge'];
	$data['account2'] = $car['leftcharge'];
	$data['preaccount'] = $car['precharge'];
	$insuac = 0;

	if($insu == 1) {
		$insuac = $car['price_insu1'];
	}
	if($insu == 2) {
		$insuac = $car['price_insu2'];
	}
	if($insu == 3) {
		$insuac = $car['price_insu3'];
	}
	$data['insuac'] = $insuac;
	$totalaccount = $car['charge'] + $insuac;
	$data['totalaccount'] = $totalaccount;

	$insu_exem = 'NULL';
	$insu_limit = 'NULL';
	$distance_limit = 'NULL';
	$distance_additional_price = 'NULL';

	if($car['pricetype'] == 2 || $insu != 0) {
		if($car['pricetype'] == 2) {
			$insu_exem = $car['price_longterm_insu_exem'];
			$insu_limit = $car['price_longterm_insu_limit'] > 0 ? $car['price_longterm_insu_limit'] : 'NULL';
		} else {
			$insu_exem = $car['price_insu'.$insu.'_exem'];
			$insu_limit = $car['price_insu'.$insu.'_limit'] > 0 ? $car['price_insu'.$insu.'_limit'] : 'NULL';
		}
	}

	if($retype == 2 && $car['distance_limit'] > 0) {
		$distance_limit = $car['distance_limit'];
		$distance_additional_price = $car['distance_additional_price'];
	}

	$sessionId = logSessionId();
	$sessionIdReal = session_id();
	$query = "
		INSERT INTO reservation(
			ip, session_id, session_id_real, vehicle_idx, retype, pricetype,
			ddata1, ddata2, rtype, sdate, edate, dt_create, 
			account, account1, account2, preaccount, insu, insuac, insu_exem, insu_limit, distance_limit, distance_additional_price, totalaccount,
			addr, raddr, extend
		) VALUES (
			'" . ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']) . "', '{$sessionId}', '$sessionIdReal', $vehicle_idx, $retype, {$car['pricetype']},
			$ddata1, $ddata2, $rtype, '$sdate', '$edate', NOW(), 
			{$car['charge']}, {$car['moncharge']}, {$car['leftcharge']}, {$car['precharge']}, '$insu', $insuac, $insu_exem, $insu_limit, $distance_limit, $distance_additional_price, $totalaccount,
			'$addr', '$raddr', '$extend'
		)
	";
	$result = mysql_query($query);

	$idx = mysql_insert_id();

//	$data['query'] = $query;
	$data['idx'] = $idx;
//	$data['login'] = $g_memidx ? true : false;
} else {
	$data['err'] = '선택하신 차량이 이미 예약되었습니다.';
	logError($data['err']);
}


echo json_encode($data);