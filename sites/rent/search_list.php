<?
/**
 * 차량 검색 페이지
 * www.rentking.co.kr/rent/search.php
 */

include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";

$data = [];
$page['type'] = "2";
$page['title'] = "차량 검색";

$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$pickupAddr = $_REQUEST['pickupAddr'];
$returnAddr = $_REQUEST['returnAddr'];
$retype = $_REQUEST['retype'];
$rentshopList = $_REQUEST['rentshopList'];

$ptype = $_REQUEST['ptype'];
$grade = $_REQUEST['grade_idx'];
$insu = $_REQUEST['insu'];
$modelQuery = $_REQUEST['modelQuery'];
$model = $_REQUEST['model'];
if($modelQuery)
	$model .= '|'.$modelQuery.'|';
$fuel = $_REQUEST['fuel'];
$option = $_REQUEST['option'];
$outdate = $_REQUEST['outdate'];
$colorQuery = $_REQUEST['colorQuery'];
$color = $_REQUEST['color'];
if($colorQuery)
	$color .= '|'.$colorQuery.'|';
$companyQuery = $_REQUEST['companyQuery'];
$company = $_REQUEST['company'];
if($companyQuery)
	$company .= '|'.$companyQuery.'|';
$nosmoke = $_REQUEST['nosmoke'];

$currentItem = $_REQUEST['currentItem'];
$itemTo = $_REQUEST['itemTo'];

$memberType = 1;
if($ar_init_member['memgrade']=='10' || $ar_init_member['memgrade']=='12')	{
	$memberType = 2;
}

$stime = getTime($sdate);
$etime = getTime($edate);

$totalItem = 0;
$list = [];

$searchLatLng = $_REQUEST['searchLatLng'];

if($rentshopList) {
	$search = getSearchInfo($memberType, $sdate, $edate, $retype, $pickupAddr, $returnAddr, explode(',', $searchLatLng), null, null,
		$rentshopList, $grade, $ptype, $insu, $model, $fuel, $option, $outdate, $color, $company, $nosmoke);
	$data['rent_hour'] = $search['hoursInfo']['rent_hour'];
	$data['total_hour'] = $search['hoursInfo']['total_hour'];
	$data['week_hour'] = $search['hoursInfo']['week_hour'];
	$data['holi_hour'] = $search['hoursInfo']['holi_hour'];
	$data['remain_hour'] = $search['hoursInfo']['remain_hour'];
	$data['months'] = $search['daysInfo']['months'];
	$data['days'] = $search['daysInfo']['days'];

	$q = str_replace('[FIELD]', '1', $search['query']);
	$q = "SELECT SUM(1) FROM ($q) x";
	$data['countQuery'] = $q;
	$totalItem = mysql_result(mysql_query($q), 0, 0);
	if(!$totalItem)
		$totalItem = 0;

	$start = $currentItem;
	$to = $itemTo - $currentItem + 1;

	$q = str_replace('[FIELD]', 't.*', $search['query']. " LIMIT $start, $to");
	$data['listQuery'] = $q;
	$result = mysql_query($q);

	while($row = mysql_fetch_array($result)) {
		$row['promotion'] = $_SESSION['promotion'] ? explode('||', $_SESSION['promotion']) : null;
		$row['outyear'] = substr($row['outdate'],0,4);
		$list[] = $row;
	}
}


$data['totalItem'] = $totalItem;
$data['list'] = $list;
$data['ptype'] = $ptype;
$data['retype'] = $retype;


$tpls->assign('data', $data);
$tpls->assign('page', $page);
$tpls->print_('mains');

$log = [];
if($currentItem == 0) {
	$log['ip'] = ($_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
	$log['sdate'] = $sdate;
	$log['edate'] = $edate;
	$log['count'] = $_REQUEST['searchCount'];
	$log['addr'] = $pickupAddr;
	$log['distance'] = $_REQUEST['distanceM'];
	$log['retype'] = $retype;
	$log['grade'] = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $grade));
	$log['ptype'] = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $ptype));
	$log['insu'] = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $insu));
	$log['model'] = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $model));
	$log['fuel'] = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $fuel));
	$log['option'] = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $option));
	$log['outdate'] = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $outdate));
	$log['color'] = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $color));
	$log['company'] = str_replace('||', '|', preg_replace('/^\\|(.*)\\|$/', '$1', $company));
	$log['nosmoke'] = str_replace('||', '|', $nosmoke);

	if($rentshopList) {
		$log['rentshop'] = count(explode(',', $rentshopList));
	} else {
		$log['rentshop'] = 0;
	}
	$log['total'] = $totalItem;


	$sessionId = logSessionId();
	$sessionIdReal = session_id();
	$query = "INSERT INTO log_search(ip, session_id, session_id_real, sdate, edate, count, addr, distance, retype
			, grade, ptype, insu, model, fuel, `option`, outdate, color, company, nosmoke
			, rentshop, total, dt
		) VALUES (
			'{$log['ip']}', '{$sessionId}', '$sessionIdReal', '{$log['sdate']}', '{$log['edate']}', {$log['count']}, '{$log['addr']}', {$log['distance']}, {$log['retype']}
			, '{$log['grade']}', '{$log['ptype']}', '{$log['insu']}', '{$log['model']}', '{$log['fuel']}', '{$log['option']}', '{$log['outdate']}', '{$log['color']}', '{$log['company']}', '{$log['nosmoke']}'
			, {$log['rentshop']}, {$log['total']}, NOW()
		)";
	mysql_query($query);
}
?>
